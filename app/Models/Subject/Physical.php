<?php

namespace App\Models\Subject;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Subject\Physical
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $first_name
 * @property string $second_name
 * @property string $middle_name
 * @property string $passport_number
 * @property string $passport_series
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Physical whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Physical whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Physical whereSecondName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Physical whereMiddleName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Physical wherePassportNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject\Physical wherePassportSeries($value)
 */
class Physical extends Model
{
    protected $table = 'subjects_physical';

    protected $guarded = ['id'];

    public $timestamps = false;

    public static function getFields()
    {
        return [
            'second_name',
            'first_name',
            'middle_name',
            'passport_series',
            'passport_number',
        ];
    }

}
