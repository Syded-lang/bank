@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account">
        <div class="account-inner">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="account-form">
                            <div class="account-form__content mb-4">
                                <h3 class="account-form__title mb-2"> @lang('Login Verification')</h3>
                                <p>@lang('Choose your preferred method to receive the verification code')</p>
                            </div>

                            <form action="{{ route('user.login.otp.send') }}" method="POST" class="verify-gcaptcha">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="auth_mode" class="form-label">@lang('Verification Method')</label>
                                    <select name="auth_mode" id="auth_mode" class="form--control" required>
                                        <option value="" disabled selected>@lang('Select verification method')</option>
                                        @if (auth()->check() && auth()->user()->ts)
                                            <option value="2fa">@lang('Google Authenticator')</option>
                                        @endif
                                        @if (gs()->modules->otp_email)
                                            <option value="email" @selected(auth()->check() && auth()->user()->preferred_2fa_method == 'email')>@lang('Email OTP')</option>
                                        @endif
                                        @if (gs()->modules->otp_sms)
                                            <option value="sms" @selected(auth()->check() && auth()->user()->preferred_2fa_method == 'sms')>@lang('SMS OTP')</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Continue')</button>
                                </div>

                                <div class="form-group">
                                    <a href="{{ route('user.login') }}" class="text--base">@lang('Back to Login')</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
