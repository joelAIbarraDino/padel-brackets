<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $payment;
    public $type;
    public $extraData; // puede ser membership o place

    public function __construct($user, $payment, $type, $extraData = null)
    {
        $this->user = $user;
        $this->payment = $payment;
        $this->type = $type;
        $this->extraData = $extraData;
    }

    public function build()
    {
        $subject = $this->type === 'tournament'
            ? 'Reserva de lugar confirmada'
            : 'Compra de membresía completada';

        return $this->subject($subject)
                    ->markdown('emails.payment_success')
                    ->with([
                        'user' => $this->user,
                        'payment' => $this->payment,
                        'type' => $this->type,
                        'extraData' => $this->extraData,
                    ]);
    }
}
