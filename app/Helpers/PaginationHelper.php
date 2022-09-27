<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class PaginationHelper{

    public static function paginate(Builder $builder, $page, $page_count){

        $max_row = $builder->count();
        $page_max = (int)ceil($max_row / $page_count);
        $view_row = $page_count * $page;

        if ($page_count != -1) {
            $builder->skip(($page_count*($page-1)))->take(($page_count));
        }

        if ($builder->get()->count() < $page_count || $page_count == -1) {
            $view_row = $max_row;
        }


        return [
            'builder' => $builder,
            'page_max' => $page_max,
            'page_sel' => $page,
            'max_row' => $max_row,
            'view_row' => $view_row,
        ];
    }



}