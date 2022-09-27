<?php

namespace App\Processes\Scenaries\Contracts\Matchings;

use App\Models\Contracts\Contracts;
use App\Models\Directories\HoldKvMatching;
use App\Models\User;
use App\Processes\Operations\Contracts\Matchings\MatchingKasko;
use App\Processes\Operations\Mails\NotificationMails;
use Illuminate\Support\Str;

class MatchingsAutomatic {


    const default_pso_email = 'zayvka@kompakt-expert.ru';
    const products = [2 => 'КАСКО'];

    public static function checkContract($contract){

        if($contract->product_id > 0 && isset(self::products[$contract->product_id])){

            if($contract->bso){
                if($contract->product_id  == 2){

                    $is_create_pso = MatchingKasko::checkDataOrder($contract, 'inspection');


                    if($contract->calculation){
                        if(strlen($contract->calculation->json) > 0){
                            $_info_tarif = json_decode($contract->calculation->json);
                            if(isset($_info_tarif->is_set_pso)){
                                $is_create_pso =  $_info_tarif->is_set_pso;
                            }
                        }
                    }


                    if($is_create_pso == true){
                        $user = User::query()->where('email', self::default_pso_email)->where('status_user_id', 0)->first();

                        if($user){
                            if(MatchingKasko::createOrUdatePSOOrder($contract) == true){


                                $contract = Contracts::find($contract->id);
                                $inspection = $contract->matching_inspection;
                                $inspection->type_id = 0;
                                $inspection->city_id = 1;
                                $inspection->position_type_id = 1;

                                $inspection->status_id = 2;
                                $inspection->bso_id = $contract->bso_id;
                                $inspection->point_sale_id = $contract->bso->point_sale_id;
                                $inspection->user_id = $user->id;
                                $inspection->work_user_id = $user->id;
                                $inspection->work_status_id = 2;
                                $inspection->supplier_organization_id = $user->organization_id;
                                $inspection->save();

                                $email = new \stdClass();
                                $email->template = "emails.notification.notification";
                                $email->title = "СК ТИТ заявка ПСО #{$inspection->id}";
                                $email->body = '<a href="'.url("/orders/pso/{$inspection->id}").'">Ссылка на заявку</a>';
                                $email->user_email = $user->email;

                                NotificationMails::sendEmail($email);

                                return true;
                            }
                        }
                    }



                }
            }
        }


        return false;
    }






}