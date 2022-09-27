<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Log;

abstract class BaseEmail extends Mailable {

    use Queueable,
        SerializesModels;

    private $request;

    public function __construct() {

        if (config('mail')) {
            $from = config('mail')['username'];
        } else {
            throw new \exception('Email from invalid');
        }

        $this->from($from);
    }

    public function build(Request $request) {
        
    }

    public function send(\Illuminate\Contracts\Mail\Mailer $mailer) {
        if (config('app.debug') == false) {
            parent::send($mailer);
        } else {
            Log::info('Email send');
        }
    }

}
