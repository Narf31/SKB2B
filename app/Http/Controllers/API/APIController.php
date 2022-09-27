<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResultGenerate;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto;
use App\Models\Contracts\Payments;
use App\Models\Contracts\Subjects;
use App\Models\Directories\InstallmentAlgorithms;
use App\Models\Directories\Products\Data\Kasko\Standard;
use App\Models\User;
use App\Models\Vehicle\VehiclePurpose;
use Illuminate\Http\Request;

class APIController
{

    private $user;
    /**
     * @var mixed
     */
    private $token;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {

        #todo раскоментировать

//        $this->token = $request->access_token;
//        if (empty($this->token)) {
//            exit(ResultGenerate::Error('access_token empty'));
//        }
//
//        $this->user = User::where('apiToken', $this->token)
//            ->where('apiTokenTime', '>=', date("Y-m-d H:i:s", time() - 60 * 30))
//            ->first();
//        if (empty($this->user)) {
//            exit(ResultGenerate::Error('invalid access_token'));
//        }
//
//        $this->user->apiTokenTime = date("Y-m-d H:i:s", time());
//        $this->user->save();

    }

    public function GetPolices1C(Request $request)
    {
        $dateFrom = !empty($request->date_from) ? $request->date_from : null;
        $dateTo = !empty($request->date_to) ? $request->date_to : null;

        $contracts = Contracts::query();
        if (!empty($dateFrom)) {
            $contracts->where('created_at', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $contracts->where('created_at', '<=', $dateTo);
        }
        $contracts = $contracts->get();

        $result = $this->DateDecoration($contracts);

        $result = (object)[
            'title' => 'Каско',
            'date' => date("Y-m-d H:i:s", time()),
            'status_text' => 'Оформлен',
            'payment_status' => 'Оплачен',
            'number' => 'УУУ 1234567890',
        ];

        return ResultGenerate::Success(false, $result);
    }

    private function DateDecoration($contracts)
    {
        $res = [];
        foreach ($contracts as $contract) {

            $contractData = $contract->data;
            $payment = $contract->payments->last();
            $product = $contract->product;
            $program = $contract->programm;
            $owner = $contract->owner;
            $insurer = $contract->insurer;
            $beneficiar = $contract->beneficiar;
            $ownerRegistrationAddress = $contract->owner->general->getAddress(1);
            $insurerRegistrationAddress = $contract->insurer->general->getAddress(1);
            $objectInsurer = $contract->object_insurer_auto;

            dd($contract/*, $beneficiar->general->getMainDocumentsType()*/, $insurer->get_info()->doc_type);

            $id = [
                "status" => 110,
                "status_text" => Contracts::STATYS[$contract->statys_id],
                "number" => $contract->bso_title,
                "payment_status" => $payment->statys_id,
                "payment_status_text" => Payments::STATUS[$payment->statys_id],
                "sum" => $contract->payment_total,
                "date_create" => $contract->created_at,
                "RSA_TYPE" => 1,
                "risks" => [
                    Standard::COATINGS_RISKS[$contractData->coatings_risks_id]['risk'] => [
                        "insurance_amount" => $contract->insurance_amount,
                        "insurance_premium" => $contract->payment_total
                    ]
                ]
            ];

            $data = [
                "insurantRegion" => $insurerRegistrationAddress->region,
                "insurantRegion_kladr_code" => mb_substr($insurerRegistrationAddress->kladr, 0, 12),
                "insurantRegion_kladr_okato" => $insurerRegistrationAddress->okato,
                "insurantRegion_kladr_postal_code" => $insurerRegistrationAddress->zip,
                "isProlongation" => $contract->is_prolongation ? 'true' : 'false',
                "isTelematics" => "",
                "insurantType" => Subjects::TYPE[$owner->general->type_id],
                "isSmallChildren" => "",
                "limitIndemnity" => "Неагрегатная страховая сумма",
                "carMark" => $objectInsurer->mark->title,
                "carModel" => $objectInsurer->model->title,
                "carYear" => $objectInsurer->car_year,
                "carModification" => "",
                "carColor" => "",
                "carCreditType" => $contractData->is_auto_credit,
                "carIsNew" => ObjectInsurerAuto::SOURCE_ACQUISITION_TS[$objectInsurer->source_acquisition_id],
                "carTypeBody" => ($objectInsurer->models_classification) ? $objectInsurer->models_classification->BodyName : '',
                "carTypeWheel" => "Левый",  #todo ?? а вдруг правый
                "carCost" => $contract->insurance_amount,
                "carCostMin" => "0",
                "carCostMax" => "0",
                "carIsAllKeys" => $objectInsurer->count_key,
                "isOnlySpouses" => "",
                "age" => "31",  #todo водители?
                "experience" => "13",  #todo водители?
                "isAgreePerson" => "1",     #todo ??
                "isAgreeBenificiar" => "1",     #todo ??
                "isAgreeCar" => "1",     #todo ??
                "franchiseType" => Standard::FRANCHISE[$contractData->franchise_id],
                "subProgramsInsurance" => $product->title,
                "isAntitheftSystem" => "",
                "ptsDate" => $objectInsurer->docdate,
                "ptsCountry" => "РФ",
                "ptsRegion" => "Москва",
                "ptsRegion_kladr_code" => mb_substr($insurerRegistrationAddress->kladr, 0, 12),
                "ptsRegion_kladr_okato" => $insurerRegistrationAddress->okato,
                "ptsRegion_kladr_postal_code" => $insurerRegistrationAddress->zip,
                "carRegionRegistration" => $insurerRegistrationAddress->region,
                "carRegionRegistration_kladr_code" => mb_substr($insurerRegistrationAddress->kladr, 0, 12),
                "carRegionRegistration_kladr_okato" => $insurerRegistrationAddress->okato,
                "carRegionRegistration_kladr_postal_code" => $insurerRegistrationAddress->zip,
                "isHijackTheft" => "1",     #todo ??
                "isAccident" => "",     #todo ??
                "isAdditionalEquipment" => "",     #todo ??
                "isGap" => "",     #todo ??
                "isCivilLiability" => "",
                "insurancePeriod" => "12m",
                "paymentProcedure" => InstallmentAlgorithms::ALG_TYPE[$contract->installment_algorithms_id],
                "isAutoplay" => $objectInsurer->is_autostart,
                "repairType" => Standard::REPAIR_OPTIONS[$contractData->repair_options_id],
                "purposeOfUse" => VehiclePurpose::PURPOSE[$objectInsurer->purpose_id],
                "isAdditionalProgramm" => "",     #todo ??
                "emergencyCommissioner" => $contractData->is_emergency_commissioner,
                "reportsCollect" => $contractData->is_collection_certificates,
                "evacuationInTheRoadAccident" => $contractData->is_evacuation,
                "startInsurance" => $contract->begin_date,
                "endInsurance" => $contract->end_date,
                "paymentType" => Payments::PAYMENT_TYPE[$payment->type_id],
                "insurantLastName" => explode(' ', $insurer->title)[0],
                "insurantFirstName" => explode(' ', $insurer->title)[1],
                "insurantMiddleName" => !empty(explode(' ', $insurer->title)[2]) ? explode(' ', $insurer->title)[2] : '',
                "insurantBirthDate" => $insurer->get_info()->birthdate,
                "insurantBirthPlace" => $insurer->get_info()->address_born,
                "insurantPhone" => $insurer->phone,
                "insurantEmail" => $insurer->email,
                "insurantCitizenship" => $insurer->general->citizenship->title_ru,
                "insurantDocType" => "Паспорт РФ",
                "insurantDocSeries" => "45 15",
                "insurantDocNum" => "083284",
                "insurantDocDate" => "22.06.2015",
                "insurantDocPlace" => "Отделом УФМС России по гор. Москве по району Митино",
                "insurantCity" => "Москва",
                "insurantCity_kladr_code" => "77000000000",
                "insurantCity_kladr_okato" => "45000000000",
                "insurantCity_kladr_postal_code" => "",
                "insurantStreet" => "Пятницкое",
                "insurantStreet_kladr_code" => "770000000002427",
                "insurantStreet_kladr_okato" => "45283559000",
                "insurantStreet_kladr_postal_code" => "0",
                "insurantHouse" => "38",
                "insurantHousing" => "1",
                "insurantFlat" => "211",
                "insurantJureKpp" => "",
                "insurantJureBik" => "",
                "insurantJureRs" => "",
                "insurantJurePhone" => "",
                "insurantJureEmail" => "",
                "insurantJureContactLastName" => "",
                "insurantJureContactFirstName" => "",
                "insurantJureContactMiddleName" => "",
                "insurantJureContactPosition" => "",
                "insurantJureContactBirthDate" => "",
                "insurantJureContactPhone" => "",
                "insurantJureContactEmail" => "",
                "insurantJureCountry" => "",
                "insurantJureCountry_kladr_code" => "",
                "insurantJureCountry_kladr_okato" => "",
                "insurantJureCountry_kladr_postal_code" => "",
                "insurantJureCity_kladr_code" => "",
                "insurantJureCity_kladr_okato" => "",
                "insurantJureCity_kladr_postal_code" => "",
                "insurantJureStreet" => "",
                "insurantJureStreet_kladr_code" => "",
                "insurantJureStreet_kladr_okato" => "",
                "insurantJureStreet_kladr_postal_code" => "",
                "insurantJureHousing" => "",
                "insurantJureOffice" => "",
                "insurantJureFactCountry" => "",
                "insurantJureFactCountry_kladr_code" => "",
                "insurantJureFactCountry_kladr_okato" => "",
                "insurantJureFactCountry_kladr_postal_code" => "",
                "insurantJureFactCity_kladr_code" => "",
                "insurantJureFactCity_kladr_okato" => "",
                "insurantJureFactCity_kladr_postal_code" => "",
                "insurantJureFactStreet" => "",
                "insurantJureFactStreet_kladr_code" => "",
                "insurantJureFactStreet_kladr_okato" => "",
                "insurantJureFactStreet_kladr_postal_code" => "",
                "insurantJureFactHousing" => "",
                "insurantJureFactOffice" => "",
                "beneficiary" => "Страхователь",
                "owner" => "Страхователь",
                "beneficiaryRegion" => "",
                "beneficiaryRegion_kladr_code" => "",
                "beneficiaryRegion_kladr_okato" => "",
                "beneficiaryRegion_kladr_postal_code" => "",
                "beneficiaryCity_kladr_code" => "",
                "beneficiaryCity_kladr_okato" => "",
                "beneficiaryCity_kladr_postal_code" => "",
                "beneficiaryStreet_kladr_code" => "",
                "beneficiaryStreet_kladr_okato" => "",
                "beneficiaryStreet_kladr_postal_code" => "",
                "beneficiaryFlat" => "",
                "beneficiaryJureRegion" => "",
                "beneficiaryJureRegion_kladr_code" => "",
                "beneficiaryJureRegion_kladr_okato" => "",
                "beneficiaryJureRegion_kladr_postal_code" => "",
                "beneficiaryJureCity_kladr_code" => "",
                "beneficiaryJureCity_kladr_okato" => "",
                "beneficiaryJureCity_kladr_postal_code" => "",
                "beneficiaryJureStreet_kladr_code" => "",
                "beneficiaryJureStreet_kladr_okato" => "",
                "beneficiaryJureStreet_kladr_postal_code" => "",
                "beneficiaryJureFactRegion" => "",
                "beneficiaryJureFactRegion_kladr_code" => "",
                "beneficiaryJureFactRegion_kladr_okato" => "",
                "beneficiaryJureFactRegion_kladr_postal_code" => "",
                "beneficiaryJureFactCity_kladr_code" => "",
                "beneficiaryJureFactCity_kladr_okato" => "",
                "beneficiaryJureFactCity_kladr_postal_code" => "",
                "beneficiaryJureFactStreet_kladr_code" => "",
                "beneficiaryJureFactStreet_kladr_okato" => "",
                "beneficiaryJureFactStreet_kladr_postal_code" => "",
                "ptsNumEl" => "",
                "carVin" => "WBAJR71040BJ16739",
                "carRegNum" => "С808ХВ777",
                "carPtsNum" => "77УО№817155",
                "carIsPtsOriginal" => "1",
                "psoCity" => "Москва",
                "psoСonfirmClient" => "Да",
                "discount" => "14",
                "kvDiscount" => "0",
                "isDamage" => "1",
                "productName" => "Каско",
                "psoNeedTypes" => "2",
                "additionalProgramm" => ""
            ];




        }


    }
}