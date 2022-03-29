<?php

namespace JEY\PerfectMoney;

use Illuminate\Support\Traits\Macroable;

class PerfectMoneyAPI
{
    use Macroable;

    /**
     * @param string $account
     * @return array
     */
    public function getAccountName(string $account): array
    {
        return Request::fetch('/acc_name', [
            'Account' => $account,
        ]);
    }

    /**
     * @param ?string $account
     * @return array|false
     */
    public function getBalance(?string $account = NULL): bool|array
    {
        $response = Request::fetch('/balance');
        
        return empty($account)
            ? $response
            : $response['responses'][$account] ?? false;
    }

    /**
     * @param $from
     * @param $to
     * @param $amount
     * @param bool $paymentId
     * @param bool $memo
     * @return array
     */
    public function transferFund($from, $to, $amount, bool $paymentId = false, bool $memo = false): array
    {
        return Request::fetch('/confirm', [
            'Payer_Account' => $from,
            'Payee_Account' => $to,
            'Amount' => $amount,
            'PAY_IN' => 1,
            'PAYMENT_ID' => $paymentId,
            'memo' => $memo,
        ]);
    }

    /**
     * @param $from
     * @param $amount
     * @return array
     */
    public function createEV($from, $amount): array
    {
        return Request::fetch('/ev_create', [
            'Payer_Account' => $from,
            'Amount' => $amount,
        ]);
    }

    /**
     * @param $to
     * @param $number
     * @param $activationCode
     * @return array
     */
    public function transferEV($to, $number, $activationCode): array
    {
        return Request::fetch('/ev_activate', [
            'Payee_Account' => $to,
            'ev_number' => $number,
            'ev_code' => $activationCode,
        ]);
    }

}
