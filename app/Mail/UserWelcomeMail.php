<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $isAutoCreated;

    public function __construct(User $user, $password = null, $isAutoCreated = false)
    {
        $this->user = $user;
        $this->password = $password;
        $this->isAutoCreated = $isAutoCreated;
    }

    public function build()
    {
        return $this
            ->subject($this->isAutoCreated
                ?'Se ha creado tu cuenta automaticamente'
                :'Bienvenido a Lomas Podal')
            ->markdown('emails.userWelcome');
    }
}
