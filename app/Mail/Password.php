<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;



class Password extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The data that will be sent with the mail.
     *
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Here you can define the view that will be used to render the email.
        // You can also pass variables to the view.
        return $this->subject('您正在重置密码')
            ->view('emails.password')
            ->with($this->data);
    }
}
