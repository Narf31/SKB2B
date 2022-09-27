<?php

namespace App\Http\Controllers\Analitics\Total;


use App\Http\Controllers\Controller;
use DB;



class TotalAnalyticsController extends Controller{

    protected $havings;

    public function index(){
        return view('analitics.total.index');
    }

    public function get_filters(){
        return view('analitics.total.filters');
    }

    public function get_charts(){

        $this->validate(request(), [
            "period" => "integer",
            "year" => "integer",
            "month" => "integer",
            "from" => "string",
            "to" => "string",
        ]);

        $this->havings = $this->get_havings();

        //$this->havings uts > 1585688399 AND uts < 1588280400

        $incomes_by_uts = collect($this->get_incomes_data_by_uts());
        $expenses_by_uts = collect($this->get_expenses_data_by_uts());
        $payments = collect($this->get_payments_data());

        $all = collect([])->merge($incomes_by_uts)->merge($expenses_by_uts)->merge($payments)->sortBy('uts');

        $from_time = $all->first() ? $all->first()->uts : time();
        $to_time = $all->last() ? $all->last()->uts : time();

        $incomes_by_category = collect($this->get_incomes_data_by_category())->keyBy('category_title');
        $expenses_by_category = collect($this->get_expenses_data_by_category())->keyBy('category_title');

        $colors = ['#30d0d8', 'orange', '#6699FF', '#307d30','#305afb',  'purple',  '#6633CC',  '#FFEB3B',  'red',  '#35d832',       '#339999', '#99FF66', '#999933','#FF9999',];

        $datasets = [
            'income_kv' => ['fill'=>false, 'backgroundColor' => $colors[1], 'borderColor' => $colors[1], 'label' => 'Оборот', 'data' => []],

            'expense_borderau' => ['fill'=>false, 'backgroundColor' => $colors[3], 'borderColor' => $colors[3], 'label' => 'Бордеро', 'data' => []],
            'expense_dvou' => ['fill'=>false, 'backgroundColor' => $colors[2], 'borderColor' => $colors[2], 'label' => 'ДВОУ', 'data' => []],
            'common_expense' => ['fill'=>false, 'backgroundColor' => $colors[8], 'borderColor' => $colors[8], 'label' => 'Убытки', 'data' => []],
            'profit' => ['fill'=>false, 'backgroundColor' => $colors[9], 'borderColor' => $colors[9], 'label' => 'Прибыль', 'data' => []],
        ];

        $checkpoints = $this->get_checkpoints($from_time, $to_time);
        $line_labels = $this->get_labels($checkpoints);

        foreach($checkpoints as $k => $time){

            if($k == 0){
                $datasets['income_kv']['data'][] = 0;

                $datasets['expense_borderau']['data'][] = 0;
                $datasets['expense_dvou']['data'][] = 0;
                $datasets['common_expense']['data'][] = 0;
                $datasets['profit'][] = 0;
                continue;
            }

            $prev_time = $checkpoints[$k-1];

            $period_incomes = $incomes_by_uts->filter(function($item) use ($time, $prev_time){
                return $item->uts > $prev_time && $item->uts <= $time;
            });

            $period_expenses = $expenses_by_uts->filter(function($item) use ($time, $prev_time){
                return $item->uts > $prev_time && $item->uts <= $time;
            });

            $period_payments = $payments->filter(function($item) use ($time, $prev_time){
                return $item->uts > $prev_time && $item->uts <= $time;
            });


            $period_income_kv = $period_payments->first() ? $period_payments->first()->income_kv : 0;
            $period_expense_dvou = $period_payments->first() ? $period_payments->first()->expense_dvou : 0;
            $period_expense_borderau = $period_payments->first() ? $period_payments->first()->expense_bordereau : 0;
            $period_common_expense = 0;
            $period_profit = $period_income_kv - ($period_expense_dvou+$period_expense_borderau); //$period_common_expense;

            $datasets['income_kv']['data'][] = $period_income_kv;
            $datasets['expense_dvou']['data'][] = $period_expense_dvou;
            $datasets['expense_borderau']['data'][] = $period_expense_borderau;
            $datasets['common_expense']['data'][] = $period_common_expense;
            $datasets['profit']['data'][] = $period_profit;

        }

        $dataset_income_category = [
            'data' => $incomes_by_category->pluck('income'),
            'backgroundColor' => array_slice($colors, 0, $incomes_by_category->count())
        ];

        $dataset_expense_category = [
            'data' => $expenses_by_category->pluck('expense'),
            'backgroundColor' => array_slice($colors, 0, $expenses_by_category->count())
        ];

        $charts = [
            'income' => [
                'type'    => 'doughnut',
                'data'    => [
                    'labels' => $incomes_by_category->keys(),
                    'datasets' => [
                        $dataset_income_category
                    ],
                ],
                'options' => [
                    'elements' => [
                        'center' => [
                            'text'        => getPriceFormat($incomes_by_category->sum('income')),
                            'color'       => '#36A2EB',
                            'fontStyle'   => 'Helvetica',
                            'sidePadding' => 15
                        ],
                    ],
                ],
            ],
            'expense' => [
                'type'    => 'doughnut',
                'data'    => [
                    'labels' => $expenses_by_category->keys(),
                    'datasets' => [
                        $dataset_expense_category
                    ]
                ],
                'options' => [
                    'elements' => [
                        'center' => [
                            'text'        => getPriceFormat($expenses_by_category->sum('expense')),
                            'color'       => '#36A2EB',
                            'fontStyle'   => 'Helvetica',
                            'sidePadding' => 15
                        ],
                    ],
                ],
            ],
            'turn' => [
                'type' => 'line',
                'data' => [
                    'labels' => $line_labels,
                    'datasets' => [
                        $datasets['income_kv'],
                        $datasets['expense_borderau'],
                        $datasets['expense_dvou'],
                        $datasets['common_expense'],

                    ]
                ]
            ],

        ];

        $result = [];
        foreach($datasets as $dataset_name => $dataset){
            $result[] = [
                'title' => $dataset['label'],
                'color' => $dataset['backgroundColor'],
                'total' => array_sum($dataset['data'])
            ];
        }


        return response()->json([
            'charts' => $charts,
            'result' => $result
        ]);
    }





    protected function get_incomes_data_by_category(){

        $unix_timestamp_sql = getUnixTimestampSQL('date');
        $sql = "select sum(ie.sum) as income, iec.title as category_title, {$unix_timestamp_sql} as uts
        from incomes_expenses ie
        join incomes_expenses_categories iec on (iec.id = ie.category_id)
        where iec.type=1 and ie.status_id=2
        group by category_title, uts
        having {$this->get_havings($unix_timestamp_sql)}";

        return DB::select($sql);
    }

    protected function get_expenses_data_by_category(){

        $unix_timestamp_sql = getUnixTimestampSQL('date');
        $sql = "select sum(ie.sum) as expense, iec.title as category_title, {$unix_timestamp_sql} as uts 
        from incomes_expenses ie
        join incomes_expenses_categories iec on (iec.id = ie.category_id)
        where iec.type=2 and ie.status_id=2
        group by category_title, uts
        having {$this->get_havings($unix_timestamp_sql)}";

        return DB::select($sql);
    }


    protected function get_incomes_data_by_uts(){

        $unix_timestamp_sql = getUnixTimestampSQL('date');
        $sql = "select sum(ie.sum) as income, {$unix_timestamp_sql} as uts
        from incomes_expenses ie
        join incomes_expenses_categories iec on (iec.id = ie.category_id)
        where iec.type=1 and ie.status_id=2
        group by uts
        having {$this->get_havings($unix_timestamp_sql)}
        order by {$unix_timestamp_sql}";

        return DB::select($sql);
    }

    protected function get_expenses_data_by_uts(){

        $unix_timestamp_sql = getUnixTimestampSQL('date');
        $sql = "select sum(ie.sum) as expense,  {$unix_timestamp_sql} as uts
        from incomes_expenses ie
        join incomes_expenses_categories iec on (iec.id = ie.category_id)
        where iec.type=2 and ie.status_id=2
        group by uts
        having {$this->get_havings($unix_timestamp_sql)}
        order by {$unix_timestamp_sql}";

        return DB::select($sql);
    }


    protected function get_payments_data(){

        $unix_timestamp_sql = getUnixTimestampSQL('payment_data');
        $sql = "select {$unix_timestamp_sql} as uts,
        sum(financial_policy_kv_parent_total) as kv_parent,
        sum(financial_policy_kv_dvoy_total) as expense_dvou,
        sum(financial_policy_kv_bordereau_total) as expense_bordereau,
        sum(payment_total) as income_kv
        from payments
        where statys_id = 1
        group by uts
        having {$this->get_havings($unix_timestamp_sql)}
        order by {$unix_timestamp_sql}";

        return DB::select($sql);
    }

    protected function get_checkpoints($from_time=false, $to_time=false, $count = 27){

        $month = str_pad((int)request()->get('month'), 2, '0', STR_PAD_LEFT);
        $year = (int)request()->get('year');

        $from = request()->get('from');
        $to = request()->get('to');

        $time_checkpoints = [];

        if(request('period') == 3){
            $from_time = strtotime($from);
            $to_time = strtotime($to);
        }

        switch(request('period')){

            case 0: //month

                $first_day_month_date = "{$year}-{$month}-01";
                $last_day_in_month = date('t', strtotime($first_day_month_date));
                $month_day_arr = range(1, $last_day_in_month);
                $time_checkpoints = array_map(function($_day) use ($year, $month){
                    return strtotime("{$year}-{$month}-{$_day} 00:00:00");
                }, $month_day_arr);

                $time_checkpoints[] = strtotime("{$year}-{$month}-{$last_day_in_month} 23:59:59");
                break;

            case 1: //year

                $first_year_date = "{$year}-01-01";
                $last_day_in_month = date('t', strtotime($first_year_date));
                $year_month_arr = range(1,12);
                $time_checkpoints = array_map(function($_month) use ($year){
                    $_month = str_pad($_month, 2, '0', STR_PAD_LEFT);
                    return strtotime("{$year}-{$_month}-01 00:00:00");
                }, $year_month_arr);
                $time_checkpoints[] = strtotime("{$year}-12-{$last_day_in_month} 23:59:59");

                break;

            case 2: //all
            case 3: //period

                $one_day_sec = 60*60*24;
                $diff = $to_time - $from_time;
                $diff = $diff > 0 ? $diff : $one_day_sec;

                $diff_days = $diff / $one_day_sec;
                $diff_days = $diff_days > 0 ? $diff_days : 1;

                $count++;
                $count = $diff_days < $count ? $diff_days : $count;

                $time_checkpoints = array_map('ceil', range($from_time, $to_time, $diff / $count));

                break;

        }

        $time_checkpoints = array_map('intval', $time_checkpoints);

        return $time_checkpoints;
    }


    protected function get_havings($uts = 'uts'){

        $month = str_pad((int)request()->get('month'), 2, '0', STR_PAD_LEFT);
        $year = (int)request()->get('year');
        $from = request()->get('from');
        $to = request()->get('to');

        $havings = [];

        switch(request('period')){

            case 0: //month
                $first_time = strtotime("{$year}-{$month}-01 00:00:00");
                $last_day_in_month = date('t', $first_time);
                $last_time = strtotime("{$year}-{$month}-{$last_day_in_month} 23:59:59");

                $first_time--;
                $last_time++;

                $havings[] = "{$uts} > {$first_time}";
                $havings[] = "{$uts} < {$last_time}";

                break;

            case 1: //year
                $first_time = strtotime("{$year}-01-01 00:00:00");
                $last_day_in_month = date('t', $first_time);
                $last_time = strtotime("{$year}-12-{$last_day_in_month} 23:59:59");

                $first_time--;
                $last_time++;

                $havings[] = "{$uts} > {$first_time}";
                $havings[] = "{$uts} < {$last_time}";
                break;

            case 2:
                $havings[] = "1=1";
                break;
            case 3: //period
                $first_time = strtotime($from);
                $last_time = strtotime($to);

                $first_time--;
                $last_time++;

                $havings[] = "{$uts} > {$first_time}";
                $havings[] = "{$uts} < {$last_time}";
                break;
        }

        return implode(" AND ", $havings);
    }

    private function get_labels($checkpoints){

        $labels = [];

        switch(request('period')){

            case 0: // month
            case 2: // all
            case 3: // period

                $labels = array_unique(array_map(function($item){
                    return date('d.m.Y', $item);
                }, $checkpoints));

                break;

            case 1: //year

                $labels = array_values(getRuMonthes());

                break;

        }

        return $labels;
    }

}