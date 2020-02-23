<?php


namespace PerfectMoney;

use DOMDocument;
use DOMXPath;
use stdClass;

class Client
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var stdClass
     */
    private $response;


    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response;
    }

    /**
     * @param null $account
     * @return Response
     */
    public function getBalance($account = null)
    {
        $balance = $this->response->setResponse($this->parseHTML($this->request->get('balance')));
        if(is_null($account))
            return $balance;
        return $balance->{$account};
    }

    /**
     * @param $Payer_Account
     * @param $Amount
     * @return Response
     */
    public function createEVoucher($Payer_Account, $Amount)
    {
        return $this->response->setResponse($this->parseHTML($this->request->get('ev_create', compact('Payer_Account', 'Amount'))));
    }

    /**
     * @param $Payee_Account
     * @param $Amount
     * @param $PAY_IN
     * @param $PAYMENT_ID
     * @return Response
     */
    public function confirm($Payee_Account, $Amount, $PAY_IN, $PAYMENT_ID)
    {
        return $this->response->setResponse($this->parseHTML(($this->request->get('confirm', compact('Payee_Account', 'Amount', 'PAY_IN', 'PAYMENT_ID')))));
    }
    /**
     * @param $content
     * @return array|bool
     */
    private function parseHTML($content)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($content);
        $xpath = new DOMXPath($dom);
        $inputs = $xpath->query("//input");
        if($inputs->length <= 0)
            return false;
        $result = [];
        foreach ($inputs as $input)
            $result[$input->attributes[0]->nodeValue] = $input->attributes[2]->nodeValue;
        return json_encode($result);
    }
}
