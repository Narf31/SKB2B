<?php
namespace App\Classes\Export\TagModels\Contracts;

use App\Classes\Export\TagModels\TagModel;
use App\Models\Contracts\Payments;

class TagPayments extends TagModel {


    public function apply(){

        $replace_arr = [
            'official_discount_sum' => 0,
            'unofficial_discount_sum' => 0,
            'kv_agent_total_sum' => 0,
            'kv_kurator_total_sum' => 0,
            'cash_amount_real_sum' => 0,
            'payment_total_sum' => 0,
            'to_transfer_total_sum' => 0,
            'to_return_total_sum' => 0,
            'kv_bordereau_total_sum' => 0,
            'kv_dvou_total_sum' => 0,
            'payment_list' => []
        ];

        foreach ($this->builder->get() as $k => $payment){

            $sum_to_transfer = $this->getSumToTransfer($payment);
            $to_transfer_total = isset($sum_to_transfer->to_transfer_total) ? $sum_to_transfer->to_transfer_total : 0;
            $to_return_total = isset($sum_to_transfer->to_return_total) ? $sum_to_transfer->to_return_total : 0;

            $cash_amount_real = $this->getCashAmountReal($payment);

            $row = [];

            /** Участники */
            $row['agent'] = $payment->agent->name;
            $row['manager'] = $payment->manager ? $payment->manager->name : "";
            $row['organization'] = $payment->org ? $payment->org->title : "";
            $row['insurance'] = $payment->bso && $payment->bso->insurance ? $payment->bso->insurance->title : "";
            $row['cashier'] = $payment->invoice && $payment->invoice->invoice_payment_user ? $payment->invoice->invoice_payment_user->name : "";
            $row['insurer'] = $payment->getInsurer();



            /** Номера */
            $row['number'] = $k + 1;
            $row['policy_number'] = $payment->bso ? $payment->bso->bso_title : "";
            $row['invoice_number'] = $payment->invoice ? $payment->invoice->id : "";
            $row['receipt_number'] = $payment->bso_receipt ? $payment->bso_receipt: "";
            $row['payment_number'] = $payment->payment_number;
            $row['policy_only_serial'] = isset(explode(' ', $row['policy_number'])[0]) ? explode(' ', $row['policy_number'])[0] : "";
            $row['policy_only_number'] = isset(explode(' ', $row['policy_number'])[1]) ? explode(' ', $row['policy_number'])[1] : "";



            /** Даты */
            $row['payment_date'] = setDateTimeFormatRu($payment->invoice_payment_date,1);
            $row['contract_date'] = $payment->contract ? setDateTimeFormatRu($payment->contract->sign_date,1) : "";
            $row['checkout_date'] = $payment->invoice_payment_date ? getDateFormatRu($payment->invoice_payment_date) : "";
            $row['receipt_date'] = $payment->receipt ? getDateFormatRu($payment->receipt->time_create) : "";
            $row['contract_begin_date'] = $payment->contract ? getDateFormatRu($payment->contract->begin_date) : "";
            $row['contract_end_date'] = $payment->contract ? getDateFormatRu($payment->contract->end_date) : "";



            /** Типы и условия */
            $row['payment_type'] = $payment->payment_type_ru('payment_type');
            $row['type'] = $payment->transaction_type_ru('type_id');
            $row['contract_type'] = $payment->contract ? $payment->contract->type_ru() : "";
            $row['payment_flow'] = $payment->payment_flow_ru('payment_flow');
            $row['personal_sale'] = $payment->contract ? $payment->contract->is_personal_sales_ru('is_personal_sales'):"" ;
            $row['sale_condition'] = $payment->contract ? $payment->contract->sales_condition_ru('sales_condition') :"" ;
            $row['accept'] = $payment->contract ? $payment->contract->kind_acceptance_ru('kind_acceptance') :"" ;
            $row['installment_algorithm'] = $payment->contract && $payment->contract->installment_algorithm ? $payment->contract->installment_algorithm->title : "";
            $row['product'] = $payment->bso && $payment->bso->product ? $payment->bso->product->title : "";
            $row['payment_status'] = $payment->status_ru('statys_id');



            /** Проценты */
            $row['kv_agent'] = $payment->financial_policy_kv_agent;
            $row['kv_curator'] = $payment->financial_policy_kv_parent;
            $row['kv_bordereau'] = $payment->financial_policy_kv_bordereau;
            $row['kv_dvou'] = $payment->financial_policy_kv_dvoy;



            /** Цены */
            $row['cash_amount_real'] = getPriceFormat($cash_amount_real);
            $row['payment_on_receipt'] = getPriceFormat($payment->payment_total);
            $row['official_discount'] = getPriceFormat($payment->official_discount);
            $row['unofficial_discount'] = getPriceFormat($payment->informal_discount);
            $row['official_discount_total'] = getPriceFormat($payment->official_discount_total);
            $row['unofficial_discount_total'] = getPriceFormat($payment->informal_discount_total);
            $row['kv_agent_total'] = getPriceFormat($payment->financial_policy_kv_agent_total) ;
            $row['kv_curator_total'] = getPriceFormat($payment->financial_policy_kv_parent_total);
            $row['payment_total'] = getPriceFormat($payment->payment_total);
            $row['margin'] = getPriceFormat($payment->getMargin());
            $row['insurance_amount'] = getPriceFormat($payment->getInsuranceAmount());
            $row['to_transfer_total'] = getPriceFormat($to_transfer_total);
            $row['to_return_total'] = getPriceFormat($to_return_total);
            $row['kv_bordereau_total'] = getPriceFormat($payment->financial_policy_kv_bordereau_total);
            $row['kv_dvou_total'] = getPriceFormat($payment->financial_policy_kv_dvoy_total);



            /** Суммирумые колонки */
            $replace_arr['official_discount_sum'] += $payment->official_discount_total;
            $replace_arr['unofficial_discount_sum'] += $payment->informal_discount_total;
            $replace_arr['kv_agent_total_sum'] += $payment->financial_policy_kv_agent_total;
            $replace_arr['kv_kurator_total_sum'] += $payment->financial_policy_kv_parent_total;
            $replace_arr['cash_amount_real_sum'] += $cash_amount_real;
            $replace_arr['payment_total_sum'] += $payment->payment_total;
            $replace_arr['to_transfer_total_sum'] += $to_transfer_total;
            $replace_arr['to_return_total_sum'] += $to_return_total;
            $replace_arr['kv_bordereau_total_sum'] += $payment->financial_policy_kv_bordereau_total;
            $replace_arr['kv_dvou_total_sum'] += $payment->financial_policy_kv_dvoy_total;

            $replace_arr['payment_list'][] = $row;
        }



        /** Суммы строкой */
        $replace_arr['official_discount_sum_str'] = num2str($replace_arr['official_discount_sum']);
        $replace_arr['unofficial_discount_sum_str'] = num2str($replace_arr['unofficial_discount_sum']);
        $replace_arr['kv_agent_total_sum_str'] = num2str($replace_arr['kv_agent_total_sum']);
        $replace_arr['kv_kurator_total_sum_str'] = num2str($replace_arr['kv_kurator_total_sum']);
        $replace_arr['cash_amount_real_sum_str'] = num2str($replace_arr['cash_amount_real_sum']);
        $replace_arr['payment_total_sum_str'] = num2str($replace_arr['payment_total_sum']);
        $replace_arr['to_transfer_total_sum_str'] = num2str($replace_arr['to_transfer_total_sum']);
        $replace_arr['to_return_total_sum_str'] = num2str($replace_arr['to_return_total_sum']);
        $replace_arr['kv_bordereau_total_sum_str'] = num2str($replace_arr['kv_bordereau_total_sum']);
        $replace_arr['kv_dvou_total_sum_str'] = num2str($replace_arr['kv_dvou_total_sum']);


        $replace_arr['official_discount_sum'] = getPriceFormat($replace_arr['official_discount_sum']);
        $replace_arr['unofficial_discount_sum'] = getPriceFormat($replace_arr['unofficial_discount_sum']);
        $replace_arr['kv_agent_total_sum'] = getPriceFormat($replace_arr['kv_agent_total_sum']);
        $replace_arr['kv_kurator_total_sum'] = getPriceFormat($replace_arr['kv_kurator_total_sum']);
        $replace_arr['cash_amount_real_sum'] = getPriceFormat($replace_arr['cash_amount_real_sum']);
        $replace_arr['payment_total_sum'] = getPriceFormat($replace_arr['payment_total_sum']);
        $replace_arr['to_transfer_total_sum'] = getPriceFormat($replace_arr['to_transfer_total_sum']);
        $replace_arr['to_return_total_sum'] = getPriceFormat($replace_arr['to_return_total_sum']);
        $replace_arr['kv_bordereau_total_sum'] = getPriceFormat($replace_arr['kv_bordereau_total_sum']);
        $replace_arr['kv_dvou_total_sum'] = getPriceFormat($replace_arr['kv_dvou_total_sum']);


        $replace_arr['payment_count'] = count($replace_arr['payment_list']);


        return $replace_arr;
    }



    public static function doc(){

        $doc = [

            'Доступные теги платежей<sup style="font-size: 75%;">(общие)</sup>' => [

                'payment_list' => '<b style="color: #333">Тег списка платежей</b>',
                'payment_count' => 'Количество платежей',

                /** Суммирумые колонки */
                'official_discount_sum' => 'Сумма оффициальной скидки',
                'unofficial_discount_sum' => 'Сумма неоффициальной скидки',
                'kv_agent_total_sum' => 'Сумма вознаграждения агента',
                'kv_kurator_total_sum' => 'Сумма вознаграждения руководителя',
                'cash_amount_real_sum' => 'Сумма в кассу реальная по всем позициям',
                'payment_total_sum' => 'Сумма полная',
                'to_transfer_total_sum' => 'Сумма к перечислению в СК',
                'to_return_total_sum' => 'Сумма вознаграждения брокера',
                'kv_bordereau_total_sum' => 'Сумма агентского вознаграждения Бордеро',
                'kv_dvou_total_sum' => 'Сумма агентского вознаграждения Двоу',


                /** Суммы прописью */
                'official_discount_sum_str' => 'Сумма оффициальной скидки прописью',
                'unofficial_discount_sum_str' => 'Сумма неоффициальной скидки прописью',
                'kv_agent_total_sum_str' => 'Сумма вознаграждения агента прописью',
                'kv_kurator_total_sum_str' => 'Сумма вознаграждения руководителя прописью',
                'cash_amount_real_sum_str' => 'Сумма в кассу реальная по всем позициям прописью',
                'payment_total_sum_str' => 'Сумма полная прописью',
                'to_transfer_total_sum_str' => 'Сумма к перечислению в СК прописью',
                'to_return_total_sum_str' => 'Сумма вознаграждения брокера прописью',
                'kv_bordereau_total_sum_str' => 'Сумма агентского вознаграждения Бордеро прописью',
                'kv_dvou_total_sum_str' => 'Сумма агентского вознаграждения Двоу прописью',

            ],


            'Данные платежа<sup style="font-size: 75%; color: #000; font-weight: bold">(список)</sup>' => [

                /** Участники */
                'agent' => 'Агент',
                'manager' => 'Менеджер',
                'organization' => 'Организация',
                'insurance' => 'СК',
                'cashier' => 'Кассир',
                'insurer' => 'Страхователь',


                /** Номера */
                'number' => 'Номер порядковый',
                'invoice_number' => 'Номер счёта',
                'policy_number' => 'Серия и номер полиса',
                'policy_only_number' => 'Номер полиса',
                'receipt_number' => 'Номер квитанции',
                'payment_number' => 'Номер платежа',
                'policy_only_serial' => 'Серия полиса',


                /** Даты */
                'payment_date' => 'Дата оплаты',
                'contract_date' => 'Дата договора',
                'checkout_date' => 'Дата по кассе',
                'receipt_date' => 'Дата квитанции',
                'contract_begin_date' => 'Дата начала действия договора',
                'contract_end_date' => 'Дата окончания действия договора',


                /** Типы и условия */
                'payment_type' => 'Тип платежа',
                'type' => 'Тип',
                'contract_type' => 'Тип договора',
                'payment_flow' => 'Поток оплаты',
                'personal_sale' => 'Личная продажа',
                'sale_condition' => 'Условие продажи',
                'accept' => 'Акцепт',
                'installment_algorithm' => 'Алгоритм рассрочки',
                'product' => 'Продукт',
                'payment_status' => 'Статус оплаты',


                /** Проценты */
                'kv_agent' => 'Вознаграждение агента %',
                'kv_curator' => 'Вознаграждение руководителя %',
                'kv_bordereau' => 'Вознаграждение агента Бордеро %',
                'kv_dvou' => 'Вознаграждение агента Двоу %',


                /** Цены */
                'payment_on_receipt' => 'Взнос по квитанции',
                'official_discount' => 'Официальная скидка %',
                'unofficial_discount' => 'Нефициальная скидка %',
                'official_discount_total' => 'Официальная скидка',
                'unofficial_discount_total' => 'Нефициальная скидка',
                'kv_agent_total' => 'Вознаграждение агента',
                'kv_curator_total' => 'Вознаграждение руководителя',
                'margin' => 'Маржа',
                'cash_amount_real' => 'Сумма в кассу реальная',
                'payment_total' => 'Сумма',
                'insurance_amount' => 'Страховая сумма',
                'to_transfer_total' => 'К перечислению в СК',
                'to_return_total' => 'Вознаграждение брокера',
                'kv_bordereau_total' => 'Вознаграждение агента Бордеро',
                'kv_dvou_total' => 'Вознаграждение агента Двоу',

            ]

        ];

        foreach ($doc as $k => $v){
            asort($doc[$k]);
        }

        return  $doc;
    }







    public function getSumToTransfer(Payments $payment){

        if($process = $this->process()->get('TagReportOrder')){
            return $process->report->getSumToTransferAndReturn($payment, $process->report->type_id);
        }

        return false;

    }

    private function getCashAmountReal(Payments $payment){

        $cash_amount_real = $payment->getPaymentAgentSum();

        if($tag_invoice = $this->process()->get('TagInvoice')){
            if($invoice = $tag_invoice->builder->first()){
                if($invoice->type == "cashless"){
                    $cash_amount_real = 0;
                }
            }
        }

        return $cash_amount_real;
    }


}
