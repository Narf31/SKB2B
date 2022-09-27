<?php

namespace App\Models\Reports;

use App\Classes\Export\TagModels\Report\TagReportOrder;
use App\Models\BSO\BsoItem;
use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSuppliers;
use App\Models\Organizations\Organization;
use App\Models\Settings\FinancialGroupPaymentInfo;
use App\Models\User;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;

class ReportOrders extends Model {

    use ActiveConstTrait;

    protected $table = 'reports_orders';

    protected $guarded = ['id'];

    const TAG_MODEL = TagReportOrder::class;

    const TYPE = [
        0 => 'Бордеро',
        1 => 'ДВОУ'
    ];
    const TEMPLATE_CATEGORY = [
        0 => 'report_borderau_to_sk',
        1 => 'report_dvou_to_sk'
    ];

    const STATE = [
        0 => 'Создан',
        1 => 'На согласовании',
        2 => 'На оплате',
        3 => 'Частично оплачен',
        4 => 'Оплачен',
    ];


    const MARKER_COLORS = [
        0 => ['color'=>'', 'title' => 'Нет'],
        1 => ['color'=>'#f2f2f2', 'title' => 'Серый'],
        2 => ['color'=>'#fffae6', 'title' => 'Желтый'],
        3 => ['color'=>'#ccd9ff', 'title' => 'Синий'],
        4 => ['color'=>'#e6ffe6', 'title' => 'Зеленый'],
        5 => ['color'=>'#ffcccc', 'title' => 'Красный'],
    ];


    public function bso_supplier(){
        return $this->hasOne(BsoSuppliers::class, 'id', 'bso_supplier_id');
    }

    public function organization(){
        return $this->hasOne(Organization::class, 'id', 'agent_organization_id');
    }

    public function bso_items(){
        return $this->hasMany(BsoItem::class, 'acts_sk_id');
    }

    public function create_user(){
        return $this->hasOne(User::class, 'id', 'create_user_id');
    }


    public function report_payment_sums(){
        return $this->hasMany(ReportPaymentSum::class, 'report_id');
    }


    public function accept_user(){
        return $this->hasOne(User::class, 'id', 'accept_user_id');
    }

    public static function getReportQuery()
    {
        $report = ReportOrders::query();
        $organization = Organization::getALLOrg();
        $organization->select("organizations.id");
        $report->whereRaw('reports_orders.agent_organization_id IN (' . getLaravelSql($organization) . ')');
        return $report;
    }

    public static function getReportId($id)
    {
        $report = ReportOrders::getReportQuery()->where('reports_orders.id', $id)->get()->first();
        if(!$report){
            abort(403);
        }
        return $report;
    }



    public function getPayments()
    {
        $payments = ReportPayments::query();

        if((int)$this->type_id == 0) {
            $payments->where('reports_payments.reports_order_id', '=', $this->id);
        }
        if((int)$this->type_id == 1) $payments->where('reports_payments.reports_dvou_id', '=', $this->id);

        return $payments;
    }

    public function getTemplateCategory(){
        return $this->template_category_ru('type_id');
    }

    public function refreshSumOrder()
    {
        $report = $this;
        $payments = $report->getPayments();

        $to_transfer_total = 0;
        $to_return_total = 0;

        $report->payment_total = 0;//$payments->sum('payment_total');

        $report->bordereau_total = $payments->sum('financial_policy_kv_bordereau_total');
        $report->dvoy_total = $payments->sum('financial_policy_kv_dvoy_total');

        $report->amount_total = $report->bordereau_total+$report->dvoy_total;

        foreach ($payments->get() as $payment)
        {
            $res_sum = $this->getSumToTransferAndReturn($payment, $this->type_id);

            $to_transfer_total += $res_sum->to_transfer_total;
            $to_return_total += $res_sum->to_return_total;
        }

        $report->to_transfer_total = $to_transfer_total;
        $report->to_return_total = $to_return_total;

        $report->save();

        return true;
    }




    public function getSumToTransferAndReturn($payment_re, $type_id)
    {
        $res_sum = new \stdClass();
        $res_sum->to_transfer_total = 0;
        $res_sum->to_return_total = 0;

        $payment = $payment_re->payment;
        $payment_total = $payment->payment_total - $payment->official_discount_total;
        $bordereau_total = $payment_re->financial_policy_kv_bordereau_total;
        $dvoy_total = $payment_re->financial_policy_kv_dvoy_total;

        $payment_type = $payment->payment_type; // тип оплаты
        $payment_flow = $payment->payment_flow; // поток оплаты

        $hold_type_id = 2; //0 - Без удержания 1 - С удержанием 2 - Частичное удержание

        $hold_kv = $payment->bso->supplier->hold_kv_product($payment->bso->product_id);
        if($hold_kv) {
            $group_id = $payment->contract->agent->financial_group_id;
            $payment_info = FinancialGroupPaymentInfo::findPaymentInfo($hold_kv->insurance_companies_id, $hold_kv->bso_supplier_id, $hold_kv->id, $group_id, $payment->bso->bso_class_id);
            if(isset($payment_info)) $hold_type_id = $payment_info->hold_type_id;
        }


        if((int)$this->type_id == 0){ // БОРДЕРО

            //оплата в БРОКЕР
            if($payment_flow == 0){

                $res_sum->to_transfer_total = $payment_total;
                switch ($hold_type_id) {
                    case 0:
                        $res_sum->to_return_total += $bordereau_total;
                        break;
                    case 1:
                        $res_sum->to_transfer_total -= $bordereau_total;
                        $res_sum->to_return_total += 0;
                        break;
                    case 2:
                        $res_sum->to_transfer_total -= $bordereau_total;
                        $res_sum->to_return_total += $dvoy_total;
                        break;
                }

            }

            //оплата в СК
            if($payment_flow == 1){
                $res_sum->to_return_total += $bordereau_total;
            }

        }

        if((int)$this->type_id == 1){ // ДВОУ

            $res_sum->to_return_total += $dvoy_total;

        }

        return $res_sum;
    }



    public function refreshAdvancePayment()
    {
        $report = $this;

        $total_to_transfer_total = 0;
        $total_to_return_total = 0;


        $advance_payment =  $report->to_transfer_total; //Сумма К перечислению
        if($report->report_payment_sums){
            $total_to_transfer_total = $report->to_transfer_total - $report->report_payment_sums->where('type_id', 1)->sum('amount');
            $total_to_return_total = $report->to_return_total - $report->report_payment_sums->where('type_id', 0)->sum('amount');

            if($total_to_transfer_total == 0 && $total_to_return_total == 0){
                $report->accept_status = 4;
            }else{
                $report->accept_status = 3;
            }
            $report->save();
        }

        return true;
    }


}