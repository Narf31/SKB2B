<?php

namespace App\Models\Contracts;

use App\Classes\Export\TagModels\Contracts\TagPayments;
use App\Models\Acts\ReportAct;
use App\Models\BSO\BsoActs;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Directories\FinancialPolicy;
use App\Models\Finance\Invoice;
use App\Models\Organizations\Organization;
use App\Models\Reports\ReportOrders;
use App\Models\Settings\PaymentMethods;
use App\Models\User;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class Payments extends Model
{
    use ActiveConstTrait;

    const TAG_MODEL = TagPayments::class;

    protected $table = 'payments';

    protected $guarded = ['id'];

    public $timestamps = false;

    const STATUS = [
        0 => 'Не оплачен',
        1 => 'Оплачен'
    ];

    const PAYMENT_TYPE = [
        0 => 'Наличные',
        1 => 'Безналичные',
        2 => 'Карта',
    ];

    const PAYMENT_FLOW = [
        0 => 'Брокер',
        1 => 'СК',
    ];


    const TRANSACTION_TYPE = [
        0 => 'Взнос',
        1 => 'Долг',
        2 => 'Выплата',
    ];

    const OVERDUE = [
        1 => ['key' => 1, 'title' => 'претензий нет', 'color' => '$FFF' ],
        2 => ['key' => 2, 'title' => 'более 3-х дней', 'color' => '#DDF' ],
        3 => ['key' => 3, 'title' => 'более 15-ти дней', 'color' => '#FDD' ],
    ];

    const TYPE_RU = ['sk' => 'СК','cash' => 'Наличные','cashless' => 'Безналичные'];

    public function contract(){
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function bso(){
        return $this->hasOne(BsoItem::class, 'id', 'bso_id');
    }


    public function agent(){
        return $this->hasOne(User::class, 'id', 'agent_id');
    }

    public function parent_agent(){
        return $this->hasOne(User::class, 'id', 'parent_agent_id');
    }

    public function manager(){
        return $this->hasOne(User::class, 'id', 'manager_id');
    }

    public function invoice(){
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }


    public function agent_organization(){
        return $this->hasOne(Organization::class, 'id', 'agent_organization_id');
    }

    public function org(){
        return $this->hasOne(Organization::class, 'id', 'org_id');
    }

    public function reports_border(){
        return $this->hasOne(ReportOrders::class,'id', 'reports_order_id');
    }

    public function reports_dvoy(){
        return $this->hasOne(ReportOrders::class, 'id', 'reports_dvou_id');
    }

    public function act_sk(){
        return $this->hasOne(ReportAct::class, 'id', 'acts_sk_id');
    }

    public function realized_act(){
        return $this->hasOne(BsoActs::class, 'id', 'realized_act_id');
    }

    public function receipt(){
        return $this->hasOne(BsoItem::class, 'id', 'bso_receipt_id');
    }

    public function payment_method(){
        return $this->hasOne(PaymentMethods::class, 'id', 'payment_method_id');
    }

    public function supplementary() {
        return $this->hasOne(ContractsSupplementary::class, 'id', 'supplementary_id');
    }

    public static function getPayments()
    {
        $payment = self::query()->with('bso','org','contract','agent','manager');



        return $payment;
    }

    public static function getPaymentsUser()
    {
        $payment = self::query();

        //Проверка на доступы
        $user = auth()->user();
        $visibility_obj = $user->role->rolesVisibility(6)->visibility;


        if($visibility_obj == 0){ //Все

        }elseif ($visibility_obj == 1){//Все в рамках организации

            $payment->where(function ($query) use ($user) {
                $query->where('payments.agent_id', $user->id)
                    ->orWhere('payments.agent_parent_id', $user->id)
                    ->orWhere('payments.agent_curator_id', $user->id)
                    ->orWhere('payments.agent_organization_id', $user->organization_id);
            });


        }elseif ($visibility_obj == 2){//Только свои

            $payment->where("payments.agent_id", $user->id);

        }elseif ($visibility_obj == 3){//Только свои и своих подчиненных

            $payment->where(function ($query) use ($user) {
                $query->where('payments.agent_id', $user->id)
                    ->orWhere('payments.agent_parent_id', $user->id)
                    ->orWhere('payments.agent_curator_id', $user->id)
                    ->orWhere('payments.agent_organization_id', $user->organization_id);
            });

        } else {
            $payment->where('payments.agent_id', auth()->id());
        }


        return $payment;
    }


    public function setBsoLogToPayment($location_id = 10, $postpone = 0)
    {

        $payment = $this;
        $payment->bso->setBsoLog($location_id, $postpone);

        $_user = $payment->agent_id;
        if(auth()->check()){
            $_user = auth()->id();
        }


        if ($payment->bso_receipt_id > 0) {
            BsoLogs::setLogs(
                $payment->bso_receipt_id,
                $payment->bso->state_id,
                $location_id,
                0,
                $_user,
                0,
                0,
                0,
                $postpone
            );
        }


        return true;

    }


    public function getPaymentAgentSum()
    {
        return $this->payment_total-$this->official_discount_total;

    }

}
