<?php

namespace App\Http\Controllers\API\TitRest;

use App\Helpers\ResultGenerate;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto;
use App\Models\Contracts\Payments;
use App\Models\Contracts\Subjects;
use App\Models\Directories\InstallmentAlgorithms;
use App\Models\Directories\Products\Data\Kasko\Standard;
use App\Models\User;
use App\Models\Vehicle\VehiclePurpose;
use Illuminate\Http\Request;

class ApiRestController
{
    private $result = null;

    public function __construct(Request $request)
    {
        $this->result = new \stdClass();
        $this->result->response = null;
        $this->result->error = null;
        if($request && isset($request->token) && $request->token == '8vql7Y5EPrhZiKDFbzr4hDPzkoe3kXil2lFtXyw7YvR9yqwVpWZXyOG9IVml'){

        }else{
            $this->result->error = 'Доступ запрещен!';
        }

    }




    public function returnResult(){
        return response()->json((array)$this->result);
    }

    public function getContract(Request $request)
    {
        $id = (int)$request->id;
        if($id > 0){
            $contract = Contracts::query()->where('id', $id)->first();
            if($contract){



                $result = [
                    'contract_id' => $contract->id,
                    'product_id' => $contract->product_id,
                    'program_id' => $contract->program_id,
                    'product_title' => ($contract->product) ? $contract->product->title : null,
                    'program_title' => ($contract->program) ? $contract->program->title : null,
                    'contract_title' => $contract->bso->bso_title,
                    'contract_serie' => $contract->bso->bso_serie->bso_serie,
                    'contract_number' => $contract->bso->bso_number,
                    'sign_date' => setDateTimeFormat($contract->sign_date),
                    'begin_date' => setDateTimeFormat($contract->begin_date),
                    'end_date' => setDateTimeFormat($contract->end_date),

                    'payment_total' => getFloatFormat($contract->payment_total),
                    'payments' => $this->getContractPayments($contract->payments),


                    'agent' => $this->getUser($contract->agent),
                    'curator' => $this->getUser($contract->curator),

                    'terms' => $contract->data,
                    'risk' => $this->getRisk($contract),
                    'object' => $this->getObject($contract),

                    'insurer' => $this->getSubjects($contract->insurer),
                    'owner' => $this->getSubjects($contract->insurer),
                    'beneficiar' => $this->getSubjects($contract->insurer),
                    'drivers' => $contract->contracts_insurers,

                ];

                $this->result->response = $result;

            }else{
                $this->result->error = 'Договор не найден!';
            }
        }else{
            $this->result->error = 'Не выбран договор!';
        }
        return $this->returnResult();
    }


    public function getPayments(Request $request)
    {
        $result = [];
        if(!$this->result->error){
            $from = getDateFormatEn($request->from);
            $to = getDateFormatEn($request->to);

            $payments = Payments::query()->where('statys_id', 1);
            if($from && strlen($from) > 9){
                $payments->where('payment_data','>=', $from);
            }

            if($to && strlen($to) > 9){
                $payments->where('payment_data','<=', $to);
            }

            foreach ($payments->get() as $payment){
                $contract = $payment->contract;
                $data = [
                    'contract_id' => $contract->id,
                    'product_id' => $contract->product_id,
                    'program_id' => $contract->program_id,
                    'product_title' => ($contract->product) ? $contract->product->title : null,
                    'program_title' => ($contract->program) ? $contract->program->title : null,
                    'contract_title' => $contract->bso->bso_title,
                    'contract_serie' => $contract->bso->bso_serie->bso_serie,
                    'contract_number' => $contract->bso->bso_number,
                    'sign_date' => setDateTimeFormat($contract->sign_date),
                    'begin_date' => setDateTimeFormat($contract->begin_date),
                    'end_date' => setDateTimeFormat($contract->end_date),

                    'agent_id' => $contract->agent_id,
                    'agent_title' => $contract->agent->name,

                    'insurer' => $contract->insurer->title,

                    'payment_id' => $payment->id,
                    'installment_algorithms_payment' => getFloatFormat($payment->installment_algorithms_payment),
                    'payment_data' => $payment->payment_data,
                    'payment_total' => getFloatFormat($payment->payment_total),
                    'official_discount' => getFloatFormat($payment->official_discount),
                    'official_discount_total' => getFloatFormat($payment->official_discount_total),
                    'is_deleted' => $payment->is_deleted,
                    'invoice_id' => $payment->invoice_id,
                    'payment_type' => $payment->payment_type,
                    'payment_flow' => $payment->payment_flow,

                    'payment_type_title' => (isset(Payments::PAYMENT_TYPE[$payment->payment_type]))? Payments::PAYMENT_TYPE[$payment->payment_type] : null,
                    'payment_flow_title' => (isset(Payments::PAYMENT_FLOW[$payment->payment_flow]))? Payments::PAYMENT_FLOW[$payment->payment_flow] : null,

                    'payment_method' => ($payment->payment_method) ? $payment->payment_method->title : null,
                ];


                $result[] = $data;
            }
        }
        $this->result->response = $result;
        return $this->returnResult();
    }


    public function getSubjects($subject){
        $result = null;
        if($subject){

            $result = [
                'id' => $subject->id,
                'general_subject_id' => $subject->general_subject_id,
                'title' => $subject->title,
                'type' => $subject->type,
                'email' => $subject->email,
                'phone' => $subject->phone,
                'data' => $subject->data()->toArray(),
            ];

        }

        return $result;
    }

    public function getUser($user)
    {
        $result = null;
        if($user){
            $result = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'agent_contract_title' => $user->agent_contract_title,
                'agent_contract_begin_date' => $user->agent_contract_begin_date,
                'agent_contract_end_date' => $user->agent_contract_end_date,
            ];

        }

        return $result;
    }


    public function getContractPayments($payments)
    {
        $result = null;
        if($payments){
            $result = [];
            foreach ($payments as $payment){
                $data = [

                    'payment_id' => $payment->id,
                    'status_id' => $payment->statys_id,
                    'status' => Payments::STATUS[$payment->statys_id],
                    'is_deleted' => $payment->is_deleted,
                    'installment_algorithms_payment' => getFloatFormat($payment->installment_algorithms_payment),
                    'invoice_id' => $payment->invoice_id,
                    'payment_data' => $payment->payment_data,
                    'payment_total' => getFloatFormat($payment->payment_total),
                    'official_discount' => getFloatFormat($payment->official_discount),
                    'official_discount_total' => getFloatFormat($payment->official_discount_total),

                    'payment_type' => $payment->payment_type,
                    'payment_flow' => $payment->payment_flow,

                    'payment_type_title' => (isset(Payments::PAYMENT_TYPE[$payment->payment_type]))? Payments::PAYMENT_TYPE[$payment->payment_type] : null,
                    'payment_flow_title' => (isset(Payments::PAYMENT_FLOW[$payment->payment_flow]))? Payments::PAYMENT_FLOW[$payment->payment_flow] : null,

                    'payment_method' => ($payment->payment_method) ? $payment->payment_method->title : null,
                ];
            }
            $result[] = $data;
        }
        return $result;
    }


    public function getRisk($contract)
    {
        $result = [];

        if($calculation = $contract->calculation){

            if($calculation->json && strlen($calculation->json) > 0){
                $json = \GuzzleHttp\json_decode($calculation->json);

                if($contract->product->slug == 'gap'){
                    $json->insurance_amount = getFloatFormat($json->insurance_amount);
                    $result[] = (array)$json;
                }

                if($contract->product->slug == 'dgo'){
                    $json->title = "ДГО";
                    $json->insurance_amount = getFloatFormat($json->insurance_amount);
                    $result[] = (array)$json;
                }

                if($contract->product->slug == 'kasko'){
                    if(isset($json->info)){
                        $info = (array)$json->info;
                        foreach ($info as $_in){
                            if(isset($_in->insurance_amount)) $_in->insurance_amount = getFloatFormat($_in->insurance_amount);
                            if(isset($_in->payment_total)) $_in->payment_total = getFloatFormat($_in->payment_total);
                            $result[] = (array)$_in;
                        }

                    }
                }


            }


        }


        return $result;
    }


    public function getObject($contract)
    {
        $result = [];

        $object_insurer = $contract->object_insurer;
        if($object_insurer){
            $auto = $object_insurer->data();
            if($auto){
                $result = $auto->toArray();
                unset($result['id']);
                unset($result['mark_code']);
                unset($result['model_code']);
                unset($result['model_classification_code']);
                unset($result['object_insurer_id']);
                unset($result['ts_category']);

                $result['category_id'] = $auto->ts_category;
                $result['category'] = $auto->category_auto->title;

                $result['mark'] = ($auto->mark) ? $auto->mark->title : '';
                $result['mark_ref_key_1c'] = ($auto->mark) ? $auto->mark->ref_key_1c : '';

                $result['model'] = ($auto->model) ? $auto->model->title : '';
                $result['model_ref_key_1c'] = ($auto->model) ? $auto->model->ref_key_1c : '';

                $result['model_modification'] = ($auto->model_classification_code == '0') ? '' : $auto->model_classification_code;

                $result['anti_theft_system'] = ($auto->anti_theft_system) ? $auto->anti_theft_system->title : '';

                $result['doc_type_title'] = (isset(ObjectInsurerAuto::DOC_TYPE_TS[$auto->doc_type])) ? ObjectInsurerAuto::DOC_TYPE_TS[$auto->doc_type] : '';

            }
        }


        return $result;
    }


}