<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Repositories\FilesRepository;
use App\Models\Log\LogEvents;
use App\Models\Settings\PaymentMethods;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,payment_methods');
    }

    public function index()
    {
        return view('settings.payment_methods.index', [
            'pay_methods' => PaymentMethods::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.payment_methods.create');
    }

    public function edit($id)
    {
        return view('settings.payment_methods.edit', [
            'pay_method' => PaymentMethods::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $pay_method = new PaymentMethods;
        $pay_method->save();
        LogEvents::event($pay_method->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 6, 0,0, $request->all());

        return $this->save($pay_method, $request);
    }

    public function update($id, Request $request)
    {
        $pay_method = PaymentMethods::findOrFail($id);
        LogEvents::event($pay_method->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 6, 0,0, $request->all());

        return $this->save($pay_method, $request);
    }

    private function save(PaymentMethods $pay_method, Request $request)
    {

        $pay_method->title = $request->title;
        $pay_method->is_actual = (int)$request->is_actual;

        $pay_method->payment_type = (int)$request->payment_type;
        $pay_method->payment_flow = (int)$request->payment_flow;
        $pay_method->key_type = (int)$request->key_type;
        $pay_method->control_type = (int)$request->control_type;
        $pay_method->acquiring = getFloatFormat($request->acquiring);


        if ($request->hasFile('template')) {
            $ext = $request->file('template')->getClientOriginalExtension();
            if(in_array($ext, ['xls', 'xlsx', 'docx', 'doc'])){


                $template = $pay_method->template;
                if($template){

                    $template_path = storage_path() . '/app/' . $template->getPathAttribute();
                    $template_path_host = storage_path() . '/app/' . $template->getPathWithHostAttribute();


                    $pathes = [
                        1 => is_file($template_path) ? $template_path : false,
                        2 => is_file($template_path_host) ? $template_path_host : false
                    ];

                    if($pathkey = array_search(true, $pathes)){
                        unlink($pathes[$pathkey]);
                        $template->delete();
                    }
                }


                $file = (new FilesRepository)->makeFile($request->template, PaymentMethods::TEMPLATES_FOLDER);
                $pay_method->file_id = $file->id;

            }

        }


        $pay_method->save();
        return parentReload();
    }

    public function destroy($id)
    {
        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 6);

        PaymentMethods::findOrFail($id)->delete();

        return response('', 200);
    }

}
