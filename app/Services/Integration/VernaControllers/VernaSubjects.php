<?php

namespace App\Services\Integration\VernaControllers;


use App\Models\Clients\GeneralSubjectsLogs;

class VernaSubjects
{

    public $Send = null;

    public function __construct()
    {
        $this->Send = new VernaSend();
    }

    public static function getKIASGeneralId($general)
    {

        //if($general->export_id > 0) return $general->export_id;

        $_class = new VernaSubjects();
        return $_class->create($general, 1);
    }

    public function create($general, $is_return = 0){

        $data = $general->data;
        $user = $general->user;
        $json = json_decode($general->json_data);
        $documents = $general->documents()->get();
        $born = $general->getAddressType(0);
        $addresses = $general->address()->get();
        $fio = (isset($general->title) && $general->type_id==0) ? explode(' ',$general->title) : [];
        $fio_lat = (isset($general->lat) && $general->type_id==0) ? explode(' ',$general->lat) : [];

        if($general->type_id==0){
            $fullName = $general->title;
            $nameLat = $general->lat;
            $shortName = $fio[0];
            $shortName .= isset($fio[1]) ? ' '.mb_substr($fio[1],0,1).'.' : '';
            $shortName .= isset($fio[2]) ? mb_substr($fio[2],0,1).'.' : '';
        }else{
            $fullName = isset($data->full_title) ? $data->full_title : '';
            $nameLat = isset($data->full_title_en) ? $data->full_title_en : '';
            if(isset($json) && is_array($json) && isset($json[0]) && isset($json[0]->basicName)){
                $shortName = isset($json[0]->basicName) ? str_replace('"', '', $json[0]->basicName) : '';
            }else{
                $shortName = '';
            }

        }

        $response = new \stdClass();
        $response->restrictions = new \stdClass();
            $response->restrictions->mode = 'ucChief';
            $response->restrictions->userIsn = isset($user) ? $user->export_user_id : 'userIsn';

        $response->isn = isset($general->export_id) ? "$general->export_id" : '';
        $response->classIsn = 0;
        $response->birthDate = isset($data->birthdate) ? setDateTimeFormatRu($data->birthdate, 1) : '';
        $response->resident = ($general->is_resident == 1) ? "Y" : 'N';
        $response->firstName = isset($fio[1]) ? $fio[1] : '';
        $response->middleName = isset($fio[2]) ? $fio[2] : '';
        $response->lastName = isset($fio[0]) ? $fio[0] : '';
        $response->firstNameLat = isset($fio_lat[1]) ? $fio_lat[1] : '';
        $response->middleNameLat = isset($fio_lat[2]) ? $fio_lat[2] : '';
        $response->lastNameLat = isset($fio_lat[0]) ? $fio_lat[0] : '';
        $response->phone = isset($general->phone) ? $general->phone : '';
        $response->email = isset($general->email) ? $general->email : '';
        $response->juridical = (isset($general->type_id) && $general->type_id == 1) ? "Y" : "N";//Макс. длина: 1 символ
        $response->shortName = $shortName;
        $response->fullName = $fullName;
        $response->nameLat = $nameLat;
        $response->birthPlace = isset($born->address) ? $born->address : '';

        if($general->type_id==1){
            $response->inn = isset($general->inn) ? $general->inn : '';
            $response->iin = '';
            $response->kpp = isset($data->kpp) ? $data->kpp : '';
            $response->okvd = isset($data->okved_code) ? $data->okved_code : '';
            $response->okpo = isset($data->okpo) ? $data->okpo : '';
            $response->ogrn = isset($data->ogrn) ? $data->ogrn : '';
        }


        $response->remark = isset($general->comments) ? $general->comments : '';
        $response->okvdIsn = 0;
        $response->orgFormIsn = 0;
        $response->parentIsn = 0;
        $response->countryIsn = 0;
        $response->familyStateIsn = 0;
        $response->educationIsn = 0;
        $response->professionIsn = 0;
        $response->sex = (isset($data->sex) && $data->sex == 1) ? "М":"Ж";//Макс. длина: 1 символ
        $response->vip = '';

        $response->drivingDateBeg = '';

        $response->documents = new \stdClass();
        $response->documents->document = [];
        foreach($documents as $document) {

            $doc = new \stdClass();
            $doc->type = (isset($document->type_doc))?$document->type_doc->title:'';
            $doc->typeIsn = $document->type_id;
            $doc->series = isset($document->serie) ? $document->serie : '';//Макс. длина: 10 символов
            $doc->number = isset($document->number) ? $document->number : '';//Макс. длина: 20 символов
            $doc->date = isset($document->date_issue) ? setDateTimeFormatRu($document->date_issue, 1) : '';
            $doc->dateEnd = isset($document->driver_exp_date) ? setDateTimeFormatRu($document->driver_exp_date, 1) : '';
            $doc->issuedBy = isset($document->issued) ? $document->issued : '';//Макс. длина: 255 символов
            $doc->divisionCode = isset($document->unit_code) ? $document->unit_code : '';
            $doc->docCountryIsn = isset($general->citizenship_id) ? $general->citizenship->isn : '';

            if($doc->typeIsn == 1145){
                $response->drivingDateBeg = setDateTimeFormatRu($document->driver_exp_date, 1);
            }

            $response->documents->document[] = $doc;
        }


        $response->contacts = new \stdClass();
        $response->contacts->contact = [];
        if(isset($general->phone) && strlen($general->phone) > 0){
             $contact = new \stdClass();
             $contact->type = 'ucPhoneTypeMobile';
             $contact->value = isset($general->phone) ? $general->phone : '';
             $contact->name = 'Телефон';
             $contact->remark = '';
             $response->contacts->contact[] = $contact;
        }

        if(isset($general->email) && strlen($general->email) > 0){
            $contact = new \stdClass();
            $contact->type = 'ucEmailTypePersonal';
            $contact->value = $general->email;
            $contact->name = 'Электронная почта';
            $contact->remark = '';
            $response->contacts->contact[] = $contact;
        }


        $response->addresses = new \stdClass();
        $response->addresses->address = [];

        $a_reg = $this->getAddressGeneral($general, 1);
        if($a_reg){
            $response->addresses->address[] = $a_reg;
        }

        $a_fact = $this->getAddressGeneral($general, 2);
        if($a_fact){
            $response->addresses->address[] = $a_fact;
        }





        $result = $this->Send->send('agent/subjects/add', $response, 'POST');

        //dump($result);

        $status = false;
            //dd($result);
        //

        if($result && isset($result->data) && isset($result->data->result) && isset($result->data->result->isn)){
            $general->export_id = (int)$result->data->result->isn;
            if(isset($result->data->result->antiVip) && $result->data->result->antiVip == "Y"){
                $general->status_work_id = 2;
            }
            $general->save();
            GeneralSubjectsLogs::setLogs($general->id, "Сохранения данных в КИАС (Метод agent/subjects/add) {$general->export_id}");
            $status = true;
        }else{

            $error = '';
            if($result && isset($result->data) && isset($result->data->error) && isset($result->data->error->row)){
                if(is_array($result->data->error->row)){
                    foreach($result->data->error->row as $errors) {
                        if(isset($errors->attr_name)) {
                            $error .= "{$errors->attr_name}: {$errors->fulltext}; ";
                        }elseif(isset($errors->fulltext)){
                            $error .= "{$errors->fulltext}; ";
                        }
                    }
                }else{
                    $errors = $result->data->error->row;
                    if(isset($errors->attr_name) && isset($errors->fulltext)) {
                        $error .= "{$errors->attr_name}: {$errors->fulltext}; ";
                    }elseif(isset($errors->fulltext)){
                        $error .= "{$errors->fulltext}; ";
                    }elseif(isset($errors->text)){
                        $error .= "{$errors->text}; ";
                    }
                    $general->comments = $error;
                    $general->status_work_id = 2;
                    $general->save();
                }

            }
            GeneralSubjectsLogs::setLogs($general->id, "Ошибка сохранения: ".$error);
        }


        if($is_return == 1){
            return $general->export_id;
        }


        return $status;

    }


    public function getSubjectsULKIAS($inn, $ogrn){

        $params = [
            'inn' => $inn,
            'ogrn' => $ogrn,
        ];


        return $this->Send->send('getIdentificationStatus', $params, 'POST');
    }

    public function getAddressGeneral($general, $type_id){

        $address_db = $general->getAddressType($type_id);

        /*
            ISN  CONSTNAME  SHORTNAME
           1299181  cAddrStoaForSMS  АДРЕС СТОА ДЛЯ ОТПРАВКИ В SMS
           2244  cAddrOffice  ОФИС
           2245  cAddrPost  ПОЧТОВЫЙ
           2246  cAddrReg  РЕГИСТРАЦИЯ
           2247  cAddrFact  ФАКТИЧЕСКИЙ
           2248  cAddrJur  ЮРИДИЧЕСКИЙ
            */

        $typeIsn = 2246;
        if($type_id == 1){
            $typeIsn = 2246;
        }
        if($type_id == 2){
            $typeIsn = 2247;
        }

        $address = new \stdClass();
        $address->house = isset($address_db->house) ? $address_db->house : '';
        $address->flat = isset($address_db->flat) ? $address_db->flat : '';
        $address->housecode = isset($address_db->fias_id) ? $address_db->fias_id : '';

        $address->district = new \stdClass();
        $address->district->name = '';
        $address->district->custom_value = '';
        $address->district->code = '';
        $address->district->abbr = '';

        $address->apartment = '';
        $address->typeIsn = $typeIsn;

        $address->city = new \stdClass();
        $address->city->custom_value = '';
        $address->city->code = '';
        $address->city->abbr = '';
        $address->city->name = isset($address_db->city) ? $address_db->city : '';

        $address->building = '';

        $address->street = new \stdClass();
        $address->street->name = isset($address_db->street) ? $address_db->street : '';
        $address->street->custom_value = '';
        $address->street->code = '';
        $address->street->abbr = '';

        $address->region = new \stdClass();
        $address->region->name = isset($address_db->region) ? $address_db->region : '';
        $address->region->custom_value = '';
        $address->region->code = '';
        $address->region->abbr = '';

        $address->postcode = isset($address_db->zip) ? $address_db->zip : '';

        $address->locality = new \stdClass();
        $address->locality->name = '';
        $address->locality->custom_value = '';
        $address->locality->code = '';
        $address->locality->abbr = '';

        $address->housing = isset($address_db->block) ? $address_db->block : '';


        return $address;

    }

}