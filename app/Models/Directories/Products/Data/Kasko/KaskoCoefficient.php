<?php

namespace App\Models\Directories\Products\Data\Kasko;

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
class KaskoCoefficient extends Model
{
    protected $table = 'kasko_coefficient';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function getTitleTerms(){
        $txt = '';
        $group = Coefficients::getCoefficientsGroupDefault($this->category, $this->group);



        if(isset($group['is_adjacent']) && (int)$group['is_adjacent'] == 1){

            $coefficients = Coefficients::getCoefficientsAllToCategoryDefault($this->category);

            $json = $this->json;
            if(strlen($json) > 0){
                $json = json_decode($json, true);

                foreach ($group['control']['data'] as $control){
                    if(isset($json[$control]) && isset($json[$control])){

                        if($coefficients[$control]['control']['type'] == 'select'){
                            if(isset($coefficients[$control]['control']['value'][$json[$control]['value']])){
                                $txt .= $coefficients[$control]['title'].' - '.$coefficients[$control]['control']['value'][$json[$control]['value']].'; ';
                            }
                        }

                        if($coefficients[$control]['control']['type'] == 'range'){
                            $txt .= $coefficients[$control]['title']." - с ".$json[$control]['value_to'];
                            if(strlen($json[$control]['value_from']) > 0){
                                $txt .= " по ".$json[$control]['value_from'];
                            }
                            $txt .= '; ';

                        }



                    }
                }



            }

        }else{

            $txt = $group['title'];

            if($group['control']['type'] == 'select'){
                if(isset($group['control']['value'][$this->value])){
                    $txt .= ' - '.$group['control']['value'][$this->value];
                }

            }

            if($group['control']['type'] == 'range'){
                $txt .= " - с {$this->value_to}";

                if(strlen($this->value_from) > 0){
                    $txt .= " по {$this->value_from}";
                }

            }
        }



        return $txt;
    }



}
