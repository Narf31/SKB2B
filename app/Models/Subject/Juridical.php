<?php

namespace App\Models\Subject;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Subject\Juridical
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $ogrn
 * @property string $inn
 * @property string $bik
 * @property string $bank
 * @property string $rs Расчётный счёт
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Juridical whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Juridical whereOgrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Juridical whereInn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Juridical whereBik($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Juridical whereBank($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Juridical whereRs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Juridical whereTitle($value)
 */
class Juridical extends Model
{
    protected $table = 'subjects_juridical';

    protected $guarded = ['id'];

    public $timestamps = false;

    public static function getFields()
    {
        return [
            'title',
            'ogrn',
            'inn',
            'bik',
            'bank',
            'rs',
        ];
    }

}
