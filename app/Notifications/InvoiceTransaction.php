<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceTransaction extends Notification
{
    use Queueable;
    private $receiver;
    private $number;
    private $mode_of_payment;
    private $through;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($receiver, $number, $mode_of_payment, $through)
    {
        $this->receiver = $receiver;
        $this->number = $number;
        $this->mode_of_payment = $mode_of_payment;
        $this->through = $through;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->view('transactions.paymentDetails',['receiver' => $this->receiver, 'number'=>$this->number, 'payment_method'=>$this->through, 'mode'=>$this->mode_of_payment]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
