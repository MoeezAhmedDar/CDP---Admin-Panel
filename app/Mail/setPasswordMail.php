<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class setPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->subject('Reset Your Password')
        //     ->view('email.resetPassword');

        // return $this->view('admin.email.resetPassword')
        //     ->subject("Passwrod Reset Link")
        //     ->with(['tocken' => $this->token]);

        return $this->view('admin.email.resetPassword')
            ->subject("Set Password Link")
            ->with(['details' => $this->details]);
    }
}
