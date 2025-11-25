<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $billingDetails;

    /**
     * Create a new message instance.
     */
    public function __construct(Project $project, string $billingDetails)
    {
        $this->project = $project;
        $this->billingDetails = $billingDetails;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Invoice Request Confirmation: {$this->project->name} (Manual Activation)",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Iske liye humein resources/views/emails/invoice_request.blade.php banana hoga.
        return new Content(
            markdown: 'emails.invoice_request',
            with: [
                'projectName' => $this->project->name,
                'billingDetails' => $this->billingDetails,
                'projectAdminEmail' => config('mail.from.address') // Ya aapka koi support email
            ],
        );
    }
}
