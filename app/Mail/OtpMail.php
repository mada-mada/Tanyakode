<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode; // Variabel untuk menampung kode
    public $user;    // Variabel untuk menampung data user

    // Terima data saat class dipanggil
    public function __construct($user, $otpCode)
    {
        $this->user = $user;
        $this->otpCode = $otpCode;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi OTP Anda', // Judul Email
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp', // Kita akan buat view ini di Langkah 2
        );
    }
}
