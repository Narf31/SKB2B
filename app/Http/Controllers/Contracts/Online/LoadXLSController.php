<?php

namespace App\Http\Controllers\Contracts\Online;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsInsurer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\FilesRepository;
use Maatwebsite\Excel\Facades\Excel;

class LoadXLSController extends Controller {

    protected $storageFolder = "reports";
    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository)
    {
        $this->filesRepository = $filesRepository;
    }

    protected $columns = [
        'user_title'    => 'Фамилия и имя латиницей',
        'birth_date'    => 'Дата рождения',
        'gender'        => 'Пол',
    ];


    public function vzr($id, Request $request) {

        return view('contracts.online.load.xls.vzr', [
            'contract_id' => (int)$id,
        ]);

    }

    public function prf($id, Request $request) {

        return view('contracts.online.load.xls.prf', [
            'contract_id' => (int)$id,
        ]);

    }



    protected function getColumns($excel_columns)
    {
        $result = [];

        foreach (array_keys($this->columns) as $column) {
            if($column) $result[$column] = $excel_columns[$column];
        }

        return $result;
    }



    public function get_file($id, Request $request) {


        $file = $request->file('file_xls');

        $file->store($this->storageFolder);

        $path = $file->getRealPath();


        $data = Excel::load($path)->get();

        $keys = $data[0]->keys()->toArray();

        return response()->json([
            'columns' => $keys,
            'file'    => $file->hashName()
        ]);

    }


    public function vzr_set_data($id, Request $request) {

        $contract = Contracts::find($id);
        $storageFolder = $this->storageFolder;


        $excel_columns = $request->get('excel_columns') ?: [];
        $default = $request->get('default') ?: [];

        $filePath = storage_path("app/$storageFolder") . "/" . $request->file_name;


        if(file_exists($filePath)){

            $excel = Excel::load($filePath)->get();

            $rows = $excel;
            $columns = $this->getColumns($excel_columns);

            foreach ($rows as $key => $row){

                if($row){
                    $user_title = $default['user_title'];
                    $birth_date = setDateTimeFormat($default['birth_date']);
                    $gender = $default['gender'];
                    $gender_val = 1;//[0 => 'Мужской', 1 => 'Женский']

                    if( strlen($excel_columns['user_title'])>0 && isset($row[$excel_columns['user_title']]))
                    {
                        $user_title = $row[$excel_columns['user_title']];
                    }

                    if( strlen($excel_columns['birth_date'])>0 && isset($row[$excel_columns['birth_date']]))
                    {
                        $birth_date = setDateTimeFormat($row[$excel_columns['birth_date']]);
                    }

                    if( strlen($excel_columns['gender'])>0 && isset($row[$excel_columns['gender']]))
                    {
                        $gender = $row[$excel_columns['gender']];
                    }


                    if(stripos($gender, 'м')!== false){
                        $gender_val = 0;
                    }

                    if(stripos($gender, 'М')!== false){
                        $gender_val = 0;
                    }

                    if(stripos($gender, 'm')!== false){
                        $gender_val = 0;
                    }

                    if(stripos($gender, 'M')!== false){
                        $gender_val = 0;
                    }


                    ContractsInsurer::create([
                        'contract_id' => $contract->id,
                        'title_lat' => $user_title,
                        'birthdate' => getDateFormatEn($birth_date),
                        'sex' => $gender_val,
                        'birthyear' => (date("Y")-date("Y", strtotime($birth_date))),
                    ]);

                }

            }



        }


        \Storage::delete($this->storageFolder . "/" . $request->file_name);

        parentReload();


    }


    public function prf_set_data($id, Request $request) {

        $contract = Contracts::find($id);
        $storageFolder = $this->storageFolder;


        $excel_columns = $request->get('excel_columns') ?: [];
        $default = $request->get('default') ?: [];

        $filePath = storage_path("app/$storageFolder") . "/" . $request->file_name;


        if(file_exists($filePath)){

            $excel = Excel::load($filePath)->get();

            $rows = $excel;
            $columns = $this->getColumns($excel_columns);

            foreach ($rows as $key => $row){

                if($row){
                    $user_title = $default['user_title'];
                    $birth_date = setDateTimeFormat($default['birth_date']);
                    $gender = $default['gender'];
                    $gender_val = 1;//[0 => 'Мужской', 1 => 'Женский']

                    $citizenship_id = $default['citizenship_id'];

                    if( strlen($excel_columns['user_title'])>0 && isset($row[$excel_columns['user_title']]))
                    {
                        $user_title = $row[$excel_columns['user_title']];
                    }

                    if( strlen($excel_columns['birth_date'])>0 && isset($row[$excel_columns['birth_date']]))
                    {
                        $birth_date = setDateTimeFormat($row[$excel_columns['birth_date']]);
                    }

                    if( strlen($excel_columns['gender'])>0 && isset($row[$excel_columns['gender']]))
                    {
                        $gender = $row[$excel_columns['gender']];
                    }


                    if(stripos($gender, 'м')!== false){
                        $gender_val = 0;
                    }

                    if(stripos($gender, 'М')!== false){
                        $gender_val = 0;
                    }

                    if(stripos($gender, 'm')!== false){
                        $gender_val = 0;
                    }

                    if(stripos($gender, 'M')!== false){
                        $gender_val = 0;
                    }


                    ContractsInsurer::create([
                        'contract_id' => $contract->id,
                        'title' => $user_title,
                        'birthdate' => getDateFormatEn($birth_date),
                        'sex' => $gender_val,
                        'citizenship_id' => $citizenship_id,
                        'birthyear' => (date("Y")-date("Y", strtotime($birth_date))),
                    ]);

                }

            }



        }


        \Storage::delete($this->storageFolder . "/" . $request->file_name);

        parentReload();


    }




}
