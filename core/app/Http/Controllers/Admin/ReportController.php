<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transaction(Request $request, $username = null)
    {
        $pageTitle = 'Transaction Logs';

        $remarks = Transaction::distinct('remark')->selectRaw('UCASE(REPLACE(remark, "_", " ")) AS remark_text')->orderBy('remark')->get('remark');

        $transactions = Transaction::selectRaw('transactions.*, users.account_number, users.username,
            CASE WHEN transactions.trx_type = "+" THEN "Credited" ELSE "Debited" END AS transaction_type,
            UCASE(REPLACE(remark, "_", " ")) AS remark_text')
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
        ->searchable(['trx', 'username', 'account_number', 'details'])
            ->filterable()
            ->orderable();

        $username = $username ?? $request->username;

        if ($username || $request->has('username')) {
            $transactions = $transactions->where('username', $username);
        }

        if ($username || $request->has('username')) {
            $transactions = $transactions->where('username', $username);
        }
        $transactions = $transactions->dynamicPaginate();

        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    private function getDistinctData($column)
    {
        return UserLogin::select($column)->whereNotNull($column)->where($column, '!=', "")->distinct($column)->orderBy($column)->get()->pluck($column)->toArray();
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';

        $countries = $this->getDistinctData('country');
        $cities = $this->getDistinctData('city');
        $browsers = $this->getDistinctData('browser');
        $allOs = $this->getDistinctData('os');

        $loginLogs = UserLogin::selectRaw('
            user_logins.*,
            users.account_number,
            users.username
        ')
            ->leftJoin('users', 'user_logins.user_id', '=', 'users.id')
            ->searchable(['users.account_number', 'users.username', 'user_ip', 'city', 'country', 'longitude', 'latitude', 'browser', 'os'])
            ->filterable()
            ->orderable()
            ->dynamicPaginate();
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'countries', 'cities', 'browsers', 'allOs'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs = NotificationLog::reportQuery();
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }

    public function loginIp(Request $request)
    {
        $pageTitle = 'Login by IP';

        $loginIps = UserLogin::select(['ip', 'users.username'])->distinct('ip')->join('users', 'user_logins.user_id', '=', 'users.id')->orderBy('ip');

        if ($request->ip) {
            $loginIps = $loginIps->where('ip', $request->ip);
        }

        $loginIps = $loginIps->dynamicPaginate();

        return view('admin.reports.login_ip', compact('pageTitle', 'loginIps'));
    }

    public function updateTransactionDate(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'new_date' => 'required|date'
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);
        $transaction->created_at = $request->new_date;
        $transaction->updated_at = now();
        $transaction->save();

        $notify[] = ['success', 'Transaction date updated successfully'];
        return back()->withNotify($notify);
    }
}
