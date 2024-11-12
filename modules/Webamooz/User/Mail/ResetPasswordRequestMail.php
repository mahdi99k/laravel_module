<?php

namespace Webamooz\User\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordRequestMail extends Mailable
{
    use Queueable, SerializesModels;


    public $code, $firstName;

    public function __construct($firstName, $code)
    {
        $this->code = $code;
        $this->firstName = $firstName;  //firstName
    }

    public function envelope()
    {
        return new Envelope(subject: 'وب آموز | بازیابی رمز عبور',);
    }

    public function content()
    {
        return new Content(markdown: 'User::mails.reset-password-verify-code',);
    }

    public function attachments()
    {
        return [];
    }
}
