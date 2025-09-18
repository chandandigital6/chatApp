<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\InterestedLead;
class InterestedLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public InterestedLead $lead;

public function __construct(InterestedLead $lead)
{
    $this->lead = $lead->loadMissing('package');
}

public function envelope(): Envelope
{
    $pkg = $this->lead->package;
    return new Envelope(
        subject: 'New Interested Lead' . ($pkg? ' - '.$pkg->name : ''),
    );
}

public function content(): Content
{
    return new Content(
        view: 'emails.interested_lead',
        with: [
            'lead'    => $this->lead,
            'package' => $this->lead->package,
        ],
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
