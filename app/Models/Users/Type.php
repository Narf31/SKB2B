<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users\Type
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Type whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Type whereTitle($value)
 * @mixin \Eloquent
 */
class Type extends Model
{
    const MANAGER = 1;
    const DRIVER = 2;
    const WORKER = 3;

    protected $table = 'users_types';

    protected $guarded = ['id'];

    public $timestamps = false;
}
