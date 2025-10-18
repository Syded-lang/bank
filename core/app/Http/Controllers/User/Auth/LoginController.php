<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Status;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    use AuthenticatesUsers;


    protected $username;


    public function __construct()
    {
        parent::__construct();
        $this->username = $this->findUsername();
    }

    public function showLoginForm()
    {
        $pageTitle = "Login";
        Intended::identifyRoute();
        return view('Template::user.auth.login', compact('pageTitle'));
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        Intended::reAssignSession();

        return $this->sendFailedLoginResponse($request);
    }

    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin($request)
    {

        $validator = Validator::make($request->all(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            Intended::reAssignSession();
            $validator->validate();
        }

    }

    public function logout()
    {
        $this->guard()->logout();
        request()->session()->invalidate();

        $notify[] = ['success', 'You have been logged out.'];
        return to_route('user.login')->withNotify($notify);
    }


    public function authenticated(Request $request, $user)
    {
        // Skip login OTP - go directly to user verification and login
        $user->tv = $user->ts == Status::VERIFIED ? Status::UNVERIFIED : Status::VERIFIED;
        $user->save();
        
        $ip = getRealIP();
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();

        $redirection = Intended::getRedirection();
        return $redirection ? $redirection : to_route('user.home');
    }
    
    public function showLoginOtpSelect()
    {
        if (!session('pending_login_user_id')) {
            return to_route('user.login');
        }
        
        $pageTitle = 'Login Verification';
        return view('Template::user.auth.login_otp_select', compact('pageTitle'));
    }
    
    public function sendLoginOtp(Request $request)
    {
        $request->validate([
            'auth_mode' => 'required|in:2fa,email,sms'
        ]);
        
        $userId = session('pending_login_user_id');
        if (!$userId) {
            $notify[] = ['error', 'Session expired. Please login again.'];
            return to_route('user.login')->withNotify($notify);
        }
        
        $user = \App\Models\User::find($userId);
        if (!$user) {
            $notify[] = ['error', 'User not found.'];
            return to_route('user.login')->withNotify($notify);
        }
        
        $authMode = $request->auth_mode;
        
        // For Google Authenticator
        if ($authMode == '2fa') {
            if (!$user->ts) {
                $notify[] = ['error', 'Google Authenticator is not enabled for your account.'];
                return back()->withNotify($notify);
            }
            session(['login_auth_mode' => '2fa']);
            return to_route('user.login.otp.verify');
        }
        
        // For Email or SMS OTP
        $otpManager = new \App\Lib\OTPManager();
        $additionalData = ['after_verified' => 'user.login.otp.complete'];
        
        try {
            $otpManager->newOTP(
                $user,
                $authMode,
                'LOGIN_OTP',
                $additionalData
            );
            
            session(['login_auth_mode' => $authMode]);
            
            $notify[] = ['success', 'OTP sent to your ' . ($authMode == 'email' ? 'email' : 'phone') . ' successfully'];
            return to_route('user.login.otp.verify')->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }
    
    public function showLoginOtpVerify()
    {
        if (!session('pending_login_user_id')) {
            return to_route('user.login');
        }
        
        $authMode = session('login_auth_mode');
        $pageTitle = 'Verify Login';
        
        return view('Template::user.auth.login_otp_verify', compact('pageTitle', 'authMode'));
    }
    
    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);
        
        $userId = session('pending_login_user_id');
        if (!$userId) {
            $notify[] = ['error', 'Session expired. Please login again.'];
            return to_route('user.login')->withNotify($notify);
        }
        
        $user = \App\Models\User::findOrFail($userId);
        $authMode = session('login_auth_mode');
        
        // Verify Google Authenticator
        if ($authMode == '2fa') {
            if (!verifyG2fa($user, $request->otp)) {
                $notify[] = ['error', 'Invalid Google Authenticator code'];
                return back()->withNotify($notify);
            }
        } else {
            // Verify Email/SMS OTP
            $verification = \App\Models\OtpVerification::where('user_id', $userId)
                ->where('otp', $request->otp)
                ->where('send_via', $authMode)
                ->where('expired_at', '>', now())
                ->whereNull('used_at')
                ->first();
            
            if (!$verification) {
                $notify[] = ['error', 'Invalid or expired OTP code'];
                return back()->withNotify($notify);
            }
            
            $verification->used_at = now();
            $verification->save();
        }
        
        // OTP verified successfully - now complete the login
        auth()->loginUsingId($userId);
        
        // Clear session data
        session()->forget(['pending_login_user_id', 'login_auth_mode']);
        
        // Log the login
        $user->tv = $user->ts == Status::VERIFIED ? Status::UNVERIFIED : Status::VERIFIED;
        $user->save();
        
        $ip = getRealIP();
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();

        $notify[] = ['success', 'You have been logged in successfully'];
        
        $redirection = Intended::getRedirection();
        return $redirection ? $redirection : to_route('user.home')->withNotify($notify);
    }


}
