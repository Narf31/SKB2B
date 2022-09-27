<?php

namespace App\Http\Controllers\Client\Damages;

use App\Http\Controllers\Controller;
use App\Models\Orders\Damages;
use App\Models\Settings\PointsSale;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;

class ActionsController extends Controller
{

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository) {

        $this->filesRepository = $filesRepository;
    }


    public function get_point_sale(Request $request)
    {

        $point_sale = PointsSale::where('is_actual', 1)
            ->where('city_id', $request->city_id)
            ->orderBy('title', 'asc');

        if($request->type == 1){
            $point_sale->where('is_damages', 1);
        }


        return response()->json($point_sale->get());

    }


    public function scan_damages($order_id, Request $request) {

        Damages::findOrFail($order_id)->scans()->save($this->filesRepository->makeFile($request->file, Damages::getPath($order_id)));

        return response('', 200);
    }


}
