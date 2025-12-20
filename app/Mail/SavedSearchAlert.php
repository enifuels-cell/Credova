<?php

namespace App\Mail;

use App\Models\SavedSearch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

class SavedSearchAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $savedSearch;
    public $properties;
    public $newCount;

    /**
     * Create a new message instance.
     */
    public function __construct(SavedSearch $savedSearch, Collection $properties, int $newCount = 0)
    {
        $this->savedSearch = $savedSearch;
        $this->properties = $properties;
        $this->newCount = $newCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->newCount > 0 
            ? "New properties found for '{$this->savedSearch->name}'"
            : "Your saved search results for '{$this->savedSearch->name}'";

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
            view: 'emails.saved-search-alert',
            with: [
                'savedSearch' => $this->savedSearch,
                'properties' => $this->properties,
                'newCount' => $this->newCount,
                'user' => $this->savedSearch->user,
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
