<?php

namespace App\Services\Integration;

use App\Services\Integration\VernaControllers\VernaDirectories;
use Mockery\Exception;

class VernaAPI
{
    const TYPE = [
        'categories' => 'Категорий и целей использования тс',
        'getrsacarmodels' => 'Справочник марок и моделей автомобилей',
        'ucCatalogDocumentClasses' => 'типы документов контрагента',
        'ucOrgForm' => 'Организационно-правовая форма',
        'ucSubjEconomic' => 'Отрасль (Сектор экономики) ',
        'ucSubjOKVD' => 'Справочник ОКВЭД',
        'getSalesPoints' => 'Точки продаж',
        'ucColorAuto' => 'КАСКО цвет Авто',
        'updateTariffDefault' => 'КАСКО тарифы',
        'ucCatalogCountryes' => 'Страны',
        'getCompanyStructure' => 'Загрузка штатных сотрудников',
        'getCompanyStructureUpdate' => 'Обновления и связи сотрудников',
    ];


    public function __construct()
    {

    }

    public static function updateDirectories($type)
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "Метод не найден";

        $dir = new VernaDirectories();
        $result = false;

        if($type == 'categories') $result = $dir->categories();
        if($type == 'getrsacarmodels') $result = $dir->getrsacarmodels();
        if($type == 'ucCatalogDocumentClasses') $result = $dir->ucCatalogDocumentClasses();
        if($type == 'ucOrgForm') $result = $dir->ucOrgForm();
        if($type == 'countries') $result = $dir->countries();
        if($type == 'getSalesPoints') $result = $dir->getSalesPoints();
        if($type == 'getTransdekraManufacturers') $result = $dir->getTransdekraManufacturers();
        if($type == 'updateTariffDefault') $result = $dir->updateTariffDefault();
        if($type == 'ucColorAuto') $result = $dir->ucColorAuto();
        if($type == 'ucCatalogCountryes') $result = $dir->ucCatalogCountryes();
        if($type == 'getCompanyStructure') $result = $dir->getCompanyStructure();
        if($type == 'getCompanyStructureUpdate') $result = $dir->getCompanyStructureUpdate();



        if($result == true){
            $res->state = 0;
            $res->msg = "Данные обновлены!";
        }

        return $res;
    }



}