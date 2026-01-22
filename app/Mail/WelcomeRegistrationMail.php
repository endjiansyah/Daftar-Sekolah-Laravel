<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeRegistrationMail extends Mailable
{
    public function __construct(public User $user) {}

    public function envelope(): Envelope {
        return new Envelope(subject: 'Konfirmasi Pendaftaran PPDB');
    }

    public function content(): Content {
        return new Content(view: 'emails.welcome'); // Mengarah ke file view
    }
}