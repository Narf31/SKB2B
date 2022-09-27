<?php

namespace App\Processes\Operations\Mails;


use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Contracts\Subjects;


class ContractMails {



    public static function sendDocuments($contract, $email){

        $bso_title = '';
        if($contract->bso){
            $bso_title = $contract->bso->bso_title;
        }

        if(strlen($email) > 3){
            //Отправляем на почту ссылки

            if(app()->environment() != 'local'){

                try {

                    \Mail::send('emails.contract.documents',
                        [
                            'email' => $email,
                            'bso_title' => $bso_title,

                        ], function ($mail) use($email, $bso_title, $contract) {
                            $mail->from(config('mail.from.address'), config('mail.from.name'));
                            $mail->to($email, "Договор {$bso_title}")->subject("Договор {$bso_title}");
                            foreach ($contract->masks as $doc) {
                                $mail->attach(storage_path() . '/app/' . $doc->getPathAttribute());
                            }
                        });

                } catch (\Exception $e) {

                }


            }

            return true;
        }
        return false;
    }

}