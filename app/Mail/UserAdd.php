<?php

namespace App\Mail;

use App\Mail\BaseEmail;
use Illuminate\Http\Request;

class UserAdd extends BaseEmail {

    public function __construct() {
        $this->subject = "Регистрация в системе М5 2.0";
        parent::__construct();
    }
    
    public function build(Request $request) {
        $this->request = $request;

        return $this->markdown('emails.user.add')
                        ->with('email', $this->request->email)
                        ->with('password', $this->request->password);
    }

}
