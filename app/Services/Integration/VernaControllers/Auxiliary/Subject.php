<?php

namespace App\Services\Integration\VernaControllers\Auxiliary;



class Subject{

    public $Send = null;


    public static function getSubject($subject)
    {
        return Subject::getDateInfo($subject);
    }





    private static function getDateInfo($subject)
    {
        $docs = '';
        if($subject->type == 0) {
            $sub = Subject::getSubjectFL($subject);
        }else{
            $sub = Subject::getSubjectUL($subject);
        }

        return Subject::response($sub);
    }





    private static function getSubjectFL($subject, $is_general = 0)
    {
        $response = new \stdClass();

        if($is_general == 1){
            $data = $subject->data;
        }else{
            $data = $subject->get_info();
        }




        $Sex = ($data->sex == 0)?"М":"Ж";
        $name = explode(" ", $subject->title);
        $Birthday = date("d.m.Y", strtotime($data->birthdate));
        $Phone = oldSetPhoneNumberFormat(parsePhoneNumber($subject->phone), 'ddd-ddd-dd-dd');
        $Email = ($subject->email)?$subject->email:'';

        $Surname = (isset($name[0])?$name[0]:'');
        $Name = (isset($name[1])?$name[1]:'');
        $Patronymic = str_replace("{$Surname} {$Name} ", '', $subject->title);


        $PhysicalPerson =new \stdClass();
        //$PhysicalPerson->CountryCode = 'Страхователь: ISO-код страны регистрации (Тип: Целое число. Необязательный параметр)';
        $PhysicalPerson->Resident = 'Y';
        $PhysicalPerson->Juridical = 'N';
        $PhysicalPerson->Sex = $Sex;

        $PhysicalPerson->Surname = $Surname;
        $PhysicalPerson->Name = $Name;
        $PhysicalPerson->Patronymic = $Patronymic;


        $PhysicalPerson->BirthDate = $Birthday;
        $PhysicalPerson->Phone = $Phone;
        $PhysicalPerson->Email = $Email;

        if($is_general == 1){
            $PhysicalPerson->PersonDocument = self::getFLDocGeneral($subject);
            $PhysicalPerson->Address =  self::getAddressGeneral($subject);

        }else{
            $PhysicalPerson->PersonDocument = self::getFLDoc($subject);
            $PhysicalPerson->Address =  self::getAddress($subject);
        }

        $response->PhysicalPerson = $PhysicalPerson;

        return $response;
    }




    private static function getFLDoc($subject)
    {
        $data = $subject->get_info();
        $DocDate = setDateTimeFormatRu($data->doc_date, 1);

        $response = new \stdClass();
        $response->Type = $data->doc_type;
        $response->Serial = $data->doc_serie;
        $response->Number = $data->doc_number;
        $response->Date = $DocDate;
        $response->IssuerStr = $data->doc_office;
        $response->IssuerCode = $data->doc_info;

        return $response;

    }

    private static function getFLDocGeneral($subject, $type = 1165)
    {
        $data = $subject->getDocumentsType($type);
        $DocDate = setDateTimeFormatRu($data->date_issue, 1);

        $response = new \stdClass();
        $response->Type = $data->type_id;
        $response->Serial = $data->serie;
        $response->Number = $data->number;
        $response->Date = $DocDate;
        $response->IssuerStr = $data->issued;
        $response->IssuerCode = $data->unit_code;

        return $response;

    }


    private static function getAddressGeneral($subject, $type = 1)
    {
        $data = $subject->getAddressType($type);

        $response = new \stdClass();
        $response->StrValue = $data->address;
        $response->AreaKLADRCode = $data->city_kladr_id;
        $response->ApartmentAddress = new \stdClass();
        $response->ApartmentAddress->Street = $data->street;
        $response->ApartmentAddress->House = $data->house;
        $response->ApartmentAddress->Building = $data->block;
        $response->ApartmentAddress->Flat = $data->flat;

        return $response;

    }




    private static function getAddress($subject)
    {
        $data = $subject->get_info();

        $response = new \stdClass();
        $response->StrValue = $data->address_register;
        $response->AreaKLADRCode = $data->address_register_city_kladr_id;
        $response->ApartmentAddress = new \stdClass();
        $response->ApartmentAddress->Street = $data->address_register_street;
        $response->ApartmentAddress->House = $data->address_register_house;
        $response->ApartmentAddress->Building = $data->address_register_block;
        $response->ApartmentAddress->Flat = $data->address_register_flat;

        return $response;

    }




    public static function getDrivers($drivers)
    {
        $res = new \stdClass();
        $res->Driver = [];

        foreach ($drivers as $driver){


            $Sex = ($driver->sex == 0)?"М":"Ж";
            $name = explode(" ", $driver->title);
            $Birthday = setDateTimeFormatRu($driver->birthdate, 1);
            $DrivingDateBeg = setDateTimeFormatRu($driver->exp_date, 1);
            $DocumentDate = setDateTimeFormatRu($driver->doc_date, 1);


            $Surname = (isset($name[0])?$name[0]:'');
            $Name = (isset($name[1])?$name[1]:'');
            $Patronymic = str_replace("{$Surname} {$Name} ", '', $driver->title);

            $Driver = new \stdClass();

            //$Driver->CountryCode = '';
            $Driver->Resident = 'Y';
            $Driver->Juridical = 'N';
            $Driver->Sex = $Sex;
            $Driver->Surname = $Surname;
            $Driver->Name = $Name;
            $Driver->Patronymic = $Patronymic;
            $Driver->BirthDate = $Birthday;
            $Driver->DrivingDateBeg = $DrivingDateBeg;
            //$Driver->DriverKBM = '';
            $Driver->DriverDocument = new \stdClass();
            $Driver->DriverDocument->Serial = $driver->doc_serie;
            $Driver->DriverDocument->Number = $driver->doc_number;
            //$Driver->DriverDocument->CategoryDriverLicense = '';
            $Driver->DriverDocument->Date = $DocumentDate;

            $res->Driver[] = $Driver;

            /*
             "Driver": {
        "CountryCode": "Водитель: ISO-код страны регистрации (Тип: Целое число. Необязательный параметр)",
        "Resident": "Водитель: признак, является ли резидентом (Тип: Строка. Необязательный параметр. Макс. длина: 1 символов)",
        "Juridical": "Водитель: признак, является ли юридическим лицом (Тип: Строка. Необязательный параметр. Макс. длина: 1 символов)",
        "Sex": "Водитель: пол (Тип: Строка. Необязательный параметр. Макс. длина: 1 символов)",
        "Surname": "Водитель: фамилия (Тип: Строка. Необязательный параметр)",
        "Name": "Водитель: имя (Тип: Строка. Необязательный параметр)",
        "Patronymic": "Водитель: отчество (Тип: Строка. Необязательный параметр)",
        "BirthDate": "Водитель: дата рождения (Тип: Дата. Необязательный параметр)",
        "DrivingDateBeg": "Водитель: дата начала стажа по выбранной категории (Тип: Дата. Необязательный параметр)",
        "DriverKBM": "Водитель: коэффициент КБМ (класс) (Тип: Строка. Необязательный параметр)",
        "DriverDocument": {
          "Serial": "Водительское удостоверение: серия (Тип: Строка. Необязательный параметр)",
          "Number": "Водительское удостоверение: номер (Тип: Строка. Необязательный параметр)",
          "CategoryDriverLicense": "Водительское удостоверение: открытые категории (Тип: Строка. Необязательный параметр)",
          "Date": "Водительское удостоверение: дата выдачи (Тип: Дата. Необязательный параметр)"
        }
             */

        }

        return Subject::response($res);

    }


    private static function getSubjectUL($subject)
    {
        $response = new \stdClass();

        $general = $subject->general;
        $data = $general->data;

        $gentral_manager = self::getSubjectFL($data->gentral_manager(), 1);


        $PhysicalPerson =new \stdClass();
        //$PhysicalPerson->CountryCode = 'Страхователь: ISO-код страны регистрации (Тип: Целое число. Необязательный параметр)';
        $PhysicalPerson->Resident = 'Y';
        $PhysicalPerson->Juridical = 'Y';
        $PhysicalPerson->Address =  self::getAddressGeneral($general, 1);
        $PhysicalPerson->PersonDocument = self::getFLDocGeneral($general, 1169);

        $PhysicalPerson->Phone = oldSetPhoneNumberFormat(parsePhoneNumber($general->phone), 'ddd-ddd-dd-dd');
        $PhysicalPerson->Email = ($general->email)?$general->email:'';

        $PhysicalPerson->OrgRelationChief = $gentral_manager;
        $PhysicalPerson->OrgRepresentative = $gentral_manager;
        $PhysicalPerson->AddressJuridical = self::getAddressGeneral($general, 2);


        $PhysicalPerson->EconomicISN = '';
        $PhysicalPerson->FullName = $general->title;

        $PhysicalPerson->INN = $data->inn;
        $PhysicalPerson->KPP = $data->kpp;
        $PhysicalPerson->OGRN = $data->ogrn;
        $PhysicalPerson->OKPO = $data->okpo;
        $PhysicalPerson->OKVD = $data->okved_code;

        $PhysicalPerson->OkvdIsn = '';
        $PhysicalPerson->OrgFormIsn = '2067';

        $response->PhysicalPerson = $PhysicalPerson;








        return $response;

    }




    private static function response($result = '', $state = true)
    {
        return (object)['state' => $state, 'result' => $result];
    }





}