<?php

namespace App\Http\Controllers\BsoActs\ActsTransferTP\Details;

use App\Classes\Export\ExportManager;
use App\Classes\Export\Replacers\ExcelReplacer;
use App\Http\Controllers\Controller;

use App\Models\BSO\BsoActs;
use App\Models\Settings\ExportItem;
use App\Models\Settings\Template;
use App\Models\Settings\TemplateCategory;
use Illuminate\Http\Request;

class ActsTransferTPDetailsController extends Controller
{


    public function __construct()
    {
        $this->middleware('permissions:bso_acts,acts_transfer_tp');
    }


    public function index($act_id)
    {
        $bso_act = BsoActs::getActId($act_id);


        return view('bso_acts.acts_transfer_tp.details', [
            'act' => $bso_act
        ]);

    }


    public function export($act_id)
    {

        $builder =  BsoActs::query()->where('id', $act_id);

        $builder->firstOrFail();

        $category = TemplateCategory::get('bso_realized');

        return (new ExportManager($category, $builder))->handle();

    }





    public function delete_items($act_id, Request $request)
    {
        $item_array = \GuzzleHttp\json_decode($request->get('item_array'));
        $act = BsoActs::getActId($act_id);
        $act->deleteItemsRealizedActs($item_array);

        return response(200);
    }

    public function delete_act($act_id)
    {
        $act = BsoActs::getActId($act_id);
        $act->deleteRealizedActs();
        return response(200);
    }

    public function accept($act_id)
    {
        $act = BsoActs::getActId($act_id);
        $act->acceptRealizedActs();
        return response(200);
    }

}
