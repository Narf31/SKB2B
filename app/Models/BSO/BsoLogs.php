<?php

namespace App\Models\BSO;

use App\Classes\Notification\BsoLogNotifier;
use App\Classes\Notification\NotificationManager;
use App\Models\Contracts\Payments;
use App\Models\File;
use App\Models\Security\Security;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Log;

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
class BsoLogs extends Model
{
    protected $table = 'bso_logs';

    protected $guarded = ['id'];

    public $timestamps = false;

    const NOTIFIER = BsoLogNotifier::class;

    protected static function boot(){



        parent::boot();
    }

    public function act(){
        return $this->hasOne(BsoActs::class, 'id', 'bso_act_id');
    }

    public function bso_location(){
        return $this->hasOne(BsoLocations::class, 'id', 'bso_location_id');
    }

    public function bso_state(){
        return $this->hasOne(BsoState::class, 'id', 'bso_state_id');
    }

    public function bso(){
        return $this->hasOne(BsoItem::class, 'id', 'bso_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function user_from(){
        return $this->hasOne(User::class, 'id', 'bso_user_from');
    }

    public function user_to(){
        return $this->hasOne(User::class, 'id', 'bso_user_to');
    }

    public function payment(){
        return $this->hasOne(Payments::class, 'id', 'bso_id');
    }



    public static function setLogs($bso_id, $bso_state_id, $bso_location_id, $bso_act_id = 0, $bso_user_from = 0, $bso_user_to = 0, $contract_id = 0, $reports_act_id = 0, $postpone = 0)
    {

        $user_id = $bso_user_to;
        if(auth()->id()){
            $user_id = auth()->id();
        }


        $bso_log = BsoLogs::create([
            'bso_id' => $bso_id,
            'bso_state_id' => $bso_state_id,
            'bso_location_id' => $bso_location_id,
            'bso_act_id' => $bso_act_id,
            'bso_user_from' => $bso_user_from,
            'bso_user_to' => $bso_user_to,
            'contract_id' => $contract_id,
            'user_id' => $user_id,
            'reports_act_id' => $reports_act_id,
            'log_time' => date("Y-m-d H:i:s", time()+$postpone),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
        ]);


        return true;
    }

}
