<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies\InstallmentAlgorithms;

use App\Http\Controllers\Controller;
use App\Models\Directories\InstallmentAlgorithms;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Log\LogEvents;
use Illuminate\Http\Request;

class InstallmentAlgorithmsController extends Controller
{

    public function __construct()
    {

    }



    public function edit($id, $algorithm_id)
    {

        $sk = InsuranceCompanies::where('id', $id)->get()->first();

        if((int)$algorithm_id > 0){
            $algorithm = InstallmentAlgorithms::where('insurance_companies_id', $id)->where('id', $algorithm_id)->get()->first();
        }else{
            $algorithm = new InstallmentAlgorithms();
        }

        return view('directories.insurance_companies.installment_algorithms.edit', [
            'insurance_companies' => $sk,
            'algorithm' => $algorithm
        ]);

    }


    public function save($id, $algorithm_id, Request $request)
    {
        $sk = InsuranceCompanies::where('id', $id)->get()->first();

        if((int)$algorithm_id > 0){
            $algorithm = InstallmentAlgorithms::where('insurance_companies_id', $id)->where('id', $algorithm_id)->get()->first();
            LogEvents::event($algorithm->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE].' '.InstallmentAlgorithms::ALG_TYPE[$request->algorithm_id], 12, $id, 0, $request->all());

        }else{
            $algorithm = new InstallmentAlgorithms();
            $algorithm->insurance_companies_id = $id;
            LogEvents::event($algorithm->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE].' '.InstallmentAlgorithms::ALG_TYPE[$request->algorithm_id], 12, $id, 0, $request->all());

        }



        $algorithm->algorithm_id = $request->algorithm_id;
        $algorithm->save();

        return parentReload();

    }


}
