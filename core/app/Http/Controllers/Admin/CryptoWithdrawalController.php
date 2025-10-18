<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class CryptoWithdrawalController extends Controller
{
    /**
     * Process crypto withdrawal automatically via CoinPayments
     */
    public function processCryptoWithdrawal(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        
        $withdraw = Withdrawal::where('id', $request->id)
            ->where('status', Status::PAYMENT_PENDING)
            ->with(['user', 'method'])
            ->firstOrFail();

        // Check if this is a crypto withdrawal method
        if (!$this->isCryptoMethod($withdraw->method->name)) {
            $notify[] = ['error', 'This is not a crypto withdrawal method'];
            return back()->withNotify($notify);
        }

        // Get wallet address from user data
        $userWithdrawData = $withdraw->withdraw_information;
        $walletAddress = $userWithdrawData->Wallet_Address ?? null;
        $networkType = $userWithdrawData->Network_Type ?? 'TRC20';

        if (!$walletAddress) {
            $notify[] = ['error', 'Wallet address not found in withdrawal request'];
            return back()->withNotify($notify);
        }

        // Get CoinPayments gateway credentials
        $gateway = Gateway::where('alias', 'Coinpayments')->where('crypto', 1)->first();
        
        if (!$gateway) {
            $notify[] = ['error', 'CoinPayments gateway not configured'];
            return back()->withNotify($notify);
        }

        $credentials = json_decode($gateway->gateway_parameters);
        $privateKey = $credentials->private_key->value ?? null;
        $publicKey = $credentials->public_key->value ?? null;

        if (!$privateKey || !$publicKey) {
            $notify[] = ['error', 'CoinPayments API credentials not configured'];
            return back()->withNotify($notify);
        }

        // Determine currency code based on network type
        $currencyCode = $this->getCurrencyCode($withdraw->method->name, $networkType);

        // Create withdrawal via CoinPayments API
        try {
            $result = $this->createCoinPaymentsWithdrawal(
                $privateKey,
                $publicKey,
                $walletAddress,
                $withdraw->final_amount,
                $currencyCode
            );

            if ($result['error'] == 'ok') {
                // Update withdrawal status
                $withdraw->status = Status::PAYMENT_SUCCESS;
                $withdraw->admin_feedback = "Crypto withdrawal processed automatically. TX ID: " . ($result['result']['id'] ?? 'N/A');
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
                    'admin_details' => 'Processed automatically via CoinPayments'
                ]);

                $notify[] = ['success', 'Crypto withdrawal processed successfully'];
                return to_route('admin.withdraw.data.pending')->withNotify($notify);
            } else {
                $notify[] = ['error', 'CoinPayments Error: ' . ($result['error'] ?? 'Unknown error')];
                return back()->withNotify($notify);
            }

        } catch (\Exception $e) {
            $notify[] = ['error', 'Error processing withdrawal: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /**
     * Call CoinPayments API to create withdrawal
     */
    private function createCoinPaymentsWithdrawal($privateKey, $publicKey, $address, $amount, $currency)
    {
        $req = [
            'version' => 1,
            'cmd' => 'create_withdrawal',
            'amount' => $amount,
            'currency' => $currency,
            'address' => $address,
            'auto_confirm' => 0, // Set to 1 for automatic confirmation
            'key' => $publicKey,
            'format' => 'json',
        ];

        $req['hmac'] = hash_hmac('sha512', http_build_query($req), $privateKey);

        $ch = curl_init('https://www.coinpayments.net/api.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Check if withdrawal method is crypto
     */
    private function isCryptoMethod($methodName)
    {
        $cryptoKeywords = ['BTC', 'ETH', 'USDT', 'TRC20', 'ERC20', 'BEP20', 'Crypto', 'Bitcoin', 'Ethereum', 'Tether'];
        
        foreach ($cryptoKeywords as $keyword) {
            if (stripos($methodName, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get CoinPayments currency code
     */
    private function getCurrencyCode($methodName, $networkType = 'TRC20')
    {
        // Map withdrawal method names to CoinPayments currency codes
        $methodName = strtoupper($methodName);
        
        if (strpos($methodName, 'BTC') !== false || strpos($methodName, 'BITCOIN') !== false) {
            return 'BTC';
        }
        
        if (strpos($methodName, 'ETH') !== false || strpos($methodName, 'ETHEREUM') !== false) {
            return 'ETH';
        }
        
        if (strpos($methodName, 'USDT') !== false || strpos($methodName, 'TETHER') !== false) {
            // USDT has different networks
            $networkType = strtoupper($networkType);
            if (strpos($networkType, 'TRC20') !== false) {
                return 'USDT.TRC20';
            } elseif (strpos($networkType, 'ERC20') !== false) {
                return 'USDT.ERC20';
            } elseif (strpos($networkType, 'BEP20') !== false) {
                return 'USDT.BEP20';
            }
            return 'USDT.TRC20'; // Default to TRC20
        }
        
        // Add more currencies as needed
        return 'BTC'; // Default fallback
    }
}
