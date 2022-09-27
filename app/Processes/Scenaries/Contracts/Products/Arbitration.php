<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\ContractsInsurer;
use App\Models\Contracts\ContractsSupplementary;
use App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LAProcedures;
use App\Models\Directories\Products\ProductsSpecialSsettingsFiles;
use App\Models\Directories\Products\Data\LiabilityArbitrationManager;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Operations\Contracts\Object\ContractObject;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Products\CalcArbitration;
use App\Processes\Operations\Contracts\Products\CalcFlats;
use App\Processes\Operations\Contracts\Products\CalcMigrants;
use App\Processes\Operations\Contracts\Products\CalcVzr;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;

class Arbitration {

    public static function save(Contracts $contract, $data){

        $arbitration = (object)$data->arbitration;

        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));
        $contract->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');
        $contract->end_date = setDateTimeFormat($data->end_date.' 23:59:59');

        $contract->is_prolongation = $data->is_prolongation;
        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';
        $contract->prolongation_bso_title = $data->prolongation_bso_title;

        $contract->insurance_amount = getFloatFormat($data->insurance_amount);
        if(isset($data->insurer)){
            $contract->insurer_id = Subjects::saveOrCreateOnlineSubject((object)$data->insurer, $contract->insurer_id, $contract->agent_id)->id;
        }

        $count_current_procedures = (isset($arbitration->count_current_procedures) ? $arbitration->count_current_procedures : 1);
        $type_agr_id = 1;
        if($contract->program->slug == 'procedural') $type_agr_id = 2;

        $contract->data()->update([
            'cro_id' => (isset($arbitration->cro_id)?$arbitration->cro_id:null),
            'type_agr_id' => $type_agr_id,
            'count_current_procedures' => $count_current_procedures,
            'count_complaints' => (int)getFloatFormat($arbitration->count_complaints),
            'count_warnings' => (int)getFloatFormat($arbitration->count_warnings),
            'count_fines' => (int)getFloatFormat($arbitration->count_fines),
            'experience' => (int)getFloatFormat($arbitration->experience),
            'is_urgently' => (isset($arbitration->is_urgently) ? 1 : 0),
            'sign_date' => $contract->sign_date,
            'begin_date' => $contract->begin_date,
            'end_date' => $contract->end_date,
        ]);

        $contract->save();

        if($contract->program->slug == 'procedural'){

            $procedureData = (object)$data->procedure;
            $procedure = $contract->data->procedure;
            if(!$procedure){
                $procedure = LAProcedures::create([
                    'contract_id' => $contract->id,
                ]);

                $contract->data()->update([
                    'procedure_id' => $procedure->id,
                ]);
            }

            $procedure->title = $procedureData->title;
            $procedure->organization_title = $procedureData->organization_title;
            $procedure->bankruptcy_procedures_id = (int)$procedureData->bankruptcy_procedures_id;
            $procedure->inn = $procedureData->inn;
            $procedure->ogrn = $procedureData->ogrn;
            $procedure->address = $procedureData->address;
            if($procedureData->latitude) $procedure->latitude = $procedureData->latitude;
            if($procedureData->latitude) $procedure->longitude = $procedureData->longitude;
            $procedure->content_html = $procedureData->content;
            $procedure->save();
        }



        return true;

    }


    public static function calc(Contracts $contract)
    {
        return CalcArbitration::calc($contract);
    }


    public static function getPrintData(Contracts $contract)
    {

        $templates = [];

        $special_settings = $contract->program->special_settings;
        $info = ProductsSpecialSsettingsFiles::where('special_settings_id',$special_settings->id)
            ->whereIn('type_name',['contract','policy'])
            ->get();

        if($special_settings && $info) {
            foreach($info as $data){
                $special_file = $special_settings->files->where('id',$data->file_id)->first();
                $original_name = $special_file->original_name;
                $original_name = explode('.', $original_name);
                $original_name = $original_name[0];
                $templates[] = ['path' => $special_file, 'title' => $original_name, 'info' => $data];
            }
        }

        if(!sizeof($templates)){
            return [];
        }

        $arbitration = $contract->data;

        $quantity = 1;
        if($contract->installment_algorithms && $pay_info = $contract->installment_algorithms->info){
            $quantity = $pay_info->quantity;

            $payments_text = '';
            $all_sum = $contract->payment_total;
            $first_payment = '';
            $second_payment = '';

            foreach ($pay_info->algorithm_list as $pay_item){
                $payments_text.= titleFloatFormat($pay_item->payment * $all_sum / 100)." - до ";
                if($pay_item->month) {
                    $payments_text .= date('d.m.Y', strtotime($contract->sign_date . ' +'.$pay_item->month.' months'));
                    $second_payment .= '• в размере '.titleFloatFormat($pay_item->payment * $all_sum / 100);
                    $second_payment .= ' ('.num2str($pay_item->payment * $all_sum / 100).')'.'</w:t><w:br/><w:t>';
                    $second_payment .= ' в срок до '.self::getDateWithRuMonth($contract->sign_date . ' +'.$pay_item->month.' months').'</w:t><w:br/><w:t>';
                }else{
                    $payments_text .= date('d.m.Y', strtotime($contract->sign_date . ' +3 days'));
                    $first_payment = 'В размере '.titleFloatFormat($pay_item->payment * $all_sum / 100);
                    $first_payment .= ' ('.num2str($pay_item->payment * $all_sum / 100).')'.'</w:t><w:br/><w:t>';
                    $first_payment .= ' подлежит уплате до '.self::getDateWithRuMonth($contract->sign_date . ' +3 days');
                }
                $payments_text.= '; ';
            }
            $payments_text = ($payments_text == '') ? '' : mb_substr($payments_text,0, -2);
        }
        $payment_type = isset($contract->payments) ? $contract->payments->first()->payment_type : 0;

        $contract_period = 'Указанный в настоящем пункте срок страхования является для целей настоящего Договора периодом страхования.';
        $contract_period_add = '';
        $contract_is_retro_period = 'не установлен';
        $policy_period = '';
        $retroactive_period_description = '';

        if(isset($arbitration) && $arbitration->retroactive_period_data){
            $contract_is_retro_period = 'установлен';
            $contract_period_add = ':  с '. setDateTimeFormatRu($arbitration->retroactive_period_data,1).' по '.setDateTimeFormatRu($contract->begin_date.'-1 day',1).'.';
            $contract_period_add .= ' Указанные в настоящем пункте в совокупности срок страхования и ретроактивный период являются для целей настоящего Договора периодом страхования';
            $contract_period = 'Страхование распространяется на указанные в настоящем Договоре события, произошедшие в период страхования, в том числе в течение ретроактивного периода, и обнаруженные Страхователем (Застрахованным лицом, Выгодоприобретателем) в период действия настоящего Договора.';
            $contract_period .= ' Событие, наступившее в ретроактивный период, может быть признано Страховщиком страховым случаем только при условии, что Страхователю (Застрахованному лицу) на момент заключения настоящего Договора не было известно и не должно было быть известно об этом событии.';
            $policy_period = ', а также ретроактивного периода с '. setDateTimeFormatRu($arbitration->retroactive_period_data,1).' по '.setDateTimeFormatRu($contract->begin_date.'-1 day',1).', с учетом положений п. 6.1 Договора';
        }

        $data = [

            'settings' => [
                'templates'=> $templates,
            ],
            'info' => [
                'sign_date' => setDateTimeFormatRu($contract->sign_date, 1),
                'sign_date_format' => self::getDateWithRuMonth($contract->sign_date),
                'begin_date' => setDateTimeFormatRu($contract->begin_date),
                'begin_date_format' => self::getDateWithRuMonth($contract->begin_date),
                'end_date' => setDateTimeFormatRu($contract->end_date, 1),
                'end_date_format' => self::getDateWithRuMonth($contract->end_date),
                'number' => $contract->bso_title,

                'payment_total' => titleFloatFormat($contract->payment_total),
                'payment_total_text' => num2str($contract->payment_total),
                'insurance_amount' => titleFloatFormat($contract->insurance_amount),
                'insurance_amount_text' => num2str($contract->insurance_amount),
                'tariff' => $contract->insurance_amount > 0 ? titleFloatFormat(100 * $contract->payment_total/$contract->insurance_amount) : '',
                'quantity' => $quantity == 1 ? 'единовременно' : 'в рассрочку',
                'payments_text' => $payments_text,
                'payment_type' => $payment_type ? 'безналичным перечислением' : 'наличными денежными средствами',
                'payment_data' => $payment_type ? 'ООО СО «ВЕРНА», Южный филиал АО «РАЙФФАЙЗЕНБАНК» г. Краснодар, р/с 40701810726000000000, к/с 30101810900000000556, БИК 040349' : '',
                'policy_payment_type' => $payment_type ? 'безналичным перечислением на расчетный счет ООО СО «ВЕРНА»:</w:t><w:br/><w:t> Южный филиал АО «РАЙФФАЙЗЕНБАНК» г. Краснодар,</w:t><w:br/><w:t> р/с 40701810726000000000, к/с 30101810900000000556, БИК 040349' : 'наличными денежными средствами',
                'first_payment' => $first_payment,
                'second_payment' => $second_payment,

                'count_current_procedures' => isset($arbitration) ? LiabilityArbitrationManager::CURRENT_PROCEDURES[ $arbitration->count_current_procedures] : '',
                'cro_title' => (isset($arbitration) &&  $arbitration->cro)? $arbitration->cro->title : '',
                'count_complaints' => isset($arbitration)? $arbitration->count_complaints : '',
                'count_warnings' => isset($arbitration)? $arbitration->count_warnings : '',
                'count_fines' => isset($arbitration)? $arbitration->count_fines : '',
                'contract_period' => $contract_period,
                'contract_period_add' => $contract_period_add,
                'policy_period' => $policy_period,
                'contract_is_retro_period' => $contract_is_retro_period,
                'procedure_deal' => isset($arbitration) && isset($arbitration->procedure) ? $arbitration->procedure->title : '',
                'bankruptcy_procedures' => isset($arbitration) && isset($arbitration->procedure) ? LAProcedures::BANKRUPTCY_PROCEDURES_ROD[$arbitration->procedure->bankruptcy_procedures_id] : '',
                'procedure_organization' => isset($arbitration) && isset($arbitration->procedure) ? $arbitration->procedure->organization_title : '',

            ],
        ];

        $data['info'] = array_merge($data['info'], self::getInsurersPrint($contract->insurer, 'insurer'));

        return $data;

    }
    public static function getDateWithRuMonth($date){
        $res = date('\«d\» ', strtotime($date));
        $month =  (int) date('m', strtotime($date));
        $res .= getMonthById($month,'rod');
        $res .= date(' Y\г.', strtotime($date));

        return $res;
    }

    public static function getInsurersPrint($subject, $key){

        if($subject->type && $key != 'owner'){
            $title_ul = $subject->title.", ИНН ".$subject->inn.", ОГРН ".$subject->ogrn;
            if(isset($subject->data()->bank_id)){
                $title_ul.=", ".Bank::find($subject->data()->bank_id)->title.', р/с '.$subject->data()->rs.', к/с '.$subject->data()->ks.', БИК '.$subject->data()->bik;
            }
            $title_ul.= ', '.$subject->data()->address_fact;
        }
        $fio = '';
        if($subject->type == 0){
            $temp = explode(' ',$subject->title);
            $fio = $temp[0];
            if(isset($temp[1])){
                $fio .= ' '.mb_substr($temp[1],0, 1).'.';
            }
            if(isset($temp[2])){
                $fio .= mb_substr($temp[2],0, 1).'.';
            }
        }
        $res = [
            "{$key}" => ($subject->type && $key != 'owner') ? $title_ul : $subject->title,
            "{$key}_title" => $subject->title,
            "{$key}_fio" => $fio,
            "{$key}_doc_serie" => isset($subject->doc_serie) ? $subject->doc_serie : '',
            "{$key}_doc_number" => isset($subject->doc_number) ? $subject->doc_number : '',
            "{$key}_inn" => isset($subject->inn) ? $subject->inn : '',
            "{$key}_snils" => isset($subject->general) && isset($subject->general->data) && isset($subject->general->data->snils) ? $subject->general->data->snils : '',
            "{$key}_phone" => isset($subject->phone) ? $subject->phone : '',
            "{$key}_email" => isset($subject->email) ? $subject->email : '',
            "{$key}_sex_end" => $subject->sex ? 'ая' : 'ый',
            "{$key}_birthdate" => $subject->type == 0 ? setDateTimeFormatRu($subject->data()->birthdate,1) : '',
            "{$key}_address_born" => $subject->type == 0 ? $subject->data()->address_born : '',
            "{$key}_is_resident" => $subject->is_resident == 1 ? 'Резидент' : 'Нерезидент',
            "{$key}_address_register" => $subject->type == 0 ? $subject->data()->address_register : '',
            "{$key}_address_fact" => $subject->type == 0 ? $subject->data()->address_fact : '',
            "{$key}_doc_info" => $subject->type == 0 ? setDateTimeFormatRu($subject->data()->doc_date,1).', '.$subject->data()->doc_info.', к/п '.$subject->data()->doc_office : '',
        ];

        return $res;

    }

    public static function copy(Contracts $contract){



        return null;

    }






}