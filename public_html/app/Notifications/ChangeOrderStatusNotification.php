<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeOrderStatusNotification extends Notification
{
    use Queueable;
    public $order;
    public $user;
    public $content;
    public $title;
    public $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
        $this->status = $order->status;

        if($order->status == 'approved') {
            $this->title = config('appMessages.notifications.order.approved.title_' . app()->getLocale());
            $this->content = config('appMessages.notifications.order.approved.content_' . app()->getLocale());
        }

        if($order->status == 'finished') {
            $this->title = config('appMessages.notifications.order.finish.title_' . app()->getLocale());
            $this->content = config('appMessages.notifications.order.finish.content_' . app()->getLocale());
        }
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
        $this->storeInDatabase();
        return (new MailMessage)
                    ->line($this->title)
                    ->action(__('site.check_order'), url('/user/notifications'))
                    ->line(__('site.thanks'));
    }

    public function storeInDatabase()
    {
        if($this->status == 'approved') {
            $title_ar = config('appMessages.notifications.order.approved.title_ar');
            $content_ar = config('appMessages.notifications.order.approved.content_ar');

            $title_en = config('appMessages.notifications.order.approved.title_en');
            $content_en = config('appMessages.notifications.order.approved.content_en');
        }else{
            $title_ar = config('appMessages.notifications.order.finish.title_ar');
            $content_ar = config('appMessages.notifications.order.finish.content_ar');

            $title_en = config('appMessages.notifications.order.finish.title_en');
            $content_en = config('appMessages.notifications.order.finish.content_en');
        }

        \App\Models\Notification::query()->create([
            'title_ar' => $title_ar,
            'content_ar' => $content_ar,
            'title_en' => $title_en,
            'content_en' => $content_en,
            'type' => $this->status,
            'user_id' => $this->order->user->id,
            'subject_id' => $this->order->id,
        ]);
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

     ];
    }
}
