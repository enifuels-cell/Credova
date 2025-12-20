<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingRequest extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Booking Request - ' . $this->booking->property->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new booking request for your property.')
            ->line('Property: ' . $this->booking->property->title)
            ->line('Guest: ' . $this->booking->user->name)
            ->line('Check-in: ' . $this->booking->start_date->format('M d, Y'))
            ->line('Check-out: ' . $this->booking->end_date->format('M d, Y'))
            ->line('Total Price: $' . number_format($this->booking->total_price, 2))
            ->action('Review Booking', route('bookings.show', $this->booking))
            ->line('Please review and respond to this booking request.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'property_title' => $this->booking->property->title,
            'guest_name' => $this->booking->user->name,
            'start_date' => $this->booking->start_date->format('Y-m-d'),
            'end_date' => $this->booking->end_date->format('Y-m-d'),
            'total_price' => $this->booking->total_price,
            'message' => 'New booking request for ' . $this->booking->property->title . ' from ' . $this->booking->user->name,
        ];
    }
}
