<?php

namespace Webamooz\Payment\Gateways\Zarinpal;

use Webamooz\Payment\Contracts\GatewayContract;
use Webamooz\Payment\Models\Payment;

class ZarinpalAdaptor implements GatewayContract
{

    private $url, $client;


    public function request($amount, $description)
    {
        $this->client = new Zarinpal();
        $MerchantID = "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx";  //36 character anyway -> sandbox
        $callback = route('payments.callback');
        $result = $this->client->request($MerchantID, $amount, $description, "", "", $callback, true);

        if (isset($result["Status"]) && $result["Status"] == 100) {
            $this->url = $result['StartPay'];
            return $result['Authority'];
        } else {
            // error
            return [
                "status" => $result["Status"],
                "message" => $result["Message"]
            ];
        }
    }

    public function verify(Payment $payment)
    {
        $this->client = new Zarinpal();
        $MerchantID = "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx";  //36 character anyway -> sandbox
        $result = $this->client->verify($MerchantID, $payment->amount, true);

        if (isset($result["Status"]) && $result["Status"] == 100) {
            return $result["RefID"];

        } else {
            return [
                "status" => $result["Status"],
                "message" => $result["Message"]
            ];
        }
    }


    public function redirect()
    {
        $this->client->redirect($this->url);
    }

    public function getName()
    {
        return "zarinpal";
    }

    public function getInvoiceIdFromRequest($request)
    {
        return $request->Authority;
    }

}
