<?php

namespace App\Processes\Operations\GeneralSubjects;



use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Contracts\Subjects;

class GeneralSubjectsMails {




    public static function sendPassword($general, $password){
        if(strlen($general->email) > 3){
            //Отправляем на почту пароль

            return false;

            if(app()->environment() != 'local'){

                try {
                    \Mail::send('emails.client.access',
                        [
                            'email' => $general->email,
                            'pass' => $password,
                            'client_title' => $general->title,

                        ], function ($mail) use($general) {
                            $mail->from(config('mail.from.address'), config('mail.from.name'));
                            $mail->to($general->email, "Доступ в личный кабинет")->subject('Доступ в личный кабинет');
                        });
                } catch (\Exception $e) {

                }



            }

            return true;
        }
        return false;
    }

}