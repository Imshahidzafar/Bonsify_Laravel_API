<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $message_content;

    /**
     * Create a new message_content instance.
     *
     * @return void
     */

    public function __construct($subject, $message_content)
    {
        //
        $this->subject = $subject;
        $this->message_content = $message_content;
    }

    /**
     * Build the message_content.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.report')->with([
            'subject' => $this->subject,
            'message_content' => $this->message_content,
        ]);
    }
}
