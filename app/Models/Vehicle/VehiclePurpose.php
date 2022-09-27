<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Model;

class VehiclePurpose extends Model{

    protected $table = 'vehicle_purpose';

    protected $guarded = ['id'];

    public $timestamps = false;


    const PURPOSE = [
        1 => 'Личная',
        2 => 'Учебная езда',
        3 => 'Такси',
        4 => 'Дорожные и специальные ТС',
        5 => 'Экстренные и коммунальные службы',
        6 => 'Перевозка опасных и легко воспламеняющихся грузов',
        7 => 'Регулярные пассажирские перевозки/перевозки пассажиров по заказам',
        8 => 'Прокат/Краткосрочная аренда'
    ];



}
