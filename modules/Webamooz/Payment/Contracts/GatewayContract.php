<?php

namespace Webamooz\Payment\Contracts;

use Webamooz\Payment\Models\Payment;

interface GatewayContract
{

    public function request($amount, $description);

    public function verify(Payment $payment);

    public function redirect();

    public function getName();

}
