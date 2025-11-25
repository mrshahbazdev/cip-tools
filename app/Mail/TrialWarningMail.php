<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Project; // Project model ki zarurat hai

class TrialWarningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $daysLeft;

    /**
     * Create a new message instance.
     */
    public function __construct(Project $project, int $daysLeft)
    {
        $this->project = $project;
        $this->daysLeft = $daysLeft;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $days = ($this->daysLeft == 0) ? 'EXPIRED' : "{$this->daysLeft} Days Left";

        return new Envelope(
            subject: "CIP-Tools Trial Alert: {$days} for {$this->project->name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Yahan par hum email ka HTML body use karenge
        return new Content(
            markdown: 'emails.trial_warning', // Ye view file chahiye
            with: [
                'projectName' => $this->project->name,
                'daysLeft' => $this->daysLeft,
                // Payment link jab Billing setup ho jaye
                'paymentLink' => 'https://' . $this->project->subdomain . '.cip-tools.de/payment',
            ],
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Is method ko tab use karein jab Content::markdown available na ho.
        // Choonke humne Content::markdown use kiya hai, is method ki zarurat nahi hai,
        // lekin agar aap Laravel ke purane version mein hain to isay uncomment kar sakte hain.
        /*
        return $this->markdown('emails.trial_warning')
                    ->with([
                        'projectName' => $this->project->name,
                        'daysLeft' => $this->daysLeft,
                        'paymentLink' => 'https://' . $this->project->subdomain . '.cip-tools.de/payment',
                    ]);
        */
    }
}
