<?php

namespace App\Http\Controllers\Contracts\Online;

use App\Domain\Process;
use App\Domain\Processes\Operations\Contracts\Online\ContractCalculationOperations;
use App\Domain\Samplers\Segments\SegmentsOnRequest;
use App\Interfaces\Services\SK\ProductServiceInterface;
use App\Models\Api\SK\ApiSetting;
use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\Driver;
use App\Models\Contracts\KbmCalculation;
use App\Models\Contracts\Subjects;
use App\Models\Directories\ProductsPrograms;
use App\Processes\Operations\Contracts\Contract\ContractMatching;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use App\Services\Kbm\Alfa\Calc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Directories\TypeBso;
use App\Models\Directories\ProductsCategory;
use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\Contracts;
use Illuminate\Support\Str;
use Mockery\Exception;

class MatchingController extends Controller {

    public function __construct() {


    }


    public function create($id, Request $request)
    {

        $contract = Contracts::getContractId($id);
        if($class = $contract->product->get_control_view($contract, $request)){
            $result = $class->getValidDataToMatching($contract);
            if($result->state == false){
                return back()->withInput()->withErrors($result->errors);
            }
        }

        $contract = Contracts::getContractId($id);
        $contract = ContractMatching::sendMatching($contract, auth()->user());
        ContractsLogs::setContractLogs($contract->id, auth()->id(), $contract->statys_id, 'Отправлен на согласования');
        return redirect(url("/contracts/online/{$id}/"));
    }

    public function createSupplementary($id, $number, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $supplementary = $contract->getSupplementaryNumber($number);

        $result = ContractMatching::sendSupplementaryMatching($contract, $supplementary, auth()->user());
        if($result->state == false){
            return back()->withInput()->withErrors($result->errors);
        }

        return redirect(url("/contracts/online/{$id}/"));
    }







}
