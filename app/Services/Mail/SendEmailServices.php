<?php

namespace App\Services\Mail;

use App\Notifications\EmailVerfication;
use App\Notifications\ResetPassword;

class SendEmailServices
{
    public function sendEmailVerification($data, $token)
    {
        return $data->notify(new EmailVerfication($data, $token));
    }

    public function sendCode($data, $code)
    {
        return $data->notify(new ResetPassword($code, $data));
    }

    public function sendConfirmedOrder($data, $product)
    {
        //
    }

    public function sendPromoInfo()
    {
        //
    }

}



?>
