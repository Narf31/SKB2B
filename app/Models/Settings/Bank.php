<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $is_actual
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Bank whereIsActual($value)
 */
class Bank extends Model
{
    protected $table = 'banks';

    protected $guarded = ['id'];

    public $timestamps = false;

    const CURRENCY = [
        0 => 'RUR',
        1 => 'USD',
        2 => 'EUR',
    ];



    public static function getSearchBankId($name)
    {
        $bank = Bank::where('title', $name)->get()->first();
        if(!$bank){
            $bank = Bank::create([
                'title' => $name,
                'is_actual' => 1,
            ]);
        }

        return $bank->id;
    }

}
