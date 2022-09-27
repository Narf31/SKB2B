<?php

namespace App\Services\Integration\VernaControllers;

use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\SubjectsFlDocType;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\BsoSuppliers;
use App\Models\Settings\SettingsSystem;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Models\Vehicle\VehiclePurpose;
use App\Processes\Operations\Contracts\Invoice\InvoiceAutomatic;
use Mockery\Exception;

class VernaPayment
{

    public $Send = null;

    public function __construct()
    {
        $this->Send = new VernaSend();
    }

    public function setPaymentBSO($contract, $payment, $isOnlinePayment = 'N')
    {



        $result = new \stdClass();
        $result->status = false;
        $result->error = '';

        if($isOnlinePayment == 'Y'){

            $params = [
                'bso_number' => ($contract->bso)?$contract->bso->bso_number:'',
                'bso_series' => ($contract->bso)?$contract->bso->bso_serie->bso_serie:'',
                'invoiceIsn' => $contract->calculation->sk_key_id,
            ];


            $response = $this->Send->send('products/osago/attachBSO', $params, 'GET');
            if(isset($response->data) && isset($response->data->result) && isset($response->data->result->status) == 'OK'){
                return $this->getPageLinck($contract, $payment);
            }

        }else{

            $params = [
                'isOnlinePayment' => $isOnlinePayment,
                'bso_number' => ($contract->bso)?$contract->bso->bso_number:'',
                'bso_series' => ($contract->bso)?$contract->bso->bso_serie->bso_serie:'',
                'isn' => $contract->calculation->sk_key_id,
            ];
            $response = $this->Send->send('products/osago/createOsagoAgreementWithBso', $params, 'GET');

            if(isset($response->data) && isset($response->data->result)){

                $result->status = true;
                $result->error = '';

                if(isset($response->data->result->attachments)){
                    $mask = new VernaMask();
                    $mask->setDocument($contract, $response->data->result->attachments);
                }

            }elseif(isset($response->data) && isset($response->data->error) && isset($response->data->error) && isset($response->data->error->text)){
                $result->error = $response->data->error->text;
            }
        }






        return $result;

    }

    public function setPaymentReceipt($contract, $payment, $isOnlinePayment = 'N')
    {

        $params = [
            'USERISN' => ($contract->agent)?$contract->agent->export_user_id:'',
            'isn' => $contract->calculation->sk_key_id,
        ];

        $result = new \stdClass();
        $result->status = false;
        $result->error = '';

        $response = $this->Send->send('products/osago/createEOsagoAgreementWithQ7', $params, 'GET');

        if(isset($response->data) && isset($response->data->error) && isset($response->data->error) && isset($response->data->error->text)){
            $result->error = $response->data->error->text;
        }else{

            if(isset($response->data) && isset($response->data->result)){

                $result->status = true;
                $result->error = '';

                if(!$contract->bso){
                    //Создаем БСО если это электронный
                    $contract->sk_contract_title = (string)$response->data->result->receiptId;

                    $bso_arr = explode('-', $contract->sk_contract_title);
                    $bso = $this->getEBSO($contract, $bso_arr[0], $bso_arr[1]);

                    $contract->bso_id = $bso->id;
                    $contract->bso_title = $bso->bso_title;
                    $contract->statys_id = 4;
                    $payment->bso_id = $bso->id;
                    $payment->save();

                }

                if(isset($response->data->result->attachments)){
                    $mask = new VernaMask();
                    $mask->setDocument($contract, $response->data->result->attachments);
                }
            }
        }

        return $result;
    }

    public function getPageLinck($contract, $payment){
        $params = [
            'partner_failure_url' => url("/clients/check/payment/{$contract->id}/{$payment->id}"),
            'partner_success_url' => url("/clients/check/payment/{$contract->id}/{$payment->id}"),
            'postbank_url' => url("/clients/check/payment/{$contract->id}/{$payment->id}"),
            'isn' => $contract->calculation->sk_key_id,
        ];

        $result = new \stdClass();
        $result->status = false;
        $result->linck = null;
        $result->error = '';


        $response = $this->Send->send('getInvoicePaymentUrl', $params, 'GET');
        //dd($response);

        if(isset($response->data) && isset($response->data->result) && isset($response->data->result->url)){
            $result->status = true;
            $result->linck = urldecode($response->data->result->url);
        }else{
            $result->error = (string)$response->data->error->text;
        }

        return $result;


    }


    public function checkPageLinck($invoice, $is_debag = 0)
    {

        if($invoice->status_id == 1){
            $params = ['isn'=>$invoice->payment_linck_id];
            $response = $this->Send->send('getInvoiceInfo', $params, 'GET');

            if($is_debag == 1){
                dd($response);
            }


            if(isset($response->data) && isset($response->data->result) && isset($response->data->result->statusIsn)){
                if((int)$response->data->result->statusIsn == 2517 && $response->data->result->statusName == 'Оплачен'){
                    $mask = new VernaMask();

                    foreach ($invoice->payments as $payment){
                        $contract = $payment->contract;


                        $contract->sk_contract_id = (int)$response->data->result->refAgrIsn;

                        if(!$contract->bso){
                            //Создаем БСО если это электронный
                            $contract->sk_contract_title = (string)$response->data->result->refAgrId;

                            $bso_arr = explode('-', $contract->sk_contract_title);
                            $bso = $this->getEBSO($contract, $bso_arr[0], $bso_arr[1]);

                            $contract->bso_id = $bso->id;
                            $contract->bso_title = $bso->bso_title;
                            $contract->statys_id = 4;
                            $payment->bso_id = $bso->id;
                            $payment->save();

                        }


                        $contract->save();

                        //События БСО 12 Оплачен BsoLog
                        $payment->setBsoLogToPayment(12);



                    }

                    InvoiceAutomatic::closeInvoice($invoice, getDateTime(), getFloatFormat($response->data->result->amount), true);

                    return true;
                }
            }
        }

        return false;
    }

    public function getInvc($isn)
    {
        $params = ['isn'=>$isn];
        $response = $this->Send->send("invc", $params, 'GET');

        dd($response);
    }

    private function getEBSO($contract, $bso_serie, $bso_number)
    {
        $user = $contract->agent;
        //Базовые данные
        $type_bso_id = 1;
        $insurance_companies_id = 1;
        $bso_class_id = 1;
        $product_id = $contract->product_id;
        $bso_count_number = 9;

        $bso_title = "{$bso_serie} {$bso_number}";

        $serie = BsoSerie::where('insurance_companies_id', $insurance_companies_id)
            ->where('bso_class_id', $bso_class_id)
            ->where('product_id', $product_id)
            ->where('type_bso_id', $type_bso_id)
            ->where('bso_serie', $bso_serie)->get()->first();

        if(!$serie){
            $serie = BsoSerie::create([
                'type_bso_id' => $type_bso_id,
                'bso_class_id' => $bso_class_id,
                'insurance_companies_id' => $insurance_companies_id,
                'product_id' => $product_id,
                'bso_serie' => $bso_serie,
                'bso_count_number' => $bso_count_number,
            ]);
        }


        $dop_serie = null;
        $agent = $user;
        $organization = $user->organization;
        if($organization->parent_user) $agent = $organization->parent_user;

        $bso_supplier = BsoSuppliers::findOrFail(1);

        $bso = BsoItem::where('bso_supplier_id', $bso_supplier->id)
            ->where('bso_serie_id', $serie->id)
            ->where('bso_number', $bso_number);

        $bso = $bso->get()->first();
        if($bso){
            return $bso;
        }

        $bso_serie = $serie;


        //Создаем БСО и вешаем на Агента
        $bso = BsoItem::create([
            'bso_supplier_id' => $bso_supplier->id,
            'org_id' => $bso_supplier->id,
            'insurance_companies_id' => $bso_supplier->insurance_companies_id,
            'bso_class_id' => $bso_serie->bso_class_id,
            'type_bso_id' => $bso_serie->type_bso_id,
            'product_id' => $bso_serie->product_id,
            'bso_serie_id' => $bso_serie->id,
            'bso_number' => $bso_number,
            'bso_title' => $bso_title,
            'location_id' => 1,
            'state_id' => 2,
            'user_id' => $agent->id,
            'agent_id' => $agent->id,
            'agent_organization_id' => $agent->organization->id,
            'user_org_id' => $agent->organization->id,
            'time_create' => date('Y-m-d H:i:s'),
            'time_target' => date('Y-m-d H:i:s'),
            'last_operation_time' => date('Y-m-d H:i:s'),
            'transfer_to_agent_time' => date('Y-m-d H:i:s'),
            'transfer_to_org_time' => date('Y-m-d H:i:s'),
            'bso_manager_id' => (int)auth()->id(),
            'point_sale_id' => (int)$agent->point_sale_id,
            'bso_dop_serie_id' => ($dop_serie?$dop_serie->id:0),
        ]);



        BsoLogs::setLogs(
            $bso->id,
            $bso->state_id,
            $bso->location_id,
            0,
            0,
            0,
            0,
            0,
            0
        );

        return $bso;

    }


    public function checkAndGetBSO($contract, $bso_serie, $bso_number)
    {
        $result = new \stdClass();
        $result->state = false;
        $result->msg = 'Ошибка БСО!';
        $result->bso = null;


        $params = ['bso_series'=>$bso_serie->bso_serie, 'bso_number'=>$bso_number];
        $response = $this->Send->send("checkBSO", $params, 'POST');
        if(isset($response->data) && isset($response->data->error) && isset($response->data->error->row) && isset($response->data->error->row->text)){
            $result->msg = $response->data->error->row->text;
        }

        if(isset($response->data) && isset($response->data->result) && isset($response->data->result->bso) && isset($response->data->result->bso->number)){

            if((int)$response->data->result->bso->state_id == 2506){
                $bso_number = $response->data->result->bso->number;
                $bso_supplier = BsoSuppliers::findOrFail(1);

                $bso = BsoItem::where('bso_supplier_id', $bso_supplier->id)
                    ->where('bso_serie_id', $bso_serie->id)
                    ->where('bso_number', $bso_number);

                $bso = $bso->get()->first();
                if($bso){
                    $result->state = true;
                    $result->msg = '';
                    $result->bso = $bso;
                    return $result;
                }

                $agent = $contract->agent;

                $bso_title = "{$bso_serie->bso_serie} {$bso_number}";

                //Создаем БСО и вешаем на Агента
                $bso = BsoItem::create([
                    'bso_supplier_id' => $bso_supplier->id,
                    'org_id' => $bso_supplier->id,
                    'insurance_companies_id' => $bso_supplier->insurance_companies_id,
                    'bso_class_id' => $bso_serie->bso_class_id,
                    'type_bso_id' => $bso_serie->type_bso_id,
                    'product_id' => $bso_serie->product_id,
                    'bso_serie_id' => $bso_serie->id,
                    'bso_number' => $bso_number,
                    'bso_title' => $bso_title,
                    'location_id' => 1,
                    'state_id' => 2,
                    'user_id' => $agent->id,
                    'agent_id' => $agent->id,
                    'agent_organization_id' => $agent->organization->id,
                    'user_org_id' => $agent->organization->id,
                    'time_create' => date('Y-m-d H:i:s'),
                    'time_target' => date('Y-m-d H:i:s'),
                    'last_operation_time' => date('Y-m-d H:i:s'),
                    'transfer_to_agent_time' => date('Y-m-d H:i:s'),
                    'transfer_to_org_time' => date('Y-m-d H:i:s'),
                    'bso_manager_id' => (int)auth()->id(),
                    'point_sale_id' => (int)$agent->point_sale_id,
                ]);



                BsoLogs::setLogs(
                    $bso->id,
                    $bso->state_id,
                    $bso->location_id,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0
                );


                $result->state = true;
                $result->msg = '';
                $result->bso = $bso;

            }
        }

        return $result;
    }


    public function setPaymentKIAS($contract, $payment)
    {
        if($payment->is_export > 0){
            $InvoiceFromId = $payment->is_export;
        }else{
            $InvoiceFromId = $this->createInvoiceFromAgreement($contract, $payment);
        }


        $isOnlinePayment = "Y";
        $isPayFormCashDesk = "N";
        if((int)$payment->payment_type == 0){
            $isOnlinePayment = "N";
            $isPayFormCashDesk = "Y";
        }


        if($InvoiceFromId){
            $params = [
                'agreementIsn' => $contract->calculation->sk_key_id,
                'amount' => $payment->payment_total,
                'invoiceIsn' => $InvoiceFromId,
                'isOnlinePayment' => $isOnlinePayment,
                'isPayFormCashDesk' => $isPayFormCashDesk,
                'newAgrStatus' => '',
                'userIsn' => $contract->agent->export_user_id,
            ];


            $response = $this->Send->send('savePaymentInfo', $params, 'POST');
            //dump($response);

            if(isset($response->data) && isset($response->data->result) && isset($response->data->result)){
                $params = [
                    'isn' => $contract->calculation->sk_key_id,
                    'name' => 'status',
                    'value' => '1539101',
                ];
                $response = $this->Send->send('agent/agreementInfo/state', $params, 'POST');
                //dump($response);
                if($response){
                    return true;
                }

            }else{
                $calculation = $contract->calculation;
                if(isset($response->data) && isset($response->data->error) && isset($response->data->error->row) && isset($response->data->error->row->text)){
                    $calculation->messages = $response->data->error->row->text;
                    $calculation->save();
                }
            }
        }

        return false;
    }


    public function createInvoiceFromAgreement($contract, $payment)
    {
        $contract = Contracts::find($contract->id);
        $calculation = $contract->calculation;

        $params = [
            'agreementIsn' => $calculation->sk_key_id,
            'amount' => $payment->payment_total,
            'userIsn' => $contract->agent->export_user_id,
        ];


        $response = $this->Send->send('createInvoiceFromAgreement', $params, 'GET');
        //dump($response);
        if(isset($response->data) && isset($response->data->result) && isset($response->data->result->isn)){
            $payment->is_export = $response->data->result->isn;
            $payment->save();
            return $response->data->result->isn;
        }else{
            if(isset($response->data) && isset($response->data->error) && isset($response->data->error->row) && isset($response->data->error->row->text)){
                $calculation->messages = $response->data->error->row->text;
                $calculation->save();
            }

        }

        return null;
    }

}