<?php

namespace App\Processes\Operations\Contracts\Settings\Kasco;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto;
use App\Models\Directories\Products\Data\Kasko\Standard;
use App\Models\Directories\Products\Data\VZR;
use App\Models\Directories\Products\ProductsAgentsSpecialSsettings;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Models\Settings\Country;
use App\Models\Settings\CurrencyValue;
use App\Models\Settings\InstallmentAlgorithmsPayment;
use App\Models\Vehicle\VehicleAntiTheftSystem;
use App\Models\Vehicle\VehicleColor;
use App\Models\Vehicle\VehiclePurpose;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use Illuminate\Support\Str;

class Coefficients{

    const CATEGORY = [
        'terms' => 'Условия страхования',
        'auto' => 'Автомобиль',
        'drivers' => 'Водители',
        'insurer' => 'Страхователь',
        'owner' => 'Собственник',
        'beneficiar' => 'Выгодоприобретатель',
        'related' => 'Смежные',
    ];

    const CATEGORY_A = [
        'terms' => 'Условия страхования',
        'insurer' => 'Страхователь',
    ];


    public static function getCoefficientsAllToCategoryDefault($category){

        $result = [];

        /*
            'related' => 'Смежные',
         */

        //Смежные
        if($category == 'related'){
            $result = [

                'franchise' => [
                    'title' => 'Франшиза',
                    'is_not_select' => 1,
                    'tarrif_name' => 'фран',
                    'field' => 'franchise',
                    'control' => [
                        'type' => 'select',
                        'value' => Standard::FRANCHISE,
                    ],
                ],

                'insurance_amount' => [
                    'title' => 'Страховая сумма',
                    'is_not_select' => 1,
                    'tarrif_name' => 'cc',
                    'field' => 'insurance_amount',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],

                'car_year' => [
                    'title' => 'Год выпуска',
                    'is_not_select' => 1,
                    'tarrif_name' => 'тсг',
                    'field' => 'car_year',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],

                'franchise_amount_year' => [
                    'title' => 'Франшиза',
                    'is_adjacent' => '1',
                    'tarrif_name' => 'фран',
                    'field' => 'franchise',
                    'control' => [
                        'data' => [
                            'franchise',
                            'insurance_amount',
                            'car_year',
                        ]
                    ],
                ],
                'is_risky_anti_theft_system' => [
                    'title' => 'Рисковое ТС: Противоугонное устройство',
                    'tarrif_name' => 'ртссиг',
                    'field' => 'is_risky_anti_theft_system_id',
                    'is_risky' => 1,
                    'control' => [
                        'type' => 'select',
                        'value' => VehicleAntiTheftSystem::all()->pluck('title', 'id')->prepend('Не выбрано', 0)->toArray(),
                    ],
                ],

                'is_risky_autostart' => [
                    'title' => 'Рисковое ТС: Автозапуск',
                    'tarrif_name' => 'тсаз',
                    'field' => 'is_risky_is_autostart',
                    'is_risky' => 1,
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],
            ];
        }


        //Условия страхования
        if($category == 'terms'){
            $result = [
                'coatings_risks' => [
                    'title' => 'Покрытия и риски',
                    'tarrif_name' => 'пр',
                    'field' => 'coatings_risks_id',
                    'control' => [
                        'type' => 'select',
                        'value' => Standard::COATINGS_RISKS,
                    ],
                ],
                'territory' => [
                    'title' => 'Территория страхования',
                    'tarrif_name' => 'террст',
                    'field' => 'territory_id',
                    'control' => [
                        'type' => 'select',
                        'value' => Standard::TERRIRORY,
                    ],
                ],
                'payment_not_certificates' => [
                    'title' => 'Выплата без справок',
                    'tarrif_name' => 'террст',
                    'field' => 'payment_not_certificates_id',
                    'control' => [
                        'type' => 'select',
                        'value' => Standard::PAYMENT_NOT_CERTIFICATES,
                    ],
                ],
                'tenure' => [
                    'title' => 'Срок владения',
                    'tarrif_name' => 'свл',
                    'field' => 'tenure_id',
                    'control' => [
                        'type' => 'select',
                        'value' => Standard::TENURE,
                    ],
                ],
                'repair_options' => [
                    'title' => 'Варианты ремонта',
                    'tarrif_name' => 'рем',
                    'field' => 'repair_options_id',
                    'control' => [
                        'type' => 'select',
                        'value' => Standard::REPAIR_OPTIONS,
                    ],
                ],
                'prolongation' => [
                    'title' => 'Тип договора',
                    'tarrif_name' => 'тд',
                    'field' => 'is_prolongation',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Первичный", 1=>'Пролонгация'],
                    ],
                ],
                'transition' => [
                    'title' => 'Переход из другой компании',
                    'tarrif_name' => 'пск',
                    'field' => 'is_transition',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],
                'installment_algorithms' => [
                    'title' => 'Алгоритм рассрочки',
                    'tarrif_name' => 'ап',
                    'field' => 'installment_algorithms_id',
                    'control' => [
                        'type' => 'select',
                        'value' => InstallmentAlgorithmsPayment::orderBy('title')->get()->pluck('title', 'id')->toArray(),
                    ],
                ],
            ];
        }

        //Водители
        if($category == 'drivers'){
            $result = [
                'multidriver' => [
                    'title' => 'Мультидрайв',
                    'tarrif_name' => 'вм',
                    'field' => 'is_multidriver',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],
                'birthyear' => [
                    'title' => 'Возраст',
                    'tarrif_name' => 'вв',
                    'field' => 'birthyear',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'expyear' => [
                    'title' => 'Стаж',
                    'tarrif_name' => 'вс',
                    'field' => 'expyear',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'only_spouses' => [
                    'title' => 'Допущены только супруги',
                    'tarrif_name' => 'всуп',
                    'field' => 'is_only_spouses',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],
                'birthyear_expyear' => [
                    'title' => 'Возраст и стаж',
                    'tarrif_name' => 'вв и вс',
                    'field' => 'birthyear_expyear',
                    'is_adjacent' => '1',
                    'control' => [
                        'data' => [
                            'birthyear',
                            'expyear',
                        ]
                    ],
                ],
            ];
        }

        //Контрагенты
        if($category == 'insurer' || $category == 'owner' || $category == 'beneficiar'){
            $result = [
                'type' => [
                    'title' => 'Тип',
                    'tarrif_name' => 'кт',
                    'field' => 'type',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"ФЛ", 3=>'ЮЛ']
                    ],
                ],
                'birthyear' => [
                    'title' => 'Возраст',
                    'tarrif_name' => 'квоз',
                    'field' => 'birthyear',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'sex' => [
                    'title' => 'Пол',
                    'tarrif_name' => 'кпол',
                    'field' => 'sex',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"муж.", 1=>'жен.']
                    ],
                ],
                'resident' => [
                    'title' => 'Резидент',
                    'tarrif_name' => 'крез',
                    'field' => 'is_resident',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],

                'citizenship' => [
                    'title' => 'Гражданство',
                    'tarrif_name' => 'кграж',
                    'field' => 'citizenship_id',
                    'control' => [
                        'type' => 'select',
                        'value' => Country::orderBy('title')->get()->pluck('title', 'id')->toArray(),
                    ],
                ],

            ];
        }

        //Автомобиль
        if($category == 'auto'){
            $result = [
                'car_year' => [
                    'title' => 'Лет авто',
                    'tarrif_name' => 'тсг',
                    'field' => 'car_year',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'car_price' => [
                    'title' => 'Стоимость автомобиля',
                    'tarrif_name' => 'тса',
                    'field' => 'car_price',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'mileage' => [
                    'title' => 'Пробег',
                    'tarrif_name' => 'тсп',
                    'field' => 'mileage',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'purpose' => [
                    'title' => 'Цель использования',
                    'tarrif_name' => 'тсци',
                    'field' => 'purpose_id',
                    'control' => [
                        'type' => 'select',
                        'value' => VehiclePurpose::PURPOSE,
                    ],
                ],
                'number_owners' => [
                    'title' => 'Кол-во собственников',
                    'tarrif_name' => 'тссоб',
                    'field' => 'number_owners',
                    'control' => [
                        'type' => 'select',
                        'value' => ObjectInsurerAuto::NUMBER_OWNERS,
                    ],
                ],
                'engine_type' =>[
                    'title' => 'Тип двигателя',
                    'tarrif_name' => 'тсд',
                    'field' => 'engine_type_id',
                    'control' => [
                        'type' => 'select',
                        'value' => ObjectInsurerAuto::ENGINE_TYPE_TS,
                    ],
                ],
                'transmission_type' => [
                    'title' => 'Коробка передач',
                    'tarrif_name' => 'тскп',
                    'field' => 'transmission_type',
                    'control' => [
                        'type' => 'select',
                        'value' => ObjectInsurerAuto::TRANSMISSION_TYPE,
                    ],
                ],
                'source_acquisition' =>[
                    'title' => 'Источник приобретения',
                    'tarrif_name' => 'тсип',
                    'field' => 'source_acquisition_id',
                    'control' => [
                        'type' => 'select',
                        'value' => ObjectInsurerAuto::SOURCE_ACQUISITION_TS,
                    ],
                ],
                'country' => [
                    'title' => 'Страна регистрации',
                    'tarrif_name' => 'тсстр',
                    'field' => 'country_id',
                    'control' => [
                        'type' => 'select',
                        'value' => Country::orderBy('title')->get()->pluck('title', 'id')->toArray(),
                    ],
                ],
                'count_key' => [
                    'title' => 'Ключей (шт.)',
                    'tarrif_name' => 'тск',
                    'field' => 'count_key',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'volume' => [
                    'title' => 'Объём (см3)',
                    'tarrif_name' => 'тсоб',
                    'field' => 'volume',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'power' => [
                    'title' => 'Мощность (л.с.)',
                    'tarrif_name' => 'тсмо',
                    'field' => 'power',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'weight' => [
                    'title' => 'Масса',
                    'tarrif_name' => 'тсмас',
                    'field' => 'weight',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'capacity' => [
                    'title' => 'Грузоподъемность',
                    'tarrif_name' => 'тсгруз',
                    'field' => 'capacity',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'passengers_count' => [
                    'title' => 'Кол-во мест',
                    'tarrif_name' => 'тсмест',
                    'field' => 'passengers_count',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'anti_theft_system' => [
                    'title' => 'Противоугонное устройство',
                    'tarrif_name' => 'тссиг',
                    'field' => 'anti_theft_system_id',
                    'control' => [
                        'type' => 'select',
                        'value' => VehicleAntiTheftSystem::all()->pluck('title', 'id')->prepend('Не выбрано', 0)->toArray(),
                    ],
                ],
                'color' => [
                    'title' => 'Цвет',
                    'tarrif_name' => 'тсц',
                    'field' => 'color_id',
                    'control' => [
                        'type' => 'select',
                        'value' => VehicleColor::all()->pluck('title', 'isn')->prepend('Не выбрано', 0)->toArray(),
                    ],
                ],


                'trailer' => [
                    'title' => 'Автомобиль используется с прицепом',
                    'tarrif_name' => 'тсап',
                    'field' => 'is_trailer',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],

                'credit' => [
                    'title' => 'Автомобиль куплен в кредит',
                    'tarrif_name' => 'тсак',
                    'field' => 'is_credit',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],

                'autostart' => [
                    'title' => 'Автозапуск',
                    'tarrif_name' => 'тсаз',
                    'field' => 'is_autostart',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],

                'right_drive' => [
                    'title' => 'Праворульный автомобиль',
                    'tarrif_name' => 'тсап',
                    'field' => 'is_right_drive',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],

                'duplicate' => [
                    'title' => 'Менялись номера агрегатов/Дубликат ПТС',
                    'tarrif_name' => 'тсад',
                    'field' => 'is_duplicate',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],

            ];
        }


        return $result;

    }

    public static function getCoefficientsaAbitrationToCategoryDefault($category){

        $result = [];

        /*
            'related' => 'Смежные',
         */

        //Условия страхования
        if($category == 'terms'){
            $result = [

                'expyear' => [
                    'title' => 'Стаж (лет)',
                    'tarrif_name' => 'аул',
                    'field' => 'expyear',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],

                'complaints' => [
                    'title' => 'Кол-во жалоб',
                    'tarrif_name' => 'кж',
                    'field' => 'count_complaints',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],

                'warnings' => [
                    'title' => 'Кол-во предупреждений',
                    'tarrif_name' => 'кп',
                    'field' => 'count_warnings',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],

                'fines' => [
                    'title' => 'Кол-во штрафов',
                    'tarrif_name' => 'кш',
                    'field' => 'count_fines',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],

                'prolongation' => [
                    'title' => 'Тип договора',
                    'tarrif_name' => 'тд',
                    'field' => 'is_prolongation',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Первичный", 1=>'Пролонгация'],
                    ],
                ],
                'urgently' => [
                    'title' => 'Срочно',
                    'tarrif_name' => 'срочно',
                    'field' => 'is_urgently',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],
            ];
        }



        //Контрагенты
        if($category == 'insurer' || $category == 'owner' || $category == 'beneficiar'){
            $result = [
                'type' => [
                    'title' => 'Тип',
                    'tarrif_name' => 'кт',
                    'field' => 'type',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"ФЛ", 3=>'ЮЛ']
                    ],
                ],
                'birthyear' => [
                    'title' => 'Возраст',
                    'tarrif_name' => 'квоз',
                    'field' => 'birthyear',
                    'control' => [
                        'type' => 'range',
                        'to' => 'sum',
                        'from' => 'sum',
                    ],
                ],
                'sex' => [
                    'title' => 'Пол',
                    'tarrif_name' => 'кпол',
                    'field' => 'sex',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"муж.", 1=>'жен.']
                    ],
                ],
                'resident' => [
                    'title' => 'Резидент',
                    'tarrif_name' => 'крез',
                    'field' => 'is_resident',
                    'control' => [
                        'type' => 'select',
                        'value' => [0=>"Нет", 1=>'Да'],
                    ],
                ],

                'citizenship' => [
                    'title' => 'Гражданство',
                    'tarrif_name' => 'кграж',
                    'field' => 'citizenship_id',
                    'control' => [
                        'type' => 'select',
                        'value' => Country::orderBy('title')->get()->pluck('title', 'id')->toArray(),
                    ],
                ],

            ];
        }


        return $result;

    }

    public static function getCoefficientsGroupDefault($category, $group){
        return self::getCoefficientsAllToCategoryDefault($category)[$group];
    }

    public static function getCoefficientsAbitrationGroupDefault($category, $group){
        return self::getCoefficientsaAbitrationToCategoryDefault($category)[$group];
    }


    public static function getDataContract($contract){

        $data = null;
        $info = $contract->data;

        if($contract->product->slug == 'kasko'){
            if($contract->program->slug == 'calculator'){
                $driver_min_birthyear = null;
                $driver_min_expyear = null;

                if($info->is_multidriver == 0){
                    if($info->calc_data && strlen($info->calc_data) > 5){
                        $calc_data = \GuzzleHttp\json_decode($info->calc_data);
                        $driver_min_birthyear = (int)$calc_data->birthdate_year;
                        $driver_min_expyear = (int)$calc_data->exp_year;
                    }
                }


                $auto = $contract->object_insurer->data()->toArray();
                $auto['car_year'] = date('Y')-(int)$auto['car_year'];
                $auto['power'] = (int)$auto['power'];
                $auto['weight'] = (int)$auto['weight'];
                $auto['capacity'] = (int)$auto['capacity'];
                $auto['volume'] = (int)$auto['volume'];
                $auto['mileage'] = (int)$auto['mileage'];

                $data = [
                    'terms' => [
                        'coatings_risks_id' => $info->coatings_risks_id,
                        'territory_id' => $info->territory_id,
                        'tenure_id' => $info->tenure_id,
                        'repair_options_id' => $info->repair_options_id,
                        'is_prolongation' => $contract->is_prolongation,
                        'payment_not_certificates_id' => $info->payment_not_certificates_id,
                        'installment_algorithms_id' => $contract->installment_algorithms->id,
                        'transition' => $info->is_transition,
                        'insurance_amount' => getFloatFormat($contract->insurance_amount),
                    ],

                    'drivers' => [
                        'is_multidriver' => $info->is_multidriver,
                        'birthyear' => $driver_min_birthyear,
                        'expyear' => $driver_min_expyear,
                    ],

                    'auto' => $auto,
                    'related' => [
                        'insurance_amount' => getFloatFormat($contract->insurance_amount),
                        'car_year' => $auto['car_year'],
                        'franchise' => $info->franchise_id,
                        'is_risky_anti_theft_system' => $auto['anti_theft_system_id'],
                        'is_risky_autostart' => $auto['is_autostart'],
                    ],
                ];




            }else{
                $driver_min_birthyear = null;
                $driver_min_expyear = null;
                if($info->is_multidriver == 0){
                    $driver_b = $contract->contracts_insurers()->orderBy('birthyear', 'asc')->get()->first();
                    if($driver_b){
                        $driver_min_birthyear = (int)$driver_b->birthyear;
                    }

                    $driver_e = $contract->contracts_insurers()->orderBy('expyear', 'asc')->get()->first();
                    if($driver_e){
                        $driver_min_expyear = (int)$driver_e->expyear;
                    }
                }

                if($info->is_multidriver == 1){
                    if($info->calc_data && strlen($info->calc_data) > 5){
                        $calc_data = \GuzzleHttp\json_decode($info->calc_data);
                        if(isset($calc_data->type_multidriver) && (int)$calc_data->type_multidriver == 0){
                            $driver_min_birthyear = (int)$calc_data->birthdate_year;
                            $driver_min_expyear = (int)$calc_data->exp_year;
                            //$info->is_multidriver = 0;
                        }
                    }
                }


                $auto = $contract->object_insurer->data()->toArray();
                $auto['car_year'] = date('Y')-(int)$auto['car_year'];
                $auto['power'] = (int)$auto['power'];
                $auto['weight'] = (int)$auto['weight'];
                $auto['capacity'] = (int)$auto['capacity'];
                $auto['volume'] = (int)$auto['volume'];
                $auto['mileage'] = (int)$auto['mileage'];

                $data = [
                    'terms' => [
                        'coatings_risks_id' => $info->coatings_risks_id,
                        'territory_id' => $info->territory_id,
                        'tenure_id' => $info->tenure_id,
                        'repair_options_id' => $info->repair_options_id,
                        'is_prolongation' => $contract->is_prolongation,
                        'payment_not_certificates_id' => $info->payment_not_certificates_id,
                        'installment_algorithms_id' => $contract->installment_algorithms->id,
                        'transition' => $info->is_transition,
                        'insurance_amount' => getFloatFormat($contract->insurance_amount),
                    ],

                    'drivers' => [
                        'is_multidriver' => $info->is_multidriver,
                        'birthyear' => $driver_min_birthyear,
                        'expyear' => $driver_min_expyear,
                    ],

                    'insurer' => self::getDataContractSubjects($contract->insurer),
                    'owner' => self::getDataContractSubjects($contract->owner),
                    'beneficiar' => self::getDataContractSubjects($contract->beneficiar),

                    'auto' => $auto,
                    'related' => [
                        'insurance_amount' => getFloatFormat($contract->insurance_amount),
                        'car_year' => $auto['car_year'],
                        'franchise' => $info->franchise_id,
                        'is_risky_anti_theft_system' => $auto['anti_theft_system_id'],
                        'is_risky_autostart' => $auto['is_autostart'],
                    ],

                ];
            }
        }elseif($contract->product->slug == 'arbitration'){


            $data = [
                'terms' => [
                    'experience' => $info->experience,
                    'count_complaints' => $info->count_complaints,
                    'count_warnings' => $info->count_warnings,
                    'count_fines' => $info->count_fines,
                    'is_urgently' => $info->is_urgently,

                ],


                'insurer' => self::getDataContractSubjects($contract->insurer),

            ];
        }


        return $data;
    }


    public static function getDataContractSubjects($subject){

        $birthyear = null;
        $sex = null;
        if($subject->type == 0){

            $data = $subject->data();
            $birthyear = date('Y')-date('Y', strtotime($data->birthdate));
            $sex = $data->sex;
        }


        $subject = [
            'type' => $subject->type,
            'birthyear' => $birthyear,
            'sex' => $sex,
            'resident' => $subject->is_resident,
            'citizenship' => $subject->citizenship_id
        ];

        return $subject;

    }


    public static function getCoefficientsValue($data, $coefficients){
        $result = [];

        //dump($data);


        foreach ($coefficients as $base_coeff){
            $is_set = false;

            if(isset($data[$base_coeff->category])){

                if((int)$base_coeff->is_adjacent == 1){

                    if(strlen($base_coeff->json) > 0){

                        $_def_coefficient = self::getCoefficientsAllToCategoryDefault($base_coeff->category);
                        $_def_group = $_def_coefficient[$base_coeff->group];


                        $base_json = \GuzzleHttp\json_decode($base_coeff->json, true);
                        foreach ($_def_group['control']['data'] as $contrer){

                            if(isset($data[$base_coeff->category][$contrer])){
                                $base_json[$contrer]['val'] = $data[$base_coeff->category][$contrer];
                            }
                        }


                        $is_set = self::checkAdjacent($base_json);
                        //if($is_set == true) dump($base_json);

                    }
                }elseif(isset($data[$base_coeff->category][$base_coeff->field]))
                {


                    $val = $data[$base_coeff->category][$base_coeff->field];

                    if($base_coeff->type == 'select'){
                        if($val == $base_coeff->value || $base_coeff->value = 0){
                            $is_set = true;
                        }
                    }
                    if($base_coeff->type == 'range'){

                        if(strlen($val) > 0){
                            $val = getFloatFormat($val);
                        }else{
                            $val = null;
                        }


                        if(strlen($base_coeff->value_to) > 0){
                            $value_to = getFloatFormat($base_coeff->value_to);
                        }else{
                            $value_to = null;
                        }

                        if(strlen($base_coeff->value_from) > 0){
                            $value_from = getFloatFormat($base_coeff->value_from);
                        }else{
                            $value_from = null;
                        }


                        if($val){
                            if($val && $value_to && $value_from){
                                if($val >= $value_to && $val <= $value_from){
                                    $is_set = true;
                                }
                            }

                            if($val && $value_to && !$value_from){
                                if($val >= $value_to){
                                    $is_set = true;
                                }
                            }

                            if($val && !$value_to && $value_from){
                                if($val <= $value_from){
                                    $is_set = true;
                                }
                            }
                        }

                    }





                }
            }

            if($is_set == true){
                $result[$base_coeff->group] = [
                    'title' => $base_coeff->tarrif_name,
                    'tariff' => getFloatFormat($base_coeff->tarife)
                ];
            }
        }

        return $result;
    }


    public static function checkAdjacent($base_json)
    {
        $is_set = false;
        $count = 0;



        foreach ($base_json as $json){
            if(isset($json['val'])){

                $val = null;
                if(strlen($json['val']) > 0) $val = getFloatFormat($json['val']);

                if(isset($json['value_to'])){

                    $value_to = null;
                    $value_from = null;

                    if(strlen($json['value_to']) > 0) $value_to = getFloatFormat($json['value_to']);
                    if(strlen($json['value_from']) > 0) $value_from = getFloatFormat($json['value_from']);


                    if($val){
                        if($val && $value_to && $value_from){

                            if($val >= $value_to && $val <= $value_from) $count +=1;

                        }elseif($val && $value_to && !$value_from){

                            if($val >= $value_to) $count +=1;

                        }elseif($val && !$value_to && $value_from){

                            if($val <= $value_from) $count +=1;

                        }
                    }
                }

                if(isset($json['value'])){

                    $value = null;
                    if(strlen($json['value']) > 0) $value = $json['value'];
                    if($value && $val == $value || (int)$value = 0){
                        $count +=1;
                    }

                }

            }

        }

        if($count == count($base_json)){
            $is_set = true;
        }



        return $is_set;
    }


}