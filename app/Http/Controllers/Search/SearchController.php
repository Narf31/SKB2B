<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\BSO\BsoItem;
use App\Models\Clients\GeneralSubjects;
use App\Models\Contracts\Contracts;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $result = [];
        $errors = "Нет записей";
        $is_bso = 0;

        session(['search.find'=> $request->find]);

        $find = trim($request->find);

        $generals = [];

        if(strlen($find) >= 3){

            $int = (int) filter_var($find, FILTER_SANITIZE_NUMBER_INT);

            if(auth()->user()->hasPermission('bso', 'items')){
                $is_bso = 1;
            }else{
                $is_bso = 0;
            }

            if($is_bso == 1){//Поиск БСО
                $models = BsoItem::getBso();
                if($int > 0){
                    $models->where('bso_items.bso_title', 'like', "%{$request->find}%");
                }else{
                    $contracts = Contracts::where('contracts.statys_id', '>', 0);
                    $contracts->where('contracts.bso_id', '>', 0);
                    $contracts->leftJoin('subjects', 'subjects.id', '=', 'contracts.insurer_id');
                    $contracts->where('subjects.title', 'like', "%{$find}%");
                    $contracts->select("contracts.bso_id");
                    $models->whereRaw('`bso_items`.`id` IN (' . getLaravelSql($contracts) . ')');
                }

            }else{//Поиск Договорам
                $models = Contracts::getContractsQuery();
                $models->where('contracts.bso_id', '>', 0);

                if($int > 0){
                    $models->where('contracts.bso_title', 'like', "%{$request->find}%");
                }else{
                    $models->leftJoin('subjects', 'subjects.id', '=', 'contracts.insurer_id');
                    $models->where('subjects.title', 'like', "%{$find}%");
                }

                $models->select(["contracts.*"]);
            }

            $result = $models->get();


            $generals = GeneralSubjects::getAllGeneralSubjects(-1, auth()->user());
            if($int > 0){
                $generals->where('general_subjects.inn', 'like', "%{$request->find}%");
            }else{
                $generals->where('general_subjects.title', 'like', "%{$request->find}%");
            }

            $generals = $generals->get();


        }else{
            $errors = "Введите более 3 символов!";
        }


        return view('search.index', [
            'result' => $result,
            'generals' => $generals,
            'is_bso' => $is_bso,
            'errors_msg' => $errors
        ]);
    }




}
