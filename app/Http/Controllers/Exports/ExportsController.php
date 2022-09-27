<?php

namespace App\Http\Controllers\Exports;


use App;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;


class ExportsController extends Controller{


    public function table2excel(Request $request){

        $data = $request->all();

        $data['param'] = isset($data['param']) ? $data['param'] : [];
        $data['method_param'] = isset($data['method_param']) ? $data['method_param'] : [];
        $request->merge($data['param']);
        $html = App::call("App\Http\Controllers\\".$data['method'], $data['method_param'])['html'];

        preg_match('/<table(.*?)>(.*?)<\/table>/s', $html, $mtable);
        preg_match('/<thead(.*?)>(.*?)<\/thead>/s', $mtable[0], $mhead);
        preg_match('/<tbody(.*?)>(.*?)<\/tbody>/s', $mtable[0], $mbody);

        $head = $this->getParseThToArr($mhead[0]);
        $body = $this->getParseTdToArr($mbody[0]);

        Excel::create(date('Y-m-d H:i:s'), function($excel) use ($head, $body) {
            $excel->sheet('Лист', function($sheet) use ($head, $body) {
                foreach($head as $hk => $val){
                    $sheet->setCellValueByColumnAndRow($hk, 1, $val);
                    $sheet->row(1,function($row){
                        $row->setFontWeight('bold');
                    });
                }
                foreach($body as $row_key => $row){
                    foreach($row as $cell_key => $cell){
                        $cell = html_entity_decode($cell);
                        $sheet->setCellValueByColumnAndRow($cell_key, 2 + $row_key, $cell);
                    }
                }
            });
        })->export('xlsx');

    }


    private function getParseThToArr($str){
        preg_match_all("/<th(.*?)>(.*?)<\/th>/s", $str, $out, PREG_PATTERN_ORDER);

        $row = [];
        $colspans = [];
        foreach($out[1] as $k => $attributes){
            $colspan = 1;
            if(preg_match('/colspan=\"(.*?)\"/s', $attributes, $attr_match)){
                $colspan = (int)$attr_match[1];
            }
            $colspans[$k] = $colspan;
        }
        foreach($out[2] as $k => $cell){
            $row[] = strip_tags($cell);
            $colspan = $colspans[$k];
            if($colspan>1){
                for($i=1; $i<$colspan;$i++){
                    $row[] = '';
                }
            }
        }
        return $row;
    }

    private function getParseTdToArr($str){

        preg_match_all('/<tr(.*?)>(.*?)<\/tr>/s', $str, $row_match);

        $arr = [];
        $rows = $row_match[2];
        if(is_array($rows) && count($rows)>0){
            foreach($rows as $cols){
                preg_match_all('/<td(.*?)>(.*?)<\/td>/s', $cols, $col_match);


                $colspans = [];
                foreach($col_match[1] as $k => $attributes){
                    $colspan = 1;
                    if(preg_match('/colspan=\"(.*?)\"/s', $attributes, $attr_match)){
                        $colspan = (int)$attr_match[1];
                    }
                    $colspans[$k] = $colspan;
                }


                $row = [];
                foreach($col_match[2] as $k => $cell){

                    $cell = str_replace('\n', '', $cell);
                    $cell = str_replace('  ', '', $cell);
                    $cell = str_replace(PHP_EOL, '', $cell);
                    $cell = strip_tags($cell);

                    $row[] = $cell;
                    $colspan = $colspans[$k];
                    if($colspan>1){
                        for($i=1; $i<$colspan;$i++){
                            $row[] = '';
                        }
                    }
                }
                $arr[] = $row;
            }
        }
        return $arr;
    }



    private function letterByCol($col_number){

        $letter_by_col = [
            1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H',
            9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O',
        ];

        return isset($letter_by_col[$col_number]) ? $letter_by_col[$col_number] : false;
    }


}
