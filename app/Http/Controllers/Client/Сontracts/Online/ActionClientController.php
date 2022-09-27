<?php

namespace App\Http\Controllers\Client\Сontracts\Online;

use App\Domain\Process;
use App\Domain\Processes\Operations\Contracts\Online\ContractCalculationOperations;
use App\Domain\Samplers\Segments\SegmentsOnRequest;
use App\Interfaces\Services\SK\ProductServiceInterface;
use App\Models\Api\SK\ApiSetting;
use App\Models\Contracts\Driver;
use App\Models\Contracts\KbmCalculation;
use App\Models\Contracts\Subjects;
use App\Models\Directories\ProductsPrograms;
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

class ActionClientController extends Controller {

    public function __construct() {


    }


    public function subject($md5_token, Request $request) {

        $subject = new Subjects();
        $subject_name = $request->name;

        $contract = Contracts::getContractToken($md5_token);
        if($subject_name == 'insurer'){
            if($contract->insurer) $subject = $contract->insurer;
        }

        if($subject_name == 'owner'){
            if($contract->owner) $subject = $contract->owner;
        }

        if($subject_name == 'beneficiar'){
            if($contract->beneficiar) $subject = $contract->beneficiar;
        }

        $subject->type = (int)$request->type;

        $view_type = $contract->getViewStateOnline();

        $result = new \stdClass();
        $result->view_0 = view("client.contracts.default.subject.partials.{$view_type}.{$subject->type}_0", [
            'subject' => $subject,
            'subject_name' => $subject_name,
            'contract' => $contract,
        ])->render();

        $result->view_1 = view("client.contracts.default.subject.partials.{$view_type}.{$subject->type}_1", [
            'subject' => $subject,
            'subject_name' => $subject_name,
            'contract' => $contract,
        ])->render();



        return response()->json($result);

    }






}
