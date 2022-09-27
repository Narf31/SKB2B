<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\Bank;
use App\Models\Settings\InstallmentAlgorithmsPayment;
use App\Models\Settings\InstallmentAlgorithmsPaymentList;
use Illuminate\Http\Request;

class InstallmentAlgorithmsPaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,installment_algorithms_payment');

        $this->breadcrumbs[] = [
            'label' => 'Справочники',
        ];


    }

    public function index()
    {
        return view('settings.installment_algorithms_payment.index', [
            'algorithms' => InstallmentAlgorithmsPayment::orderBy('quantity')->get()
        ])->with('breadcrumbs', $this->breadcrumbs);
    }



    public function edit($id)
    {

        $this->breadcrumbs[] = [
            'label' => 'Алгоритмы рассрочки',
            'url' => 'settings/installment_algorithms_payment',
        ];

        if((int)$id > 0){
            $algorithm = InstallmentAlgorithmsPayment::findOrFail($id);
        }else{
            $algorithm = new InstallmentAlgorithmsPayment();
        }

        return view('settings.installment_algorithms_payment.edit', [
            'algorithm' => $algorithm
        ])->with('breadcrumbs', $this->breadcrumbs);
    }



    public function save($id, Request $request)
    {

        if((int)$id > 0){
            $algorithm = InstallmentAlgorithmsPayment::findOrFail($id);
        }else{
            $algorithm = new InstallmentAlgorithmsPayment();
            $algorithm->save();
        }

        $algorithm->title = $request->title;

        InstallmentAlgorithmsPaymentList::where('algorithms_payment_id', $algorithm->id)->delete();

        if (isset($request->algorithms_payment)) {

            $algorithms_payment = $request->get("algorithms_payment");
            $algorithms_month = $request->get("algorithms_month");

            $algorithm_list = InstallmentAlgorithmsPaymentList::createValue($algorithm->id, $algorithms_payment, $algorithms_month);

            $algorithm->details_quantity = $algorithm_list->title;
            $algorithm->quantity = (int)$algorithm_list->quantity;

        }

        if(isset($request->is_default) && (int)$request->is_default == 1){
            InstallmentAlgorithmsPayment::query()->update(['is_default'=>0]);
            $algorithm->is_default = 1;
        }


        $algorithm->save();

        return redirect("/settings/installment_algorithms_payment/{$algorithm->id}/edit/");
    }

    public function destroy($id)
    {


        InstallmentAlgorithmsPaymentList::where('algorithms_payment_id', $id)->delete();
        InstallmentAlgorithmsPayment::findOrFail($id)->delete();

        return response('', 200);
    }

}
