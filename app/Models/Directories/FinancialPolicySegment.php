<?php

namespace App\Models\Directories;

use App\Models\File;
use App\Models\Security\Security;
use App\Models\Settings\City;
use App\Models\Settings\FinancialGroup;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use App\Models\Vehicle\VehicleCategories;
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
class FinancialPolicySegment extends Model
{
    protected $table = 'financial_policies_segments';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function location()
    {
        return $this->belongsTo(City::class);
    }

    public function categoryTS()
    {
        return $this->belongsTo(VehicleCategories::class, 'vehicle_category_id');
    }


    public function getTitleAttribute()
    {

        $title = $this->location->title . ' - ' . $this->categoryTS->title;
        $title .= '- Мощность ТС ';
        if((int)$this->vehicle_power_any == 1){
            $title .= 'Любая ';
        }else{
            $title .= "от $this->vehicle_power_from до $this->vehicle_power_to ";
        }

        $title .= '- Коэффициент территории ';
        if((int)$this->insurer_kt_any == 1){
            $title .= 'любой ';
        }else{
            $title .= " = $this->insurer_kt";
        }

        $title .= '- КБМ ';
        if((int)$this->kbm_any == 1){
            $title .= 'любой ';
        }else{
            $title .= " <= $this->kbm";
        }

        $title .= '- Водители ';
        if((int)$this->owner_age_any == 1){
            $title .= '- неважно ';
        }else{

            $title .= '- Мультидрайв ';
            if((int)$this->is_multi_drive_any == 1){
                $title .= 'Да ';

                if((int)$this->owner_age_any != 1){
                    $title .= "- Минимальный возраст собственника $this->owner_age";
                }

            }else {
                $title .= 'Нет ';

                if ((int)$this->owner_age_any != 1) {
                    if ((int)$this->drivers_age_any != 1) {
                        $title .= "- Минимальный возраст водителей $this->drivers_min_age";
                    }
                    if ((int)$this->drivers_exp_any != 1) {
                        $title .= "- Минимальный стаж водителей $this->drivers_min_exp";
                    }

                }
            }
        }



        return $title;
    }

}
