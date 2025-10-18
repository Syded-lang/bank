<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NowPaymentsController extends Controller
{
    private $apiKey;
    private $apiUrl = 'https://api.nowpayments.io/v1';
    
    public function __construct()
    {
        $this->apiKey = env('NOWPAYMENTS_API_KEY');
    }

    /**
     * Get JWT token from NOWPayments
     * Uses email/password authentication as per API documentation
     */
    private function getJwtToken()
    {
        $email = env('NOWPAYMENTS_EMAIL');
        $password = env('NOWPAYMENTS_PASSWORD');

        // Check if credentials are configured
        if (!$email || !$password) {
            Log::error('NOWPayments credentials not configured in .env');
            return null;
        }

        try {
            // POST Authentication endpoint as per NOWPayments API docs
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/auth', [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $token = $result['token'] ?? null;
                
                if ($token) {
                    Log::info('NOWPayments JWT token obtained successfully');
                    return $token;
                }
            }

            Log::error('NOWPayments Auth Failed', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            
            return null;

        } catch (\Exception $e) {
            Log::error('NOWPayments JWT Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Process crypto withdrawal via NOWPayments
     */
    public function processCryptoWithdrawal(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        
        $withdraw = Withdrawal::where('id', $request->id)
            ->where('status', Status::PAYMENT_PENDING)
            ->with(['user', 'method'])
            ->firstOrFail();

        // Check if API key is configured
        if (!$this->apiKey || $this->apiKey == 'paste_your_api_key_here') {
            $notify[] = ['error', 'NOWPayments API key not configured. Please add it in .env file'];
            return back()->withNotify($notify);
        }

        // Check if this is a crypto withdrawal method
        if (!$this->isCryptoMethod($withdraw->method->name)) {
            $notify[] = ['error', 'This is not a crypto withdrawal method'];
            return back()->withNotify($notify);
        }

        // Get wallet address from user data
        $userWithdrawData = $withdraw->withdraw_information;
        
        // Try different field name variations
        $walletAddress = null;
        $networkType = 'TRC20';
        
        if (is_array($userWithdrawData)) {
            foreach ($userWithdrawData as $field) {
                if (isset($field->name)) {
                    // Check for wallet address field
                    if (stripos($field->name, 'wallet') !== false && stripos($field->name, 'address') !== false) {
                        $walletAddress = $field->value ?? null;
                    }
                    // Check for network type field
                    if (stripos($field->name, 'network') !== false) {
                        $networkType = $field->value ?? 'TRC20';
                    }
                }
            }
        } else {
            // Handle object format
            $walletAddress = $userWithdrawData->Wallet_Address 
                ?? $userWithdrawData->wallet_address 
                ?? $userWithdrawData->{'Wallet Address'} 
                ?? null;
            $networkType = $userWithdrawData->Network_Type 
                ?? $userWithdrawData->network_type 
                ?? $userWithdrawData->{'Network Type'} 
                ?? 'TRC20';
        }

        if (!$walletAddress) {
            $notify[] = ['error', 'Wallet address not found in withdrawal request'];
            return back()->withNotify($notify);
        }

        // Get currency code
        $currency = $this->getCurrencyCode($withdraw->method->name, $networkType);

        // Get JWT token for authentication
        $jwtToken = $this->getJwtToken();
        
        if (!$jwtToken) {
            $notify[] = ['error', 'Failed to authenticate with NOWPayments. Please check your email/password in .env file'];
            return back()->withNotify($notify);
        }

        // Create payout via NOWPayments
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Authorization' => 'Bearer ' . $jwtToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/payout', [
                'withdrawals' => [[
                    'address' => $walletAddress,
                    'currency' => $currency,
                    'amount' => floatval($withdraw->final_amount),
                    'extra_id' => $withdraw->trx,
                ]]
            ]);

            $result = $response->json();

            // Log the response for debugging
            Log::info('NOWPayments Response', ['response' => $result]);

            if ($response->successful() && isset($result['id'])) {
                // Update withdrawal status
                $withdraw->status = Status::PAYMENT_SUCCESS;
                $withdraw->admin_feedback = "Processed automatically via NOWPayments. Payout ID: " . $result['id'];
                $withdraw->save();

                // Notify user
                notify($withdraw->user, 'WITHDRAW_APPROVE', [
                    'method_name' => $withdraw->method->name,
                    'method_currency' => $withdraw->currency,
                    'method_amount' => showAmount($withdraw->final_amount, currencyFormat: false),
                    'amount' => showAmount($withdraw->amount, currencyFormat: false),
                    'charge' => showAmount($withdraw->charge, currencyFormat: false),
                    'rate' => showAmount($withdraw->rate, currencyFormat: false),
                    'trx' => $withdraw->trx,
                    'admin_details' => 'Processed automatically via NOWPayments'
                ]);

                $notify[] = ['success', 'Crypto withdrawal processed successfully via NOWPayments! Payout ID: ' . $result['id']];
                return to_route('admin.withdraw.data.pending')->withNotify($notify);
            }

            // Handle errors
            $errorMessage = $result['message'] ?? $result['error'] ?? 'Unknown error occurred';
            
            // Common error handling
            if (isset($result['code'])) {
                switch ($result['code']) {
                    case 'AUTH_REQUIRED':
                    case 'UNAUTHORIZED':
                        $errorMessage = 'NOWPayments API authentication failed. Please check: 1) API key has payout permissions, 2) Account is verified for payouts, 3) Visit: https://account.nowpayments.io/settings/api-keys';
                        break;
                    case 'INSUFFICIENT_FUNDS':
                        $errorMessage = 'Insufficient balance in NOWPayments account. Please deposit USDT TRC20 to your NOWPayments account.';
                        break;
                    case 'INVALID_ADDRESS':
                        $errorMessage = 'Invalid wallet address provided';
                        break;
                    case 'CURRENCY_NOT_SUPPORTED':
                        $errorMessage = 'Currency not supported by NOWPayments';
                        break;
                }
            }

            $notify[] = ['error', 'NOWPayments Error: ' . $errorMessage];
            return back()->withNotify($notify);

        } catch (\Exception $e) {
            Log::error('NOWPayments Exception', ['error' => $e->getMessage()]);
            $notify[] = ['error', 'Error processing withdrawal: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /**
     * Check if withdrawal method is crypto
     */
    private function isCryptoMethod($methodName)
    {
        $cryptoKeywords = ['BTC', 'ETH', 'USDT', 'TRC20', 'ERC20', 'BEP20', 'BSC', 'Crypto', 'Bitcoin', 'Ethereum', 'Tether', 'Coin'];
        
        foreach ($cryptoKeywords as $keyword) {
            if (stripos($methodName, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get NOWPayments currency code
     */
    private function getCurrencyCode($methodName, $networkType = 'TRC20')
    {
        $methodName = strtoupper($methodName);
        $networkType = strtoupper($networkType);
        
        // Bitcoin
        if (strpos($methodName, 'BTC') !== false || strpos($methodName, 'BITCOIN') !== false) {
            return 'btc';
        }
        
        // Ethereum
        if (strpos($methodName, 'ETH') !== false || strpos($methodName, 'ETHEREUM') !== false) {
            return 'eth';
        }
        
        // USDT (check network)
        if (strpos($methodName, 'USDT') !== false || strpos($methodName, 'TETHER') !== false) {
            if (strpos($methodName, 'TRC20') !== false || strpos($networkType, 'TRC20') !== false) {
                return 'usdttrc20';
            } elseif (strpos($methodName, 'ERC20') !== false || strpos($networkType, 'ERC20') !== false) {
                return 'usdterc20';
            } elseif (strpos($methodName, 'BEP20') !== false || strpos($methodName, 'BSC') !== false || strpos($networkType, 'BEP20') !== false) {
                return 'usdtbsc';
            }
            // Default to TRC20 for USDT
            return 'usdttrc20';
        }
        
        // Litecoin
        if (strpos($methodName, 'LTC') !== false || strpos($methodName, 'LITECOIN') !== false) {
            return 'ltc';
        }
        
        // Ripple
        if (strpos($methodName, 'XRP') !== false || strpos($methodName, 'RIPPLE') !== false) {
            return 'xrp';
        }
        
        // Dogecoin
        if (strpos($methodName, 'DOGE') !== false || strpos($methodName, 'DOGECOIN') !== false) {
            return 'doge';
        }
        
        // Default fallback to BTC
        return 'btc';
    }

    /**
     * Check payout status
     */
    public function checkPayoutStatus($payoutId)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->get($this->apiUrl . '/payout/' . $payoutId);

            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch payout status'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get available balance from NOWPayments
     */
    public function getBalance()
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->get($this->apiUrl . '/payout/balance');

            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch balance'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
