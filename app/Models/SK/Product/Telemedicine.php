<?php

namespace App\Models\SK\Product;

use Illuminate\Database\Eloquent\Model;

class Telemedicine extends Model
{
    protected $table = 'sk_product_telemedicine';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TEMPLATE_CATEGORY = 'system_policy_telemedicine';


    const PROGRAMS = [
        1 =>[
            "id" => "1",
            "title" => "Взрослый",
            "price" => 1900,
            "description" => "Описание 1",
            "tarif_description" => "ОПИСАНИЕ ТАРИФА",
        ],
        2 =>[
            "id" => "2",
            "title" => "Семья",
            "price" => 6900,
            "description" => "Описание 2",
            "tarif_description" => "ОПИСАНИЕ ТАРИФА",
        ],
        3 =>[
            "id" => "3",
            "title" => "Дети",
            "price" => 3500,
            "description" => "Описание 3",
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
