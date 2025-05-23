<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $otp; // Переменная для хранения OTP

    /**
     * Create a new message instance.
     *
     * @param  int  $otp
     * @return void
     */
    public function __construct($otp) // Принимаем OTP
    {
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Ваш код подтверждения')
                    ->view('emails.verification'); // Указываем шаблон
    }
}