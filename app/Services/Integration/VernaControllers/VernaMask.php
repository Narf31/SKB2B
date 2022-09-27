<?php

namespace App\Services\Integration\VernaControllers;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsDocuments;
use App\Models\Contracts\ContractsMasks;
use App\Models\Contracts\SubjectsFlDocType;
use App\Models\File;
use App\Models\Settings\SettingsSystem;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Models\Vehicle\VehiclePurpose;
use App\Processes\Operations\Contracts\Invoice\InvoiceAutomatic;
use App\Processes\Operations\Convertor\ConvertsDocument;
use App\Repositories\FilesRepository;
use Mockery\Exception;

class VernaMask
{

    public $Send = null;

    public function __construct()
    {
        $this->Send = new VernaSend();
    }

    public function createDocument($contract, $payment = null){

        $tempals = null;

        if($contract->product->slug == 'osago') $tempals = $this->getListPrint($contract);
        if($contract->product->slug == 'kasko') $tempals = $this->getPrintKey($payment->is_export);

        if(!$tempals) return false;

        return $this->setDocument($contract, $tempals, $payment);

    }


    public function setDocument($contract, $tempals, $payment = null){

        $contract_path = storage_path() . ContractsMasks::getPath($contract->id);
        $contract_path_noapp = str_replace('/app/', '', ContractsMasks::getPath($contract->id));

        if(!$payment){

            foreach ($contract->masks_table as $mask){
                if($mask->file){
                    if(is_file($contract_path.$mask->file->name.'.'.$mask->file->ext)){
                        unlink($contract_path.$mask->file->name.'.'.$mask->file->ext);
                    }
                }

                $mask->delete();

            }
        }



        //\Log::info(var_export($tempals, 1));


        ini_set("memory_limit",-1);

        foreach ($tempals as $templ){



            $exp = 'pdf';

            $_downloadLink = null;
            if(isset($templ->url)){
                $_downloadLink = $templ->url;
            }

            if(isset($templ->downloadLink)){
                $_downloadLink = $templ->downloadLink;
                $_f = explode('.', $templ->fileName);
                if($_f && isset($_f[1])){
                    $exp = $_f[1];
                }
            }


            $_name = null;

            if(isset($templ->docName)){
                $_name = $templ->docName;
            }

            if(isset($templ->name)){
                $_name = $templ->name;
            }


            if($_downloadLink && $_name){


                $name = date('Ymd-His')."-".uniqid();
                $newtemppath = $_name.'.'.$exp;

                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                    'http'=>array(
                        'timeout' => 1200,
                        'method'=>"GET",
                        'header'=>"Accept-language: en\r\n" .
                            "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
                            "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
                    )
                );




                if(isset($templ->fileBytesInBase64) && strlen($templ->fileBytesInBase64) > 0){
                    $result_file_data = base64_decode($templ->fileBytesInBase64);
                }else{
                    $result_file_data = file_get_contents($_downloadLink, false, stream_context_create($arrContextOptions));
                }

                file_put_contents($contract_path.$name.'.'.$exp, $result_file_data);


                if(in_array($exp, ['xlsx', 'xls'])){

                    $temppath = $contract_path.$name.'.'.$exp;
                    $newtemppath = $_name.'.pdf';
                    $exp = 'pdf';
                    if(ConvertsDocument::xlsx_to_pdf($temppath, $newtemppath) == true){
                        if(is_file($temppath)){
                            unlink($temppath);
                        }
                    }
                    

                }


                $file = File::create([
                    'original_name' => $newtemppath,
                    'ext'           => $exp,
                    'folder'        => $contract_path_noapp,
                    'name'          => $name,
                ]);

                ContractsMasks::create([
                    'file_id' =>  $file->id,
                    'contract_id' =>  $contract->id,
                    'is_payment' =>  ($payment) ? 1 : 0,
                ]);

                //$contract->masks()->save($file);
            }



        }

       // dd("OK");


        return true;
    }


    public function getListPrint($contract){

        return $this->getPrintKey($contract->calculation->sk_key_id);
    }

    public function getPrintKey($isn){

        $params = ['isn'=>$isn];
        $response = $this->Send->send("products/osago/getprint", $params, 'GET');
        //dump($response);
        if(isset($response) && isset($response->data) && isset($response->data->result) && isset($response->data->result->row)){
            foreach ($response->data->result->row as $row){
                if(isset($row)){
                    if($row->name == 'Квитация Q7'){
                        return [$row];
                    }
                }
            }
        }

        return null;
    }






}