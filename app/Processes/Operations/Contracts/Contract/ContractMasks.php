<?php

namespace App\Processes\Operations\Contracts\Contract;


use App\Classes\Export\Replacers\ExcelReplacer;
use App\Models\Contracts\ContractsDocuments;
use App\Models\Directories\Products\ProductsSpecialSsettingsFiles;
use App\Models\File;
use App\Processes\Operations\Contracts\ContractOnlineProduct;
use App\Processes\Operations\Convertor\ConvertsDocument;
use App\Services\SignSecret\Amicus;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use setasign\Fpdi\Fpdi;

class ContractMasks{


    //маска договора
    public static function contract($contract, $typeView = 1){




        if($online_product_class = ContractOnlineProduct::get_online_product_class($contract)){
            $data = $online_product_class::getPrintData($contract);

            //Создаем договор
            $template_contract = null;
            $template = null;
            $template_statement = null;

            if(isset($data) && isset($data['settings']))
            {

                $contract_path = storage_path() . ContractsDocuments::getPath($contract->id);
                $contract_path_noapp = str_replace('/app/', '', ContractsDocuments::getPath($contract->id));

                foreach ($contract->masks_table()->where('is_payment', 0)->get() as $mask){
                    if($mask->file){
                        if(is_file($contract_path.$mask->file->name.'.'.$mask->file->ext)){
                            unlink($contract_path.$mask->file->name.'.'.$mask->file->ext);
                        }
                    }

                    $mask->delete();

                }


                $settings = $data['settings'];
                if(isset($settings)){
                    if(isset($settings['template']) && (int)$settings['template'] == 1) $template = $contract->product->template;
                    if(isset($settings['template']) && (int)$settings['template_contract'] == 1) $template_contract = $contract->product->template_contract;
                    if(isset($settings['template']) && (int)$settings['template_statement'] == 1) $template_statement = $contract->product->template_statement;
                }

                $data = $data['info'];


                if(isset($settings['templates']) && sizeof($settings['templates'])){
                    foreach ($settings['templates'] as $temp){

                        self::createDocument($contract, $temp['path'], $data, $temp['title'], $contract->statys_id == 4 ? 1 : 0, $contract->statys_id == 4 ? 1 : 0,$temp['info']);
                    }

                }
            }

            //Создаем договор
            if($template_contract){
                self::createDocument($contract, $template_contract, $data, "Договор", $typeView, 1);
            }

            if($template){
                self::createDocument($contract, $template, $data, "Маска договора", $typeView, 0);
            }

            if($template_statement){
                self::createDocument($contract, $template_statement, $data, 'Заявление', $typeView, 0);
            }


            //Прекрепляем дополнительные файлы к договору
            if($typeView == 1){

                if(isset($contract->program)){
                    $special_settings = $contract->program->special_settings;
                }else {
                    $special_settings = $contract->product->special_settings;
                }

                if($special_settings) {
                    $special_settings_files = ProductsSpecialSsettingsFiles::where('special_settings_id',$special_settings->id)
                        ->whereNotIn('type_name',['contract','policy','agreement'])
                        ->get();
                    foreach ($special_settings_files as $data) {
                        $special_file = $special_settings->files->where('id',$data->file_id)->first();
                        $original_name = $special_file->original_name;
                        $original_name = explode('.', $original_name);
                        $original_name = $original_name[0];
                        self::createDocument($contract, $special_file, $data, $original_name, $typeView, 0);

                    }
                }

            }


        }




        return true;
    }


    //Документ счета
    public static function invoices($invoice){

        $pay_method = $invoice->payment_method;
        $template = $pay_method->template;

        if($template)
        {
            $payment = $invoice->payments->first();
            $contract = $payment->contract;
            $insurer = $payment->contract->insurer;

            $insurer_title = $insurer->title;
            if($insurer->type == 1){
                $insurer_data = $insurer->data();
                $insurer_title = "{$insurer_data->title_full} , ИНН {$insurer_data->inn}, КПП {$insurer_data->kpp}, {$insurer_data->address_register}, тел.: {$insurer->phone}";
            }


            $data = [
                'policy_number' => $contract->bso_title,
                'insurer_title' => $insurer_title,
                'invoices_number' => $invoice->id,
                'invoices_date' => setDateTimeFormatRu($invoice->invoice_payment_date,1),
                'payment_date' => setDateTimeFormatRu($payment->payment_data, 1),
                'invoice_payment_total' => titleFloatFormat($invoice->invoice_payment_total),
                'invoice_payment_total_text' => num2str($invoice->invoice_payment_total),
                'num' => '1',
                'description' => (isset($payment->contract) && isset($payment->contract->product) && $payment->contract->product->slug == 'osago') ?
                    "Оплата страховой премии по договору обязательного страхования автогражданской ответственности владельцев транспортных средств №{$payment->bso->bso_title}" :
                    "Страховой взнос по счету {$invoice->id} от ".setDateTimeFormatRu($invoice->invoice_payment_date,1)."г. На основании полиса  {$payment->bso->bso_title}.  НДС не облагается.",
                'payment_total' => titleFloatFormat($payment->invoice_payment_total),
                'insurer' => isset($insurer) && $insurer->type == 0 ?
                    "{$insurer->title}, {$insurer->doc_serie} {$insurer->doc_number} от {$insurer->data()->doc_date}, тел: {$insurer->phone}, Email: {$insurer->email}":
                    "{$insurer->title}, ИНН {$insurer->inn}, ОГРН {$insurer->ogrn}",
            ];

            $table = [];
            foreach ($invoice->payments as $key => $payment){
                $table[] = ['num' => ($key+1), 'bso_title' => $payment->bso->bso_title, 'payment_total' => titleFloatFormat($payment->payment_total)];
            }



            //в зависимости от хоста файлы хранятся в разных папках, поэтому предполагаем - или там или там
            $template_path = storage_path() . '/app/' . $template->getPathAttribute();
            $template_path_host = storage_path() . '/app/' . $template->getPathWithHostAttribute();

            //проверяем где по факту файл
            $pathes = [
                is_file($template_path) ? $template_path : false,
                is_file($template_path_host) ? $template_path_host : false
            ];

            //ни в одном месте - ошибку
            if(($pathkey = array_search(true, $pathes)) === false){

            }else{
                $path = $pathes[$pathkey];
            }


            $contract_path = storage_path() . ContractsDocuments::getPath($contract->id);
            $contract_path_noapp = str_replace('/app/', '', ContractsDocuments::getPath($contract->id));


            foreach ($contract->masks_table()->where('is_payment', 0)->get() as $mask){
                if($mask->file){
                    if(is_file($contract_path.$mask->file->name.'.'.$mask->file->ext)){
                        unlink($contract_path.$mask->file->name.'.'.$mask->file->ext);
                    }
                }

                $mask->delete();

            }


            $name = date('Ymd-His')."-".uniqid();
            $newtemppath = $contract_path.$name.'.pdf';


            //реплейсим и отдаем
            if(in_array($template->ext, ['xlsx', 'xls'])){

                $temppath = $contract_path.$name.'.'.$template->ext;

                $excel = ExcelReplacer::replace($path, $data);
                $excel->setFilename($name);
                $excel->store($template->ext, $contract_path);

                if(ConvertsDocument::xlsx_to_pdf($temppath, $newtemppath) == true){

                    if(is_file($temppath)){
                        unlink($temppath);
                    }

                }else{
                    $newtemppath = '';
                }

                if(is_file($temppath)){
                    unlink($temppath);
                }

            }elseif(in_array($template->ext, ['docx', 'doc'])){

                $temppath = $contract_path.$name.'.'.$template->ext;

                $my_template = new \PhpOffice\PhpWord\TemplateProcessor($path);
                $tmpArr = $my_template->getVariables();  // создаем массив с используемыми переменными
                foreach ($data as $key => $value) {
                    if(!is_array($value)){
                        $my_template->setValue($key, $value);
                    }else if (count($value) > 0) {

                        $rows_arr = self::get_as_rows($value);

                        if(array_search(array_key_first($rows_arr[0]), $tmpArr) !== false){ // проверяем на присутствие в шаблоне переменной списка
                            $my_template->cloneRow(array_key_first($rows_arr[0]), sizeof($rows_arr));//если cloneRowAndSetValues не работает
                            foreach ($rows_arr as $n => $d){
                                foreach ($d as $k => $v){
                                    $my_template->setValue($k.'#'.($n+1), $v);
                                }
                            }
                        }

                    }
                }

                $my_template->saveAs($temppath);

                if(ConvertsDocument::word_to_pdf($temppath, $newtemppath) == true){

                    if(is_file($temppath)){
                        unlink($temppath);
                    }

                }else{
                    $newtemppath = '';
                }


            }


            if(strlen($newtemppath) > 3){
                //Переводим в PDF
                //$newtemppath
                if(is_file($newtemppath)){
                    self::saveFilePdf($contract, $newtemppath, $contract_path_noapp, $name, "Счет", 1, 0);
                }

            }



        }

        return true;
    }

    //Документ платежа
    public static function payment($payment){


        return true;
    }


    public static function createDocument($contract, $template, $data, $title_name, $typeView, $is_signature = 0){

        $contract_path = storage_path() . ContractsDocuments::getPath($contract->id);
        $contract_path_noapp = str_replace('/app/', '', ContractsDocuments::getPath($contract->id));


        if($template && $data){

            //в зависимости от хоста файлы хранятся в разных папках, поэтому предполагаем - или там или там
            $template_path = storage_path() . '/app/' . $template->getPathAttribute();
            $template_path_host = storage_path() . '/app/' . $template->getPathWithHostAttribute();

            //проверяем где по факту файл
            $pathes = [
                is_file($template_path) ? $template_path : false,
                is_file($template_path_host) ? $template_path_host : false
            ];

            //ни в одном месте - ошибку
            if(($pathkey = array_search(true, $pathes)) === false){

            }else{
                $path = $pathes[$pathkey];
            }

            $name = date('Ymd-His')."-".uniqid();
            $newtemppath = $contract_path.$name.'.pdf';


            //реплейсим и отдаем
            if(in_array($template->ext, ['xlsx', 'xls'])){

                $temppath = $contract_path.$name.'.'.$template->ext;

                $excel = ExcelReplacer::replace($path, $data);
                $excel->setFilename($name);
                $excel->store($template->ext, $contract_path);

                //$contents = file_get_contents($temppath);
                //$contents = base64_encode($contents);

                if(ConvertsDocument::xlsx_to_pdf($temppath, $newtemppath) == true){

                    if(is_file($temppath)){
                        unlink($temppath);
                    }

                }else{
                    $newtemppath = '';
                }

                if(is_file($temppath)){
                    unlink($temppath);
                }

            }elseif(in_array($template->ext, ['docx', 'doc'])){

                $temppath = $contract_path.$name.'.'.$template->ext;

                $my_template = new \PhpOffice\PhpWord\TemplateProcessor($path);
                $tmpArr = $my_template->getVariables();  // создаем массив с используемыми переменными
                foreach ($data as $key => $value) {
                    if(!is_array($value)){
                        $my_template->setValue($key, $value);
                    }else if (count($value) > 0) {

                        $rows_arr = self::get_as_rows($value);

                        if(array_search(array_key_first($rows_arr[0]), $tmpArr) !== false){ // проверяем на присутствие в шаблоне переменной списка
                            //$my_template->cloneRowAndSetValues(array_key_first($rows_arr[0]), $rows_arr);
                            $my_template->cloneRow(array_key_first($rows_arr[0]), sizeof($rows_arr));//если cloneRowAndSetValues не работает
                            foreach ($rows_arr as $n => $d){
                                foreach ($d as $k => $v){
                                    $my_template->setValue($k.'#'.($n+1), $v);
                                }
                            }
                        }

                    }
                }

                $my_template->saveAs($temppath);

                if(ConvertsDocument::word_to_pdf($temppath, $newtemppath) == true){

                    if(is_file($temppath)){
                        unlink($temppath);
                    }

                }else{
                    $newtemppath = '';
                }


            }


            if(strlen($newtemppath) > 3){
                //Переводим в PDF

                //$newtemppath
                if(is_file($newtemppath)){
                    self::saveFilePdf($contract, $newtemppath, $contract_path_noapp, $name, $title_name, $typeView, $is_signature);
                }

            }

        }

        return true;
    }

    private static function get_flat($array)
    {
        if (is_array($array) && count($array) > 0) {
            foreach($array as $key => $value) {
                if (is_array($value)) {
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }

    private static function get_as_rows($array)
    {
        $rows_arr = [];
        foreach($array as $key => $value) {
            $rows_arr[] = self::get_flat($value);
        }

        return $rows_arr;
    }

    public static function saveFilePdf($contract, $newtemppath, $contract_path_noapp, $name, $title_name, $typeView, $is_signature = 0){


        $pdf = new Fpdi();
        $pages_count = $pdf->setSourceFile($newtemppath);
        for($i=1;$i<=$pages_count;$i++){
            $pdf->AddPage();
            $tplIdx = $pdf->importPage($i);
            $pdf->useTemplate($tplIdx, 0, 0);

            if($typeView == 0){
                $sample = public_path('sample/print/')."pngwing.com.png";
                $pdf->Image($sample, 0, 20, 200,'','PNG');
            }else{

                if($is_signature == 1){

                    $print_p_object = new \stdClass();
                    $print_p_object->template_print = '';
                    $print_p_object->template_print_x = 0;
                    $print_p_object->template_print_y = 0;

                    if(strlen($contract->product->template_print) > 0){


                        $p_print = public_path('sample/print/')."{$contract->product->template_print}.png";
                        if(is_file($p_print)){
                            $print_p_object->template_print = $p_print;
                            $print_p_object->template_print_x = $contract->product->template_print_x;
                            $print_p_object->template_print_y = $contract->product->template_print_y;
                        }


                    }else{

                        if($contract->program){
                            if($contract->program->special_settings){
                                $info= ProductsSpecialSsettingsFiles::where('special_settings_id',$contract->program->special_settings->id)->get();
                                $template_print = $info->firstWhere('type_name',"contract");
                                if($template_print && strlen($template_print->template_print) > 0){
                                    $p_print = public_path('sample/print/')."{$template_print->template_print}.png";
                                    if(is_file($p_print)){
                                        $print_p_object->template_print = $p_print;
                                        $print_p_object->template_print_x = $template_print->template_print_x;
                                        $print_p_object->template_print_y = $template_print->template_print_y;
                                    }

                                }
                            }
                        }

                    }



                    if(strlen($print_p_object->template_print) > 0){
                        if($i == 1){
                            $pdf->Image($print_p_object->template_print, $print_p_object->template_print_x, $print_p_object->template_print_y, 65,'','PNG');
                            // $pdf->Image($riks_print, 117, 245, 65,'','PNG');
                        }
                    }




                }


                if($title_name == 'Счет'){
                    $p_print = public_path('sample/print/')."tit_print.png";
                    $pdf->Image($p_print, 40, 143, 65,'','PNG');

                }

            }
        }

        if(is_file($newtemppath)){
            unlink($newtemppath);
        }

        $pdf->Output('F', $newtemppath);


        //$title_name

        if($typeView == 1 && $is_signature == 1){
            $_path = str_replace($name.'.pdf','', $newtemppath);
            Amicus::signFile($_path, $name.'.pdf');
        }
        //

        $file = File::create([
            'original_name' => $title_name.'.pdf',
            'ext'           => 'pdf',
            'folder'        => $contract_path_noapp,
            'name'          => $name,
        ]);

        $contract->masks()->save($file);
        return true;

    }


    //маска договора
    public static function contractTest($contract, $typeView = 1){


        if($online_product_class = ContractOnlineProduct::get_online_product_class($contract)) {
            $data = $online_product_class::getPrintData($contract);

            //Прекрепляем дополнительные файлы к договору
            if($typeView == 1){

                $special_settings = $contract->product->special_settings;
                if($special_settings) {
                    foreach ($special_settings->files as $special_file) {


                    }
                }

            }
        }

    }




}