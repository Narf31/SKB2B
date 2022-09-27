<?php

namespace App\Http\Controllers\BsoActs;

use App\Classes\Export\ExportManager;
use App\Classes\Export\Replacers\ExcelReplacer;
use App\Http\Controllers\Controller;
use App\Models\BSO\BsoActs;
use App\Models\Settings\ExportItem;
use App\Models\Settings\Template;
use function GuzzleHttp\Promise\queue;
use Illuminate\Http\Request;


class ShowBsoActController extends Controller
{


    public function __construct()
    {
        ///$this->middleware('permissions:bso_acts,inventory_agents');
    }


    public function index($id)
    {
        $act = BsoActs::getActId($id);
        if(!$act){
            return view('errors.403', ['exception'=>1]);
        }
        return view('bso_acts.show_bso_act.index', [
            "act" => $act,
            "bso_table" => $this->get_bso_table($id)['html'],
        ]);

    }

    public function get_bso_table($id){
        $act = BsoActs::getActId($id);
        $data['html'] = view('bso_acts.show_bso_act.table.view', [
            "act" => $act,
        ])->render();
        return $data;
    }



    public function export($id){

        $builder = BsoActs::query()->where('id', $id);
        $act = $builder->firstOrFail();

        $category = $act->type->template_category();
        return (new ExportManager($category, $builder))->handle();
    }


    public function update($id){

        $this->validate(request(), ['act_org_id' => 'integer']);

        $result = ['status'=>'error'];

        if(BsoActs::query()->findOrFail($id)->update(['act_org_id' => (int)request('act_org_id')])){
            $result['status'] = 'ok';
        }

        return response()->json($result);


    }



}
