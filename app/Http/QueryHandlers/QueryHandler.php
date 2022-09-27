<?php

namespace App\Http\QueryHandlers;



use Illuminate\Database\Eloquent\Builder;

class QueryHandler{

    public $builder;
    public $allow_empty = false;

    public function __construct(Builder $builder){
        $this->builder = &$builder;
    }

    /**
     * Сможет пропускать для фильтра пустые и нулевые значения
     * В методах фильтров в этом случае необх делать ручную проверку на 0 или empty
     * @param bool $bool
     * @return QueryHandler
     */
    public function allowEmpty(bool $bool = true){
        $this->allow_empty = $bool;
        return $this;
    }

    public function apply(){
        $request_params = request()->all();
        if(is_array($request_params) && count($request_params)>0){
            foreach($request_params as $key => $value){
                if(method_exists($this, $key) && ($this->allow_empty || !empty($value))){
                    $this->$key($value);
                }
            }
        }
        return $this->builder;
    }



}