<?php

namespace App\Mail;

use App\Models\PermohonanUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PermohonanStatusNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $permohonanUser;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct(PermohonanUser $permohonanUser, string $status)
    {
        $this->permohonanUser = $permohonanUser;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->status === 'selesai' ? 'Permohonan Disetujui' : 'Permohonan Ditolak';
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.permohonan_status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
