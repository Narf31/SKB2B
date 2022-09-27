<?php

namespace App\Services\Integration\Control;

use App\Models\Clients\GeneralPodftFl;
use App\Models\Clients\GeneralPodftUl;
use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsAddress;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Clients\GeneralSubjectsFl;
use App\Models\Clients\GeneralSubjectsLogs;
use App\Models\Clients\GeneralSubjectsUl;
use App\Models\Settings\Bank;
use App\Models\Subject\Physical;
use App\Models\User;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use function GuzzleHttp\Psr7\str;
use Mockery\Exception;

class VtigerCRMGeneralUL
{




    public function getDataInfo($start, $request)
    {
        if(isset($request) && isset($request->delete) && $request->delete == 1){


            $parent_users = GeneralSubjects::where('type_id', 1)->where('export_id', '>', 0);
            $parent_users->select(['id']);

            GeneralSubjectsUl::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralSubjectsUl::whereNull('general_subject_id')->delete();
            GeneralSubjectsLogs::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralSubjectsLogs::whereNull('general_subject_id')->delete();
            GeneralSubjectsAddress::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralSubjectsDocuments::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralPodftUl::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralPodftUl::whereNull('general_subject_id')->delete();
            $parent_users->delete();

        }

        return VtigerCRMSend::getDataInfo('general_ul', "view=info");
    }

    public function updateDataInfo($start, $counts, $request, $count_all)
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "VtigerCRM не настроен!";

        $response = VtigerCRMSend::getDataInfo('general_ul', "view=updata&start={$start}&counts={$counts}&count_all={$count_all}");
        if($response->result){

            $this->setData($response->result);
            $res->state = $response->state;
            $res->msg = $response->msg;
            $res->progressbar = $response->progressbar;
            $res->start = $response->start;

        }

        return $res;
    }

    public function connectionDataInfo()
    {
        $res = new \stdClass();
        $res->state = 0;
        $res->msg = "Физ.лица связаны";



        return $res;
    }


    private function setData($datas)
    {
        foreach ($datas as $data){


            $_tG = new \stdClass();
            $_tG->title = $data->title;
            $_tG->inn = $data->inn;

            $hash = GeneralSubjectsInfo::getHash(1, $_tG);
            $general = GeneralSubjectsSearch::search_hash(1, $hash);

            if(!$general){

                $password = GeneralSubjectsInfo::createGeneralSubjectPassword();
                $general = new GeneralSubjects();
                $general->title = $_tG->title;
                $general->hash = $hash;
                $general->inn = $data->inn;
                $general->type_id = 1;
                $general->password = bcrypt(trim($password));
                $general->save();

                $ul = GeneralSubjectsUl::create([
                    'general_subject_id' => $general->id,
                    'inn' => $data->inn,
                    'ogrn' => $data->ogrn,
                ]);

                $podft = GeneralPodftUl::create([
                    'general_subject_id' => $general->id,
                ]);


            }else{
                $podft = $general->podft;
                $ul = $general->data;
            }

            $general->export_id = (int)$data->id;

            $user = VtigerCRMUsers::getExportUserId($data->export_user_id);
            if($user){
                $general->user_id = $user->id;
                $general->user_organization_id = $user->organization_id;
                $general->user_parent_id = $user->parent_id;
                $general->user_curator_id = $user->curator_id;
            }


            $general->email = $data->email1;
            if(strlen($data->phone) > 10){
                $general->phone = setPhoneNumberFormat(getPhoneFormat($data->phone), '+7 (ddd) ddd-dd-dd');
            }else{
                $general->phone = $data->phone;
            }

            $general->is_resident = 1;
            $general->citizenship_id = 51;





            $general->risk_level_id = 0;
            if($data->risk_level == 'Высокий') $general->risk_level_id = 1;
            if($data->risk_level == 'Низкий') $general->risk_level_id = 2;
            $general->risk_history = $data->risk_history;

            if(strlen($data->risk_date) > 3){
                $general->risk_date = getDateFormatEn($data->risk_date);
            }

            $general->risk_base = $data->risk_base;
            $general->risk_comments = $data->risk_comments;



            $general->save();

            GeneralSubjectsLogs::setLogs($general->id, 'Перенос данных VtigerCRM');

            $address_reg = "{$data->bill_code}, {$data->bill_country} {$data->bill_state} {$data->bill_city} {$data->bill_street}";

            if(strlen($address_reg) > 5){
                $general_address = $general->getAddressType(1);
                $general_address->address = $address_reg;
                $general_address->save();
            }

            $address_fact = "{$data->ship_code}, {$data->ship_country} {$data->ship_state} {$data->ship_city} {$data->ship_street}";
            if(strlen($address_fact) > 5){
                $general_address = $general->getAddressType(2);
                $general_address->address = $address_fact;
                $general_address->save();
            }

            $ul->full_title = $data->full_title;
            $ul->inn = $data->inn;
            $ul->kpp = $data->kpp;
            $ul->ogrn = $data->ogrn;
            $ul->date_orgn = $data->ogrn_date;

            $ul->okpo = $data->okpo;
            $ul->oktmo = $data->oktmo;
            $ul->okfs = $data->okfs;
            $ul->okato = $data->address_okato;
            $ul->okogy = $data->okogy;
            $ul->okopf = $data->okopf_code;
            $ul->okved_code = $data->okved_code;

            $ul->issued = $data->issued;
            $ul->place_registration = $data->place_registration;

            if(strlen($data->bank_name) > 3){
                $ul->bank_id = Bank::getSearchBankId($data->bank_name);
            }else{
                $ul->bank_id = null;
            }

            $ul->bik = $data->bank_bik;
            $ul->rs = $data->bank_rs;
            $ul->ks = $data->bank_ks;


            $ul->share_capital = getFloatFormat($data->share_capital);
            $ul->presence_permanent_management_body = $data->presence_permanent_management_body;
            $ul->license_information = $data->license_information;
            $ul->management_structure = $data->management_structure;
            $ul->undertaken_identify_beneficial = $data->undertaken_identify_beneficial;
            $ul->save();


            if($data->in_whose_interests_id == 'В своих'){
                $podft->in_whose_interests_id = 1;
            }

            if($data->in_whose_interests_id == 'В интересах третьего лица'){
                $podft->in_whose_interests_id = 2;
            }




            $podft->purpose_establishing_relationship = $data->purpose_establishing_relationship;
            $podft->financial_business_objectives = $data->financial_business_objectives;
            $podft->financial_position = $data->financial_position;
            $podft->information_business_reputation = $data->information_business_reputationy;

            $podft->is_recipient_grants = (int)$data->is_recipient_grants;
            $podft->is_budgetary_institution = (int)$data->is_budgetary_institution;
            $podft->is_founder = (int)$data->is_founder;
            $podft->is_beneficiary = (int)$data->is_beneficiary;
            $podft->is_documents_submitted_paper = (int)$data->is_documents_submitted_paper;
            $podft->save();


        }
        return true;
    }





}