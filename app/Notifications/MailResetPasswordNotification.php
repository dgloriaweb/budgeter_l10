<?php

namespace App\Notifications;


use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class MailResetPasswordNotification extends ResetPassword
{
    use Queueable;
    protected $pageUrl;
    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        //change back if not working
        parent::__construct($token);
        // parent::__construct($this->token);
        // $this->pageUrl = config('app.url');
        $this->pageUrl = 'http://localhost:8000/api/resetPassword';
            // we can set whatever we want here, or use .env to set environmental variables
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
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }
        return (new MailMessage)
            ->subject(Lang::get('Reset application Password'))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $this->pageUrl . "/" . $this->token . "/" . $notifiable->email)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
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
