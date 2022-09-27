<?php

namespace App\Processes\Operations\Mails;


use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Contracts\Subjects;
use App\Models\MailsNotification\MailsNotification;


class NotificationMails {



    public static function send($id){
        $email = MailsNotification::where('id',$id)->get()->first();

        if(!$email){
            return false;
        }

        if(strlen($email->user_email) > 3) {
            //Отправляем уведомление на почту
            if (app()->environment() != 'local') {

                try {
                    \Mail::send($email->template,
                        [
                            'title' => $email->title,
                            'body' => $email->body,

                        ], function ($mail) use ($email) {
                            $mail->from(config('mail.from.address'), config('mail.from.name'));
                            $mail->to($email->user_email, $email->title)->subject($email->title);
                        });
                } catch (\Exception $e) {

                }

            }
        }
        $email->delete();

        return true;

    }

    public static function sendEmail($email){

        if(strlen($email->user_email) > 3) {
            //Отправляем уведомление на почту
            if (app()->environment() != 'local') {

                try {
                    \Mail::send($email->template,
                        [
                            'title' => $email->title,
                            'body' => $email->body,

                        ], function ($mail) use ($email) {
                            $mail->from(config('mail.from.address'), config('mail.from.name'));
                            $mail->to($email->user_email, $email->title)->subject($email->title);
                        });
                } catch (\Exception $e) {

                }

            }
        }
        return true;

    }

    public static function create($user_email,$template,$title,$body,$url){

        MailsNotification::create([
            'user_email' => $user_email,
            'template' => $template,
            'title' => $title,
            'body' => $body,
            'url' => $url,
        ]);
        return true;
    }

    public static function delete($user_email,$url){

        MailsNotification::where('user_email',$user_email)->where('url',$url)->delete();

        return true;
    }
}