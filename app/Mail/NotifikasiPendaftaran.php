<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiPendaftaran extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // 1. Properti harus public agar otomatis terbaca di Blade
    public $userEmail;
    public $userName;
    public $userPassword;

    /**
     * 2. TANGKAP data dari luar melalui Constructor
     */
    public function __construct($email, $name, $password)
    {
        $this->userEmail = $email;
        $this->userName = $name;
        $this->userPassword = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Ganti ke email noreply domain kamu
            from: 'mbg@b4its.cloud', 
            subject: 'Pemberitahuan Pendaftaran Penerima Bantuan Makan Bergizi Gratis',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // 3. Pastikan path view ini benar
            view: 'email.pemberitahuan_pendaftaran', 
        );
    }

    public function attachments(): array
    {
        return [];
    }
}