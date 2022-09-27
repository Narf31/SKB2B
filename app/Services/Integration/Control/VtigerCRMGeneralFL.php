<?php

namespace App\Services\Integration\Control;

use App\Models\Clients\GeneralPodftFl;
use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsAddress;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Clients\GeneralSubjectsFl;
use App\Models\Clients\GeneralSubjectsLogs;
use App\Models\Subject\Physical;
use App\Models\User;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use function GuzzleHttp\Psr7\str;
use Mockery\Exception;

class VtigerCRMGeneralFL
{

    const citizenship = [
        'Республика  Беларусь' => 147,
        'Киргизская республика' => 90,
        'Украина' => 161,
        'армянин' => 228,
        'Республика Беларусь' => 147,
        'ВИРГИНСКИЕ ОСТРОВА, БРИТАНСКИЕ' => 190,
        'ФРАНЦИЯ' => 67,
        'ЧЕШСКАЯ РЕСПУБЛИКА' => 188,
        'ОСТРОВ НОРФОЛК' => 180,
        'Республика Молдова' => 25,
        'ЗАПАДНАЯ САХАРА Росстандарта от 14.12.2011 N 1514-ст' => 134,
        'узбекистан' => 136,
        'МОЛДОВА, РЕСПУБЛИКА' => 25,
        'Сербия' => 54,
        'Индия' => 32,
        'Болгария' => 113,
        'Беларусь' => 147,
        'Китай' => 28,
    ];

    const JOB_CREDENTIALS = [
        'Лицо не влияющее на решение (ЛНР)' => 1,
        'Лицо влияющее на решение (ЛВР)' => 2,
        'Лицо принимающее решение (ЛПР)' => 3,
        'Ведение домашнего хозяйства' => 4,
        'Иное' => 5,
    ];

    const JOB_TYPE_ACTIVITY = [
        'Административно-управленческая' => 1,
        'Финансово-экономическая' => 2 ,
        'Информационно-техническая' => 3,
        'Юридическая' => 4,
        'Производство' => 5,
        'Продажи и сбыт' => 6,
        'Обслуживающая' => 7,
    ];

    const MAIN_TYPE_EMPLOYMENT = [
        'предпринимательская деятельность (частная практика; ИП; иной вид самозанятости)' => 2,
        'трудовая деятельность (работа на основании трудового договора)' => 1,
        'Арбитражный управляющий' => 5,
        'ау' => 5,
    ];

    const POSI = [
        'Управляющий' => 1,
        'арбитражный управляющий' => 1,
        'арбитражный' => 1,
        'арбитражный управл' => 1,
        'АУ' => 1,
        'арбитражный управлющий' => 1,
    ];


    public function getDataInfo($start, $request)
    {
        if(isset($request) && isset($request->delete) && $request->delete == 1){

            $parent_users = GeneralSubjects::where('type_id', 0)->where('export_id', '>', 0);
            $parent_users->select(['id']);

            GeneralSubjectsFl::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralSubjectsFl::whereNull('general_subject_id')->delete();
            GeneralSubjectsLogs::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralSubjectsLogs::whereNull('general_subject_id')->delete();
            GeneralSubjectsAddress::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralSubjectsDocuments::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralPodftFl::whereRaw('`general_subject_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings())->delete();
            GeneralPodftFl::whereNull('general_subject_id')->delete();
            $parent_users->delete();
        }

        return VtigerCRMSend::getDataInfo('general_fl', "view=info");
    }

    public function updateDataInfo($start, $counts, $request, $count_all)
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "VtigerCRM не настроен!";

        $response = VtigerCRMSend::getDataInfo('general_fl', "view=updata&start={$start}&counts={$counts}&count_all={$count_all}");
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
            $_tG->title = "{$data->lastname} {$data->firstname} {$data->cf_450}";
            $_tG->birthdate = $data->birthday;
            $_tG->sex = 0;
            if(mb_substr($data->lastname, -1) == 'а') $_tG->sex = 1;
            $hash = GeneralSubjectsInfo::getHash(0, $_tG);
            $general = GeneralSubjectsSearch::search_hash(0, $hash);

            if(!$general){

                $password = GeneralSubjectsInfo::createGeneralSubjectPassword();
                $general = new GeneralSubjects();
                $general->title = $_tG->title;
                $general->hash = $hash;
                $general->inn = $data->inn;
                $general->type_id = 0;
                $general->password = bcrypt(trim($password));
                $general->save();


                $fl = GeneralSubjectsFl::create([
                    'general_subject_id' => $general->id,
                    'birthdate' => getDateFormatEn($_tG->birthdate),
                    'sex' => $_tG->sex,
                    'inn' => $data->inn,
                ]);

                $podft = GeneralPodftFl::create([
                    'general_subject_id' => $general->id,
                ]);

            }else{
                $podft = $general->podft;
                $fl = $general->data;
            }

            $general->export_id = (int)$data->id;
            $general->export_org_id = (int)$data->job_org_id;
            $general->export_is_connection = 0;
            if($general->export_org_id > 0){
                $general->export_is_connection = 1;
            }

            $user = VtigerCRMUsers::getExportUserId($data->export_user_id);
            if($user){
                $general->user_id = $user->id;
                $general->user_organization_id = $user->organization_id;
                $general->user_parent_id = $user->parent_id;
                $general->user_curator_id = $user->curator_id;
            }


            $general->email = $data->email;
            if(strlen($data->mobile) > 7){
                $general->phone = setPhoneNumberFormat(getPhoneFormat($data->mobile), '+7 (ddd) ddd-dd-dd');
            }

            $general->is_resident = ((int)$data->is_resident == 0)?1:0;
            $general->citizenship_id = 51;
            if($general->is_resident == 0){
                $general->citizenship_id = 0;
                if(isset(self::citizenship[$data->citizenship_id])){
                    $general->citizenship_id = self::citizenship[$data->citizenship_id];
                }
            }

            $general->risk_level_id = 0;
            if($data->risk_level == 'Высокая значимость') $general->risk_level_id = 1;
            if($data->risk_level == 'Низкая значимость') $general->risk_level_id = 2;
            $general->risk_history = 'Перенос данных VtigerCRM';

            $general->save();

            GeneralSubjectsLogs::setLogs($general->id, 'Перенос данных VtigerCRM');



            $podft->job_position = $data->job_position;
            $podft->job_credentials_id = 0;
            if(isset(self::JOB_CREDENTIALS[$data->job_credentials])){
                $podft->job_credentials_id = self::JOB_CREDENTIALS[$data->job_credentials];
            }

            $podft->job_type_activity_id = 0;
            if(isset(self::JOB_TYPE_ACTIVITY[$data->job_type_activity])){
                $podft->job_type_activity_id = self::JOB_TYPE_ACTIVITY[$data->job_type_activity];
            }

            if(isset(self::POSI[$podft->job_position])){
                $fl->profession_id = 1;
                $fl->save();
            }


            $podft->financial_business_objectives = $data->ft_financial_business_objectives;
            $podft->information_business_reputation = $data->ft_information_business_reputation;
            $podft->alleged_nature_relationship = $data->ft_alleged_nature_relationship;
            $podft->origin_ds_other_property = $data->ft_origin_ds_other_property;
            $podft->purpose_establishing_relationship = $data->ft_purpose_establishing_relationship;
            $podft->financial_position = $data->ft_financial_position;


            $podft->main_type_employment_id = 0;
            if(isset(self::MAIN_TYPE_EMPLOYMENT[$data->ft_main_type_employment])){
                $podft->main_type_employment_id = self::MAIN_TYPE_EMPLOYMENT[$data->ft_main_type_employment];
                if($podft->main_type_employment_id == 5){
                    $podft->main_type_employment_text = $data->ft_main_type_employment;
                }
            }

            $podft->save();

            if(strlen($data->address_birth) > 3){
                $general_address = $general->getAddressType(0);
                $general_address->address = $data->address_birth;
                $general_address->save();
            }

            if(strlen($data->address_reg) > 3){
                $general_address = $general->getAddressType(1);
                $general_address->address = $data->address_reg;
                $general_address->save();
            }

            if(strlen($data->address_fact) > 3){
                $general_address = $general->getAddressType(2);
                $general_address->address = $data->address_fact;
                $general_address->save();
            }

            if(strlen($data->doc_serie) > 3 && $data->doc_type_id == 'Паспорт гражданина Российской Федерации'){
                $general_documents = $general->getDocumentsType(0);
                $general_documents->is_actual = 1;
                $general_documents->is_main = 1;
                $general_documents->serie = $data->doc_serie;
                $general_documents->number = $data->doc_number;
                $general_documents->date_issue = getDateFormatEn($data->doc_date_issue);
                $general_documents->unit_code = $data->doc_unit_code;
                $general_documents->issued = $data->doc_issued;
                $general_documents->save();
            }


        }
        return true;
    }


    public static function getExportGeneralId($id, $type = 'export_id')
    {
        return GeneralSubjects::where($type, $id)->get()->first();
    }



}