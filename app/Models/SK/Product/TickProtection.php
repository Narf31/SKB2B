<?php

namespace App\Models\SK\Product;

use Illuminate\Database\Eloquent\Model;

class TickProtection extends Model
{
    protected $table = 'sk_product_tick_protection';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TEMPLATE_CATEGORY = 'system_policy_tick_protection';


    const PROGRAMS = [
        1 =>[
            "id" => "1",
            "title" => "Программа 1",
            "price" => 200,
            "description" => "Описание 1",
            "tarif_description" => "ОПИСАНИЕ ТАРИФА",
        ],
        2 =>[
            "id" => "2",
            "title" => "Программа 2",
            "price" => 300,
            "description" => "Описание 2",
            "tarif_description" => "ОПИСАНИЕ ТАРИФА",
        ],
        3 =>[
            "id" => "3",
            "title" => "Программа 3",
            "price" => 400,
            "description" => "Описание 3",
            "tarif_description" => "ОПИСАНИЕ ТАРИФА",
        ],
        4 =>[
            "id" => "4",
            "title" => "Программа 4",
            "price" => 500,
            "description" => "Описание 4",
            "tarif_description" => "ОПИСАНИЕ ТАРИФА",
        ],
        5 =>[
            "id" => "5",
            "title" => "Программа 5",
            "price" => 800,
            "description" => "Описание 5",
            "tarif_description" => "ОПИСАНИЕ ТАРИФА",
        ],
    ];


    const PAYMENTS = [
        0 =>[
            "title" => "Квитанция",
            "payment_type" => 0,
            "payment_flow" => 0,
        ],
        1 =>[
            "title" => "Чек",
            "payment_type" => 0,
            "payment_flow" => 0,
        ],
        2 =>[
            "title" => "Счет",
            "payment_type" => 1,
            "payment_flow" => 1,
        ],
        3 =>[
            "title" => "Платежная странииц",
            "payment_type" => 1,
            "payment_flow" => 1,
        ],
    ];


    public function saveProduct($contract_id, $data)
    {
        $product = $this;
        $product->contract_id = $contract_id;
        $product->program_id = $data->sk['program_id'];
        $product->save();
        return true;
    }


    public function calculation($contract)
    {

        //ТУТ РАСЧЕТ
        $product = $this;

        foreach (TickProtection::PROGRAMS as $prog){
            if($product->program_id == $prog["id"]){
                $contract->payment_total = getFloatFormat($prog["price"]);
                $contract->tarif_description = $prog["tarif_description"];
                $contract->save();
                $contract->calculationFpAndSegment();
                return true;
            }
        }

        return false;
    }

}
