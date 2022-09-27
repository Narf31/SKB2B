<?php

namespace App\Models\Contracts;

use App\Models\Settings\Country;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $next_act
 * @property string $default_purpose_payment
 * @property string $inn
 * @property float $limit_year
 * @property float $spent_limit_year
 * @property integer $is_actual
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereNextAct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereDefaultPurposePayment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereInn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereSpentLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereIsActual($value)
 */
class SubjectsFlDocType extends Model
{
    protected $table = 'subjects_fl_doc_type';

    protected $guarded = ['id'];

    public $timestamps = false;


    public static function getDocType()
    {
        $result = self::query()
            ->orderBy('title', 'asc')
            ->get();

        return $result;
    }

    public static function getDocTypeTitle($isn)
    {
        $title = '';
        $result = self::where('isn', $isn)
            ->orderBy('title', 'asc')
            ->get()->first();

        if($result){
            $title = $result->title;
        }

        return $title;
    }


}
