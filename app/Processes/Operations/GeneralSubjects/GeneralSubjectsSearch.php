<?php

namespace App\Processes\Operations\GeneralSubjects;



use App\Models\BSO\BsoItem;
use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsAddress;
use App\Models\Contracts\Subjects;
use App\Models\Contracts\SubjectsFl;

class GeneralSubjectsSearch {

    public static function search_hash($type, $hash)
    {
        return GeneralSubjects::where('type_id', $type)->where('hash', $hash)->get()->first();
    }


    public static function search($query, $limit){
        $response = new \stdClass();
        $response->suggestions = [];

        $fio_arr = explode(' ', $query);
        $fio = $fio_arr[0].' '.$fio_arr[1].' '.$fio_arr[2];
        $birthdate = (isset($fio_arr[3])?$fio_arr[3]:null);

        //Поиск по генеральному контрагенту
        $generals = GeneralSubjects::getAllGeneralSubjects(0);
        $generals->where('general_subjects.title', $fio);

        if($birthdate){
            $generals->leftJoin('general_subjects_fl', 'general_subjects_fl.general_subject_id', '=', 'general_subjects.id');
            $generals->where(\DB::raw('DATE_FORMAT(general_subjects_fl.birthdate, "%d.%m.%Y")'), 'like', "$birthdate%");
        }



        $generals->select('general_subjects.*');
        $generals->limit($limit);


        //Формат ответа
        $data_default = new \stdClass();
        $data_default->value = $fio;
        $data_default->unrestricted_value = $fio;
        $data_default->data = new \stdClass();
        $data_default->data->surname = $fio_arr[0]; // Иванова
        $data_default->data->name = $fio_arr[1]; // Татьяна
        $data_default->data->patronymic = $fio_arr[2]; //Владимировна
        $data_default->data->birthdate = '';
        $data_default->data->gender = '';
        $data_default->data->source = -1;
        $data_default->data->qc = '0';
        $data_default->data->default_text = "Новый контрагент";

        $list = $generals->get();

        $count_generals = count($list);

        foreach ($list as $general){
            //Формат ответа
            $data = new \stdClass();
            $data->value = $general->title.' '.setDateTimeFormatRu($general->data->birthdate, 1);
            $data->unrestricted_value = $general->title;
            $data->data = new \stdClass();
            $data->data->surname = $fio_arr[0]; // Иванова
            $data->data->name = $fio_arr[1]; // Татьяна
            $data->data->patronymic = $fio_arr[2]; //Владимировна
            $data->data->birthdate = $general->data->birthdate;
            $data->data->gender = '';
            $data->data->source = $general->id;
            $data->data->qc = '0';
            $data->data->address = $general->getAddressTitle(1);
            $data->data->document = $general->getDocumentTitle(0);

            $response->suggestions[] = $data;
        }

        if($count_generals < 5){
            $response->suggestions[] = $data_default;
        }

        return $response;
    }

    public static function clone_general($general_id, $subject, $document_type)
    {
        $response = (object)['state'=> false, 'msg' => 'Не удалось сохранить контрагента.'];
        $general = GeneralSubjects::find($general_id);


        $subject->title = $general->title;
        $subject->general_subject_id = $general->id;
        $subject->email = $general->email;
        $subject->is_resident = $general->is_resident;
        $subject->citizenship_id = $general->citizenship_id;
        $subject->user_id = $general->user_id;
        $subject->save();

        if($subject->type == 0){

            $info = $subject->get_info();
            $info->fio = $general->title;
            $info->fio_lat = $general->lat;

            $info->sex = $general->data->sex;
            $info->birthdate = $general->data->birthdate;


            $document = $general->getDocument($document_type);

            if($document){
                $info->doc_type = $document->type_id;
                $info->doc_serie = $document->serie;
                $info->doc_number = $document->number;
                $info->doc_date = $document->date_issue;
                $info->doc_office = $document->unit_code;
                $info->doc_info = $document->issued;
            }


            $info->save();

            GeneralSubjectsSearch::clone_general_address($info, $general, 0);
            GeneralSubjectsSearch::clone_general_address($info, $general, 1);
            GeneralSubjectsSearch::clone_general_address($info, $general, 2);
        }




        $response = (object)['state'=> true, 'msg' => ''];


        return $response;
    }

    public static function clone_general_address($info, $general, $type_id)
    {
        $address = $general->getAddress($type_id);
        $address_name = GeneralSubjectsAddress::TYPE[$type_id];

        if(!$address) return false;

        $data = [];
        if($type_id != 0){

            $data["address_{$address_name}_okato"] = $address->okato;
            $data["address_{$address_name}_zip"] = $address->zip;
            $data["address_{$address_name}_region"] = $address->region;
            $data["address_{$address_name}_city"] = $address->city;
            $data["address_{$address_name}_city_kladr_id"] = $address->city_kladr_id;
            $data["address_{$address_name}_street"] = $address->street;
            $data["address_{$address_name}_house"] = $address->house;
            $data["address_{$address_name}_block"] = $address->block;
            $data["address_{$address_name}_flat"] = $address->flat;

        }

        $data["address_{$address_name}"] = $address->address;
        $data["address_{$address_name}_kladr"] = $address->kladr;
        $data["address_{$address_name}_fias_code"] = $address->fias_code;
        $data["address_{$address_name}_fias_id"] = $address->fias_id;

        $info->update($data);

        return true;
    }

    public static function clear_general($subject, $title)
    {
        $subject->title = $title;
        $subject->general_subject_id = null;
        $subject->save();
        SubjectsFl::where('subject_id', $subject->id)->delete();
        $info = $subject->get_info();
        $info->fio = $title;
        $info->save();

        return (object)['state'=> true, 'msg' => ''];
    }


    public static function registration_general($request)
    {
        $email = $request->email;
        $product_id = $request->product_id;
        $bso_title = $request->bso_title;

        $doc_type = $request->doc_type;
        $doc_serie = $request->doc_serie;
        $doc_number = $request->doc_number;
        $doc_date = $request->doc_date;


        $bso_number = (int)filter_var($bso_title, FILTER_SANITIZE_NUMBER_INT);
        $bso = BsoItem::where('bso_number', $bso_number)
            ->where('state_id', 2)
            ->where('product_id', $product_id)->get()->first();

        if($bso){
            $contract = $bso->contract;
            $insurer = $contract->insurer;
            if($insurer && $insurer->type == 0){
                $info = $insurer->get_info();
                if(
                    $info->doc_type == $doc_type &&
                    $info->doc_serie == $doc_serie &&
                    $info->doc_number == $doc_number &&
                    $info->doc_date == getDateFormatEn($doc_date)
                ){

                    $general = $insurer->general;

                    if(strlen($general->email) > 3){

                        if($general->email != $email){
                            return false;
                        }

                        //Надо подумать если есть email


                    }else{
                        $general->email = $email;
                    }

                    $password = GeneralSubjectsInfo::createGeneralSubjectPassword();
                    $general->password = bcrypt(trim($password));
                    $general->save();

                    GeneralSubjectsMails::sendPassword($general, $password);

                    return true;
                }

            }
        }

        return false;
    }


    public static function get_general_to_subject($general)
    {

        $subject = Subjects::create([
            'type' => $general->type_id,
            'phone' => $general->phone,
            'email' => $general->email,
            'title' => $general->title,
            'general_subject_id' => $general->id,
            'is_resident' => $general->is_resident,
            'citizenship_id' => $general->citizenship_id,
            'user_id' => $general->user_id,
        ]);

        $data = $subject->get_info();
        $data->create([
            'subject_id' => $subject->id,
        ]);

        return $subject;
    }


}