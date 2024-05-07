<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ichtrojan\Otp\Otp;

class ResetPasswordVerificationNotification extends Notification
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    private $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
        $this->message='Use the below code for resetting your password';
        $this->subject='Resetting your password';
        $this->fromEmail='tset@shroukmeme.com';
        $this->mailer='smtp';
        $this->otp=new Otp();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp=$this->otp->generate($notifiable->email,'numeric',6,60);
        return (new MailMessage)
            ->mailer('smtp')
            ->subject($this->subject)
            ->greeting('Hello!',$notifiable->first_name)
            ->line($this->message)
            ->line('code: '.$otp->token);

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
