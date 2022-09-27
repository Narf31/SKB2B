<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 12.12.18
 * Time: 12:14
 */

namespace App\Classes\Export\Replacers;

use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Facades\Excel;


class ExcelReplacer
{
    public static function replace($file_path, $replace_arr){

        $replace_arr = self::replace_arr_wrap($replace_arr);
        $flat = self::get_flat($replace_arr);
        $lists = self::get_lists($replace_arr);

        $excel = Excel::load($file_path, function ($reader) use ($flat, $lists) {
            foreach ($reader->excel->getAllSheets() as $list) {
                $reader->sheet($list->getTitle(), function ($sheet) use ($flat, $lists) {


                    $sheet_flat_cells = self::get_array_cells($sheet, true);
                    $sheet_cells = self::get_array_cells($sheet);

                    $sheet_header_footer =  $sheet->getHeaderFooter();
                    $sheet_header_footer_values = [
                        'OddFooter' => $sheet_header_footer->getOddFooter(),
                        'OddHeader' => $sheet_header_footer->getOddHeader(),
                        'EvenFooter' => $sheet_header_footer->getEvenFooter(),
                        'EvenHeader' => $sheet_header_footer->getEvenHeader(),
                        'FirstFooter' => $sheet_header_footer->getFirstFooter(),
                        'FirstHeader' => $sheet_header_footer->getFirstHeader(),
                    ];

                    foreach ($sheet_header_footer_values as $key => $hf){
                        $sheet_header_footer_values[$key] = str_replace(array_keys($flat), $flat, $hf);
                    }

                    $sheet_header_footer->setOddFooter($sheet_header_footer_values['OddFooter']);
                    $sheet_header_footer->setOddHeader($sheet_header_footer_values['OddHeader']);
                    $sheet_header_footer->setEvenFooter($sheet_header_footer_values['EvenFooter']);
                    $sheet_header_footer->setEvenHeader($sheet_header_footer_values['EvenHeader']);
                    $sheet_header_footer->setFirstFooter($sheet_header_footer_values['FirstFooter']);
                    $sheet_header_footer->setFirstHeader($sheet_header_footer_values['FirstHeader']);

                    foreach ($lists as $list_key => $vl){

                        foreach($sheet_flat_cells as $cell_coord => $cell_val){
                            if (stristr($cell_val, $list_key)) {

                                $coord = self::get_row_and_col($cell_coord);
                                $list_row = $sheet_cells[$coord['row']];
                                $list_row[$coord['col']] = null;

                                $new_rows = [];
                                if (is_array($lists[$cell_val]) && count($lists[$cell_val]) > 0) {
                                    foreach ($lists[$cell_val] as $row_number => $row_replace_arr) {
                                        $new_row = $list_row;
                                        foreach ($new_row as $col => $val) {
                                            $val = str_replace(array_keys($row_replace_arr), $row_replace_arr, $val);
                                            $new_row[$col] = $val;
                                        }
                                        $new_rows[] = $new_row;
                                    }
                                }



                                if (is_array($new_rows) && count($new_rows) > 0) {

                                    foreach ($new_rows as $k => $row) {

                                        $row_num = $coord['row'] + $k;


                                        if($k > 0){
                                            $sheet->prependRow($row_num, function ($row) {});
                                        }

                                        foreach ($row as $col => $val) {
                                            $sheet->setCellValue("{$col}{$row_num}", $val);
                                        }
                                    }
                                }
                            }
                        }
                    }


                    foreach($sheet->getCellCollection() as $cell_coord){
                        if($sheet->getCellCacheController()->getCacheData($cell_coord)->getDataType() == 's'){
                            $old_val = $sheet->getCellCacheController()->getCacheData($cell_coord)->getValue();
                            $new_val = str_replace(array_keys($flat), $flat, $old_val);
                            $sheet->setCellValue($cell_coord, $new_val);
                        }
                    }

                });
            }
        });


        return $excel;
    }


    private static function get_flat($replace_arr){
        if(is_array($replace_arr) && count($replace_arr)>0){
            foreach ($replace_arr as $key => $val){
                if(is_array($val)){
                   unset($replace_arr[$key]);
                }
            }
        }
        return $replace_arr;
    }

    private static function get_lists($replace_arr){
        if(is_array($replace_arr) && count($replace_arr)>0){
            foreach ($replace_arr as $key => $val){
                if(!is_array($val)){
                    unset($replace_arr[$key]);
                }
            }
        }
        return $replace_arr;

    }


    private static function get_array_cells(LaravelExcelWorksheet $sheet, $one_level = false)
    {
        $sheet_cells = [];

        foreach ($sheet->getCellCollection() as $cell_coord) {

            $coord = self::get_row_and_col($cell_coord);
            if($one_level){
                $sheet_cells["{$coord['col']}{$coord['row']}"] = $sheet->getCellCacheController()->getCacheData($cell_coord)->getValue();
            }else{
                $sheet_cells[$coord['row']][$coord['col']] = $sheet->getCellCacheController()->getCacheData($cell_coord)->getValue();
            }

        }

        return $sheet_cells;
    }

    private static function get_row_and_col($cell_coord)
    {
        preg_match('/[0-9]{1,3}/', $cell_coord, $row_match);
        preg_match('/[A-Z]{1,2}/', $cell_coord, $col_match);

        $row = $row_match[0];
        $col = $col_match[0];

        return [
            'row' => $row,
            'col' => $col,
        ];
    }


    private static function replace_arr_wrap($replace_arr){
        $old_replace_arr = $replace_arr;
        $replace_arr = [];
        if(is_array($old_replace_arr) && count($old_replace_arr)>0){
            foreach($old_replace_arr as $k => $v){
                if(is_array($v) && count($v)>0){
                    $_arr = [];
                    foreach ($v as $_k => $_v){
                        if(is_array($_v) && count($_v)>0){
                            $_v_new = [];
                            foreach ($_v as $__k => $__v){
                                $_v_new['${'.$__k.'}'] = $__v;
                            }
                            $_arr[] = $_v_new;
                        }else{
                            $_arr[] = $_v;
                        }
                    }
                    $replace_arr['${'.$k.'}'] = $_arr;
                }else{
                    $replace_arr['${'.$k.'}'] = $v;
                }
            }
        }

        return $replace_arr;
    }


}