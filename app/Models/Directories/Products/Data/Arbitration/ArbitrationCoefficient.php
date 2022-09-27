<?php

namespace App\Models\Directories\Products\Data\Arbitration;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer;
use App\Models\User;
use App\Processes\Operations\Contracts\Settings\Kasco\Coefficients;
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
class ArbitrationCoefficient extends Model
{
    protected $table = 'arbitration_coefficient';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function getTitleTerms(){
        $txt = '';
        $group = Coefficients::getCoefficientsAbitrationGroupDefault($this->category, $this->group);

        $txt = $group['title'];
        if($group['control']['type'] == 'select'){
            if(isset($group['control']['value'][$this->value])){
                $txt .= ' - '.$group['control']['value'][$this->value];
            }

        }

        if($group['control']['type'] == 'range'){
            $txt .= " - с {$this->value_to} по {$this->value_from}";
        }

        return $txt;
    }



}
