<?php

namespace App\Models\Directories\Products\Data\Supplementary;

use App\Models\Clients\GeneralSubjects;
use App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LADocuments;
use App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LAProcedures;
use App\Models\Security\Security;
use App\Models\Settings\Country;
use App\Models\Settings\Currency;
use App\Models\Settings\TypeOrg;
use App\Models\User;
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
class LiabilityArbitrationManager extends Model
{
    protected $table = 'products_supplementary_liability_arbitration_manager';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function agent()
    {
        return $this->hasOne(User::class, 'id', 'kv_agent_id');
    }

    public function agent2()
    {
        return $this->hasOne(User::class, 'id', 'kv_agent2_id');
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'kv_manager_id');
    }

    public function manager2()
    {
        return $this->hasOne(User::class, 'id', 'kv_manager2_id');
    }

    public function manager3()
    {
        return $this->hasOne(User::class, 'id', 'kv_manager3_id');
    }




}
