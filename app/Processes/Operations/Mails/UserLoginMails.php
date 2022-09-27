<?php

namespace App\Processes\Operations\Mails;


class UserLoginMails {


    public static function send($user, $password){

        if(!$user){
            return false;
        }

        try {
            \Mail::send('emails.user.access',
                [
                    'email' => $user->email,
                    'pass' => $password,
                    'client_title' => $user->name,

                ], function ($mail) use($user) {
                    $mail->from(config('mail.from.address'), config('mail.from.name'));
                    $mail->to($user->email, "Доступ в личный кабинет")->subject('Доступ в личный кабинет');
                });
        } catch (\Exception $e) {

        }

        return true;

    }
}