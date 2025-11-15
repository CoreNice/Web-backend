<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ResetPasswordMail extends Mailable
{
    public $resetUrl;
    public $token;

    public function __construct($resetUrl, $token)
    {
        $this->resetUrl = $resetUrl;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Reset Password Akun Anda')
            ->view('emails.reset-password')
            ->with([
                'resetUrl' => $this->resetUrl,
                'token' => $this->token
            ]);
    }
}
