<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertLimitMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dish;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dish)
    {
        $this->dish = $dish;
        $this->queue = 'alert-mail';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.alert_limit', ['dish' => $this->dish])->subject('Dish limit alert');
    }
}
