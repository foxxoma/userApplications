<?php
namespace App\Mail;

use Illuminate\Support\Facades\Storage;

class PlainMail
{
    public $email;

    public function to($email)
    {
        $this->email = $email;

        return $this;
    }

    public function send($message)
    {
        $filename = $this->email . '_' . time() . '.txt';
        return Storage::disk('local')->put("temp/$filename", $message);

        return true;
    }
}
