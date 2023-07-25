<?php

namespace App\Mail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmails extends Mailable
{
    use Queueable, SerializesModels;

    protected $amountToExport;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $amount)
    {
        $this->amountToExport = $amount;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = Auth::user();
        $amount = $this->amountToExport;
        $today = date('Y-m-d');

        $data = [
            'user' => $user,
            'amount' => $amount,
            'today' => $today
        ];

        return $this->from('Financialdistrictfirm@gmail.com')
            ->subject('Solicitud de retiro #' . random_int(00000000, 99999999))
            ->view('mail-template', $data);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
