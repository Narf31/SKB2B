<?php

namespace App\Models\Directories;

use App\Models\File;
use App\Models\Security\Security;
use App\Models\Settings\FinancialGroup;
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
class FinancialPolicy extends Model
{
    protected $table = 'financial_policies';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function groups()
    {
        return $this->belongsToMany(FinancialGroup::class, 'financial_policies_groups_kv', 'financial_policy_id', 'financial_policies_group_id')
            ->withPivot( 'kv_sk', 'kv_parent', 'is_actual', 'kv_borderau', 'kv_dvou');
    }

    public function availableGroups()
    {
        return FinancialGroup::all()->map(function ($group) {


            $kvSk                       = 0;
            $kvParent                   = 0;
            $kvBorderau                 = 0;
            $kvDvou                     = 0;
            $isActual                   = 0;

            $relation = $this->groups()->where('financial_policies_group_id', $group->id)->first();

            if ($relation) {
                $kvSk                   = $relation->pivot->kv_sk;
                $kvBorderau             = $relation->pivot->kv_borderau;
                $kvDvou                 = $relation->pivot->kv_dvou;
                $kvParent               = $relation->pivot->kv_parent;
                $isActual               = $relation->pivot->is_actual;
            }

            $group->kv_sk                    = $kvSk;
            $group->kv_parent                = $kvParent;
            $group->kv_borderau              = $kvBorderau;
            $group->kv_dvou                  = $kvDvou;
            $group->is_actual                = $isActual;


            return $group;

        });

    }


    public function product()
    {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function segments()
    {
        return $this->hasMany(FinancialPolicySegment::class,  'financial_policy_id');
    }


    public function getGroupKV($payment)
    {

        $user_id = $payment->agent_id;
        if($payment->contract->sales_condition == 1){
            $user_id = $payment->manager_id;
        }

        return $this->getKV($user_id);
    }

    public function getContractGroupKV($contract)
    {

        $user_id = $contract->agent_id;

        return $this->getKV($user_id);

    }

    public function getKV($user_id)
    {
        $user = User::find($user_id);

        $res = new \stdClass();
        $res->kv_sk = $this->kv_sk;
        $res->kv_parent = $this->kv_parent;
        $res->kv_borderau = $this->kv_bordereau;
        $res->kv_dvou = $this->kv_dvou;

        $group = $this->groups()->where('id', $user->financial_group_id)->where('financial_policies_groups_kv.is_actual', 1)->first();

        if($group){


            $res->kv_sk = $group->original['pivot_kv_sk'];
            $res->kv_parent = $group->original['pivot_kv_parent'];

            $res->kv_borderau = $group->original['pivot_kv_borderau'];
            $res->kv_dvou = $group->original['pivot_kv_dvou'];
        }
        return $res;
    }


}
