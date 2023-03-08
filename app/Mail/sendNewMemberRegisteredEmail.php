<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendNewMemberRegisteredEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $retailer, $request;

    public function __construct($request, $retailer)
    {
        $this->retailer = $retailer;
        $this->request = $request;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        return $this->view('admin.email.member-registered')
            ->subject("New Retailer Registerd")
            ->with([
                'retailer' => $this->retailer,
                'request' => $this->request,
            ]);
    }
}
