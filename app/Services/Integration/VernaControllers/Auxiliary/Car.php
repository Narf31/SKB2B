<?php

namespace App\Services\Integration\VernaControllers\Auxiliary;


use App\Models\Contracts\ContractsCalculation;
use App\Models\Vehicle\VehicleModelsKasko;
use App\Services\Integration\VernaControllers\VernaSubjects;

class Car{

    public static function getCarOSAGO($contract, $owner)
    {
        $vehicle = $contract->object_insurer_auto;

        $DocDate = setDateTimeFormatRu($vehicle->docdate, 1);


        $dk_date = '';
        if($vehicle->dk_date != '1970-01-01 00:00:00') {
            $dk_date = date("d.m.Y", strtotime($vehicle->dk_date));
        }


        $response = new \stdClass();
        $response->Owner = $owner->result;
        $response->CountryCode = $vehicle->country->code;//643;//$vehicle->country->code;//"ISO-код страны регистрации ТС (Тип: Целое число. Обязательный параметр)"

        $response->UseTrailer = ($vehicle->is_trailer == 1) ? "Y" : "N";

        $response->Rent = 'N';
        $response->RightWheel = 'N';
        $response->UseSpecialSignal = 'N';
        $response->VIN = $vehicle->vin;
        $response->BodyNumber = $vehicle->body_number;
        $response->ChassisID = (string)$vehicle->body_chassis;
        $response->MaxMass = (int)$vehicle->weight;
        $response->LicensePlate = $vehicle->reg_number;


        $response->ReleaseDate = '01.01.'.$vehicle->car_year;

        $response->EngCap = (int)$vehicle->power;//
        $response->PeriodBeg1 = getDateFormatRu($contract->data->period_beg1,1);
        $response->PeriodEnd1 = getDateFormatRu($contract->data->period_end1,1);
        $response->PeriodBeg2 = getDateFormatRu($contract->data->period_beg2,1);
        $response->PeriodEnd2 = getDateFormatRu($contract->data->period_end2,1);
        $response->PeriodBeg3 = getDateFormatRu($contract->data->period_beg3,1);
        $response->PeriodEnd3 = getDateFormatRu($contract->data->period_end3,1);



        $response->CarDocument = new \stdClass();
        $response->CarDocument->Type = $vehicle->doc_type;//220219 Паспорт ТС 220220 Свидетельство о регистрации ТС
        $response->CarDocument->Serial = $vehicle->docserie;
        $response->CarDocument->Number = $vehicle->docnumber;
        $response->CarDocument->Date = $DocDate;


        $response->TicketCarDocument = new \stdClass();
        $response->TicketCarDocument->Type = (strlen($vehicle->dk_number) > 0)?220221:'';
        $response->TicketCarDocument->Number = $vehicle->dk_number;
        $response->TicketCarDocument->DiagnosticDate = getDateFormatRu($vehicle->dk_date_from,1);
        $response->TicketCarDocument->DiagnosticNextDate = getDateFormatRu($vehicle->dk_date_to,1);


        //$response->CatCar = "B";//$vehicle->category_auto->isn;//Категория ТС (Тип: Строка. Обязательный параметр)"
        $response->UseCarIsn = $vehicle->purpose->isn;//"Код признака использования ТС (Тип: Целое число. Обязательный параметр)"
        $response->MODELISN = $vehicle->model->isn;//"РСА код марки(модели) (Тип: Целое число. Обязательный параметр)"
        $response->RSACarModificationCode = $vehicle->model_classification_code;


        return Car::response($response);






    }

    public static function getCarKASKO($contract)
    {
        $response = new \stdClass();
        $vehicle = $contract->object_insurer_auto;



        $response->ALARMONBOARD = ($vehicle->anti_theft_system_id > 0) ? "Y":"N";
        $response->BODYID = $vehicle->body_number;
        $response->CARUSEISN = $vehicle->purpose->isn;
        $response->CHASSISID = $vehicle->body_chassis;
        $response->COLORISN = ($vehicle->color)?$vehicle->color->isn : '';
        $response->COUNTRYISN = ($vehicle->country)?$vehicle->country->isn : '';
        $response->FOREIGN = '';
        $response->MODELISN = $vehicle->model->isn;
        $response->MODIFICATION = '';
        $response->MULTIDRIVE = ($contract->data->is_multidriver == 1) ? "Y":"N";
        $response->NUMKEY = $vehicle->count_key;
        $response->OWNERISN = VernaSubjects::getKIASGeneralId($contract->owner->general);
        $response->POWER = $vehicle->power;
        $response->PROBEG = $vehicle->mileage;
        $response->PTSCLASSISN = $vehicle->doc_type;
        $response->PTSDATE = setDateTimeFormatRu($vehicle->docdate, 1);
        $response->PTSNO = $vehicle->docnumber;
        $response->PTSSER = $vehicle->docserie;
        $response->REGNO = $vehicle->reg_number;

        $response->RELEASEDATE = "01.01.{$vehicle->car_year}";
        $response->RELEASEYEAR = $vehicle->car_year;
        $response->RIGHTWHEEL = '';
        $response->RSACarModificationCode = $vehicle->model_classification_code;
        $response->USESPECIALSIGNAL = '';
        $response->VIN = $vehicle->vin;
        $response->VOLUME = $vehicle->volume;

        /*

  {
    "ALARMONBOARD": "Признак наличия штатной сигнализации (Y\\N) (Тип: Строка. Необязательный параметр)",
    "BODYID": "Номер кузова (Обязательное поле если незаполнены шасси и VIN) (Тип: Строка. Необязательный параметр)",
    "CARUSEISN": "Цель использования, справочник (cAimCarUse) (Тип: Строка. Необязательный параметр)",

        "CHASSISID": "Номер шасси (Обязательное поле если незаполнены VIN и номер кузова) (Тип: Строка. Необязательный параметр)",
    "COLORISN": "Цвет ТС, справочник (ucColorAuto) (Тип: Строка. Необязательный параметр)",

        "COUNTRYISN": "ISN страны регистрации ТС (Тип: Строка. Необязательный параметр)",
    "FOREIGN": "Признак иномарки (Y\\N) (Тип: Строка. Необязательный параметр)",

        "MODELISN": "ISN модели (справочник марки\\модели КИАС) (Тип: Строка. Обязательный параметр)",
    "MODIFICATION": "Модификация ТС, текстом (Тип: Строка. Необязательный параметр)",

    "MULTIDRIVE": "Признак неограниченного числа ЛДУ (Y\\N) (Тип: Строка. Необязательный параметр)",
    "NUMKEY": "Кол-во ключей (шт.) (Тип: Строка. Необязательный параметр)",
    "OWNERISN": "ISN контрагента - владельца ТС (Тип: Строка. Обязательный параметр)",
    "POWER": "Мощность ТС (л.с.) (Тип: Строка. Необязательный параметр)",
    "PROBEG": "Пробег ТС (км.) (Тип: Строка. Необязательный параметр)",
    "PTSCLASSISN": "ISN типа документа на ТС, справочник (cCarDocPasportTS) (Тип: Строка. Необязательный параметр)",
    "PTSDATE": "Дата выдачи документа на ТС (Тип: Строка. Необязательный параметр)",
    "PTSNO": "Номер документа на ТС (Тип: Строка. Необязательный параметр)",
    "PTSSER": "Серия документа на ТС (Тип: Строка. Необязательный параметр)",
    "REGNO": "Гос. рег. знак (Тип: Строка. Необязательный параметр)",


        "RELEASEDATE": "Дата выпуска ТС (Тип: Строка. Необязательный параметр)",
    "RELEASEYEAR": "Год выпуска ТС (Тип: Строка. Обязательный параметр)",
    "RIGHTWHEEL": "Признак правого руля (Y\\N) (Тип: Строка. Необязательный параметр)",
    "TDCODE": "Идентификатор ТС по справочнику Трансдекра (Тип: Строка. Обязательный параметр)",
    "USESPECIALSIGNAL": "Признак наличия спецсигнала (Y\\N) (Тип: Строка. Необязательный параметр)",
    "USETRAILER": "Признак наличия прицепа (Y\\N) (Тип: Строка. Необязательный параметр)",
    "VIN": "VIN (Обязательное поле если незаполнены шасси и номер кузова) (Тип: Строка. Необязательный параметр)",
    "VOLUME": "Объём двигателя (см3.) (Тип: Строка. Необязательный параметр)"
  },

         */

        return $response;

    }



    public static function response($result = '', $state = true)
    {
        return (object)['state' => $state, 'result' => $result];
    }


}