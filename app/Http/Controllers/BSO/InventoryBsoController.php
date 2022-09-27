<?php

namespace App\Http\Controllers\BSO;

use App\Http\Controllers\Controller;
use App\Models\BSO\BsoItem;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class InventoryBsoController extends Controller {

    public function __construct() {
        $this->middleware('permissions:bso,inventory_bso');
        $this->breadcrumbs[] = [
            'label' => 'Инвентаризация БСО',
            'url' => 'bso/inventory_bso'
        ];
    }

    public function index(Request $request) {
        $bso_items = $this->inventory_bso_list($request);
        return view('bso.inventory.bso', [
            'bso_items' => $bso_items,
        ]);
    }

    /**
     * TODO Fixme ------------ Вроде пофиксил, проверь как рабоатет
     * @param Request $request
     * @return array
     */
    public function inventory_bso_list(Request $request) {

        $where = [];
        if (isset($request->insurance_companies_id) && $request->insurance_companies_id != -1) {
            $where[] = "bi.insurance_companies_id = {$request->insurance_companies_id}";
        }

        if (isset($request->bso_supplier_id) && $request->bso_supplier_id != -1) {
            $where[] = "bi.org_id = {$request->bso_supplier_id}";
        }

        if (isset($request->point_sale_id) && $request->point_sale_id != -1) {
            $where[] = "bi.point_sale_id = {$request->point_sale_id}";
        }

        if (isset($request->type_bso_id) && $request->type_bso_id != -1) {
            $where[] = "bi.product_id = {$request->type_bso_id}";
        }

        $where = count($where) ? "where " . implode(' and ', $where) : "";

        $sql = "select
        bi.bso_supplier_id,
        bi.type_bso_id,
        sk.title as sk_title,
        bt.title as type_title,
        bt.min_red as min_red,
        bt.min_yellow as min_yellow
        from bso_items bi
        left join bso_suppliers sk on sk.id=bi.bso_supplier_id
        left join type_bso as bt on bt.id=bi.type_bso_id
        {$where}
        group by
        bi.bso_supplier_id,
        bi.type_bso_id,
        sk.id,
        bt.id
        order by sk_title, type_title;";


        return DB::select($sql);
    }

    public function details(Request $request) {

        $type = $request->has('types') ? $request->get('types') : 'stock';

        switch ($type) {
            case 'all';
                $title = 'Все';
                break;
            case 'stock';
                $title = 'На складе';
                break;
            case 'reserv';
                $title = 'Резерв';
                break;
            case 'agents';
                $title = 'Передано агентам';
                break;
            case 'couriers';
                $title = 'Передано курьерам';
                break;
            case 'sold';
                $title = 'Проданы';
                break;
            case 'spoiled';
                $title = 'Испорчены';
                break;
            case 'other';
                $title = 'Иные';
                break;
            case 'sk_blank';
                $title = 'Передано в СК - Чистые';
                break;
            case 'sk_sold';
                $title = 'Передано в СК - Проданы';
                break;
            case 'sk_spoiled';
                $title = 'Передано в СК - Испорчены';
                break;
            case 'sk_other';
                $title = 'Передано в СК - Иные';
                break;
            case 'on_hands';
                $title = 'Передано агентам';
                break;
            case 'bso_in';
                $title = 'Всего';
                break;
            case 'bso_in_30';
                $title = 'Всего старых (более 30 дней)';
                break;
            case 'bso_in_90';
                $title = 'Всего старых (более 90 дней)';
                break;
            default;
                $title = '';
        }



        $this->breadcrumbs[] = [
            'label' => $title,
        ];

        $data = $this->details_list($request);
        return view('bso.inventory.details', $data)->with('breadcrumbs', $this->breadcrumbs);
    }

    public function details_list(Request $request) {

        $title = '';
        $bso_items = BsoItem::query();

        $data = $request->all();

        $pageCount = 25;
        $page = 1;

        $page = isset($data['PAGE']) && (int) $data['PAGE'] > 1 ? (int) $data['PAGE'] : $page;
        if (isset($data['pageCount']) && !empty($data['pageCount'])) {
            $pageCount = ((int) $data['pageCount'] > 0 || (int) $data['pageCount'] == -1) ? (int) $data['pageCount'] : $pageCount;
        }


        if (isset($data['insurance_companies_id']) && $data['insurance_companies_id'] != -1) {
            $bso_items->where('insurance_companies_id', $data['insurance_companies_id']);
        }

        if (isset($data['bso_supplier_id']) && $data['bso_supplier_id'] != -1) {
            $bso_items->where('org_id', $data['bso_supplier_id']);
        }

        if (isset($data['point_sale_id']) && $data['point_sale_id'] != -1) {
            $bso_items->where('point_sale_id', $data['point_sale_id']);
        }

        if (isset($data['type_bso_id']) && $data['type_bso_id'] != -1) {
            $bso_items->where('type_bso_id', $data['type_bso_id']);
        }


        if (isset($data['agent_id']) && $data['agent_id'] != -1) {
            $bso_items->where('user_id', $data['agent_id']);
        }

        if (isset($data['nop_id']) && $data['nop_id'] != -1) {
            
        }

        $type = isset($data['types']) ? $data['types'] : 'stock';

        switch ($type) {
            case 'all';
                $title = 'Все';

                break;
            case 'stock';
                $title = 'На складе';
                $bso_items->where('location_id', 0)->where('state_id', 0)->where('is_reserved', 0);
                break;
            case 'reserv';
                $title = 'Резерв';
                $bso_items->where('state_id', 0)->where('is_reserved', 1);
                break;
            case 'agents';
                $title = 'Передано агентам';
                $bso_items->where('location_id', 1);
                break;
            case 'couriers';
                $title = 'Передано курьерам';
                $bso_items->where('location_id', 3);
                break;
            case 'sold';
                $title = 'Проданы';
                $bso_items->where('location_id', '!=', 2)->where('state_id', 2);
                break;
            case 'spoiled';
                $title = 'Испорчены';
                $bso_items->where('location_id', '!=', 2)->where('state_id', 3);
                break;
            case 'other';
                $title = 'Иные';
                $bso_items->where('location_id', '!=', 2)->where('state_id', '>', 3);
                break;
            case 'sk_blank';
                $title = 'Передано в СК - Чистые';
                $bso_items->where('location_id', 2)->where('state_id', 0);
                break;
            case 'sk_sold';
                $title = 'Передано в СК - Проданы';
                $bso_items->where('location_id', 2)->where('state_id', 2);
                break;
            case 'sk_spoiled';
                $title = 'Передано в СК - Испорчены';
                $bso_items->where('location_id', 2)->where('state_id', 3);
                break;
            case 'sk_other';
                $title = 'Передано в СК - Иные';
                $bso_items->where('location_id', 2)->where('state_id', '>', 3);
                break;

            case 'on_hands';
                $title = 'Передано агентам';
                $bso_items->where('location_id', 1);
                break;
            case 'bso_in';
                $title = 'Всего';
                $bso_items->where('location_id', 4);
                break;
            case 'bso_in_30';
                $title = 'Всего старых (более 30 дней)';
                $bso_items->where('location_id', 4)->where('time_create', '<', Carbon::now()->subDays(30));
                break;
            case 'bso_in_90';
                $title = 'Всего старых (более 90 дней)';
                $bso_items->where('location_id', 4)->where('time_create', '<', Carbon::now()->subDays(90));
                break;
        }

        $all_count = $bso_items->count();

        $bso_items->orderBy('id');

        if ($pageCount != -1) {
            $bso_items->offset($pageCount * ($page - 1));
            $bso_items->limit($pageCount);
        }

        $list = $bso_items->get();

        $max_row = (int) $all_count;
        $page_max = ceil($max_row / $pageCount);
        $view_row = ($pageCount * ($page));

        if (count($list) < $pageCount) {
            $view_row = ($max_row);
        }
        return [
            'result' => $list,
            'page_max' => $page_max,
            'page_sel' => $page,
            'max_row' => $max_row,
            'view_row' => $view_row,
            'title' => $title,
        ];
    }

    public function get_details_table(Request $request) {
        $data = $this->details_list($request);
        $data['html'] = view('bso.inventory.details_table', $data)->render();
        return $data;
    }

    public function inventory_bso_export(Request $request) {

        $bso_items = $this->inventory_bso_list($request);

        Excel::create(date('Y-m-d H:i:s'), function($excel) use($bso_items) {
            $excel->sheet('Лист', function($sheet) use($bso_items) {
                $sheet->setAutoSize(false);

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:H1');
                $sheet->mergeCells('I1:L1');

                $sheet->setCellValue('A1', 'Вид');
                $sheet->setCellValue('B1', 'Итого принято из СК');
                $sheet->setCellValue('C1', 'На складе');
                $sheet->setCellValue('D1', 'Резерв');
                $sheet->setCellValue('E1', 'Передано агентам');
                $sheet->setCellValue('F1', 'Реализовано');
                $sheet->setCellValue('I1', 'Передано в СК');

                $sheet->setCellValue('F2', 'Проданы');
                $sheet->setCellValue('G2', 'Испорчены');
                $sheet->setCellValue('H2', 'Иные');
                $sheet->setCellValue('I2', 'Чистые');
                $sheet->setCellValue('J2', 'Проданы');
                $sheet->setCellValue('K2', 'Испорчены');
                $sheet->setCellValue('L2', 'Иные');

                $row = 3;
                $sk_id_temp = '';
                foreach ($bso_items as $bso) {

                    if ($sk_id_temp != $bso->sk_title) {
                        $sheet->setCellValue("A{$row}", $bso->sk_title);
                        $sheet->mergeCells("A{$row}:L{$row}");
                        $row++;
                    }

                    $sheet->setCellValue("A{$row}", $bso->type_title);
                    $sheet->setCellValue("B{$row}", $bso->qty_all);
                    $sheet->setCellValue("C{$row}", $bso->qty_stock);
                    $sheet->setCellValue("D{$row}", $bso->qty_reserv);
                    $sheet->setCellValue("E{$row}", $bso->qty_agents);
                    $sheet->setCellValue("F{$row}", $bso->qty_sold);
                    $sheet->setCellValue("G{$row}", $bso->qty_spoiled);
                    $sheet->setCellValue("H{$row}", $bso->qty_other);
                    $sheet->setCellValue("I{$row}", $bso->qty_sk_blank);
                    $sheet->setCellValue("J{$row}", $bso->qty_sk_sold);
                    $sheet->setCellValue("K{$row}", $bso->qty_sk_spoiled);
                    $sheet->setCellValue("L{$row}", $bso->qty_sk_other);

                    $row++;

                    $sk_id_temp = $bso->sk_title;
                }
            });
        })->export('xls');
    }

}
