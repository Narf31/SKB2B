<?php

namespace App\Processes\Operations\Mails;


use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Contracts\Subjects;


class PaymentMails {



    public static function sendLink($payment, $link){

        $email = $payment->send_email;
        $bso_title = '';
        $time = date('H:i d.m.Y', strtotime($payment->invoice->created_at)+1200);
        if($payment->contract->bso){
            $bso_title = $payment->contract->bso_title;
        }

        if(strlen($email) > 3){
            //Отправляем на почту ссылки

            if(app()->environment() != 'local'){

                try {



                    \Mail::send('emails.payment.payment_link',
                        [
                            'email' => $email,
                            'link' => $link,
                            'bso_title' => $bso_title,
                            'time' => $time,

                        ], function ($mail) use($email) {
                            $mail->from(config('mail.from.address'), config('mail.from.name'));
                            $mail->to($email, "Оплата договора")->subject('Оплата договора');
                        });

                } catch (\Exception $e) {

                }


            }

            return true;
        }
        return false;
    }

}