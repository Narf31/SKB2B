<?php

namespace App\Services\Integration;

use App\Services\Integration\Control\VtigerCRMContracts;
use App\Services\Integration\Control\VtigerCRMGeneralConnections;
use App\Services\Integration\Control\VtigerCRMGeneralFL;
use App\Services\Integration\Control\VtigerCRMGeneralUL;
use App\Services\Integration\Control\VtigerCRMUsers;
use Mockery\Exception;

class VtigerCRM
{
    const TYPE = [
        'users' => 'Пользователи',
        'general_fl' => 'Контрагенты ФЛ',
        'general_ul' => 'Контрагенты ЮЛ',
        'general_connections' => 'Контрагенты связи',
        'contracts' => 'Договоры',
    ];


    public function __construct()
    {

    }

    public static function getDataResult($type, $start, $counts, $count_all, $request, $state = 'info')
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "Метод не найден";


        $_class = null;
        switch ($type) {
            case 'users':
                $_class = new VtigerCRMUsers();
                break;
            case 'general_fl':
                $_class = new VtigerCRMGeneralFL();
                break;
            case 'general_ul':
                $_class = new VtigerCRMGeneralUL();
                break;
            case 'general_connections':
                $_class = new VtigerCRMGeneralConnections();
                break;
            case 'contracts':
                $_class = new VtigerCRMContracts();

                break;
        }


        if($_class){
            if($state == 'info'){
                $res = $_class->getDataInfo($start, $request);
            }
            if($state == 'updata'){
                $res = $_class->updateDataInfo($start, $counts, $request, $count_all);
            }
            if($state == 'connection'){
                $res = $_class->connectionDataInfo();
            }

        }


        return $res;
    }

    public static function clean()
    {

    }

}