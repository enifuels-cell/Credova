<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
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
            ->subject('Booking Confirmation - ' . $this->booking->property->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking has been confirmed!')
            ->line('Property: ' . $this->booking->property->title)
            ->line('Location: ' . $this->booking->property->location)
            ->line('Check-in: ' . $this->booking->start_date->format('M d, Y'))
            ->line('Check-out: ' . $this->booking->end_date->format('M d, Y'))
            ->line('Total Price: $' . number_format($this->booking->total_price, 2))
            ->action('View Booking', route('bookings.show', $this->booking))
            ->line('Thank you for choosing our platform!');
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
            'property_location' => $this->booking->property->location,
            'start_date' => $this->booking->start_date->format('Y-m-d'),
            'end_date' => $this->booking->end_date->format('Y-m-d'),
            'total_price' => $this->booking->total_price,
            'message' => 'Your booking for ' . $this->booking->property->title . ' has been confirmed.',
        ];
    }
}
