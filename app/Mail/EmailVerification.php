<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $url;
    public $user_data;
    public $mail_markdown;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->token = $data["token"];
        $this->url = $data["url"];
        $this->user_data = $data["user_data"];
        $this->mail_markdown = $data["markdown"];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown($this->mail_markdown, [
            "token" => $this->token,
            "url" => $this->url,
            "user_data" => $this->user_data
        ]);
    }
}
