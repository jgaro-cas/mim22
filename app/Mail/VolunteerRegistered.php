<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VolunteerRegistered extends Mailable
{
    use Queueable, SerializesModels;


    public $mailDatas;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailDatas)
    {
        $this->mailDatas = $mailDatas;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Confirmation d'inscription bénévoles à Festi’Cheyres")
                    ->view('emails.VolunteerRegisteredMail')
                    ->attach('storage/Charte_Benevoles.pdf');
    }
}
