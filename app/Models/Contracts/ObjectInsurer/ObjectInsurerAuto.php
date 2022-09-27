<?php

namespace App\Models\Contracts\ObjectInsurer;

use App\Models\Settings\Country;
use App\Models\Vehicle\VehicleAntiTheftSystem;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleColor;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleMarksKasko;
use App\Models\Vehicle\VehicleModels;
use App\Models\Vehicle\VehicleModelsClassificationKasko;
use App\Models\Vehicle\VehicleModelsKasko;
use App\Models\Vehicle\VehiclePurpose;
use Illuminate\Database\Eloquent\Model;


class ObjectInsurerAuto extends Model
{

    protected $table = 'object_insurer_auto';

    protected $guarded = ['id'];

    public $timestamps = false;


    const DOC_TYPE_TS = [
        '220220' => 'Свидетельство о регистрации ТС',
        '220219' => 'Паспорт ТС',
        '220224' => 'Паспорт ТС иностранного государства',
        '220222' => 'Паспорт самоходной машины',
        '220227' => 'Паспорт самоходной машины иностранного государства',
        '226668' => 'Прочий документ на ТС',
        '220225' => 'Свидетельство о регистрации ТС иностранного государства',
        '225346' => 'Электронный паспорт ТС',
    ];


    const ENGINE_TYPE_TS = [
        '1' => 'Бензин',
        '2' => 'Дизель',
        '3' => 'Электро',
        '4' => 'Гибрид',
    ];

    const SOURCE_ACQUISITION_TS = [
        '1' => 'Не новое ТС (Куплен у физ./юр. Лица)',
        '2' => 'Новое ТС (Находится в салоне)',
    ];

    const NUMBER_OWNERS = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
    ];

    const TRANSMISSION_TYPE = [
        1 => 'Автоматизированная',
        2 => 'Механическая',
        3 => 'Роботизированная',
    ];

    const IS_CREDIT = [
        0 => 'Нет',
        1 => 'Кредит',
        2 => 'Лизинг',
    ];


    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function mark()
    {
        return $this->hasOne(VehicleMarks::class, 'id', 'mark_id');
    }

    public function model()
    {
        return $this->hasOne(VehicleModels::class, 'id', 'model_id');
    }

    public function mark_kasko()
    {
        return $this->hasOne(VehicleMarksKasko::class, 'code', 'mark_code');
    }

    public function model_kasko()
    {
        return VehicleModelsKasko::where('CODE', $this->model_code)->where('PARENTCODE',$this->mark_code)->get()->first();
    }

    public function category_auto()
    {
        return $this->hasOne(VehicleCategories::class, 'id', 'ts_category');
    }

    public function purpose()
    {
        return $this->hasOne(VehiclePurpose::class, 'id', 'purpose_id');
    }

    public function color()
    {
        return $this->hasOne(VehicleColor::class, 'id', 'color_id');
    }

    public function anti_theft_system()
    {
        return $this->hasOne(VehicleAntiTheftSystem::class, 'id', 'anti_theft_system_id');
    }

    public function models_classification()
    {
        return $this->hasOne(VehicleModelsClassificationKasko::class, 'CODE', 'model_classification_code');
    }

}
