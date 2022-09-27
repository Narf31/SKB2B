<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DebtScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('statys_id', '=', 0)->where('type_id', '=', 0);
    }

}