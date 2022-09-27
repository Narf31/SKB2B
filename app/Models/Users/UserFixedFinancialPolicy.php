<?php

namespace App\Models\Users;


use App\Models\Traits\FixedSalary;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users\UserFixedFinancialPolicy
 *
 * @property integer    $user_id
 * @property integer    $id
 * @property string     $begin_date
 * @property boolean    $is_actual
 * @property integer    $state_id
 * @property integer    $period_type_id
 * @property integer    $payment_type_id
 * @property float      $amount
 * @property-read mixed $amount_formatted
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\UserFixedFinancialPolicy whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\UserFixedFinancialPolicy whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\UserFixedFinancialPolicy whereBeginDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\UserFixedFinancialPolicy whereIsActual($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\UserFixedFinancialPolicy whereStateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\UserFixedFinancialPolicy wherePeriodTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\UserFixedFinancialPolicy wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\UserFixedFinancialPolicy whereAmount($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Settings\SalaryState $state
 * @property-read mixed $payment_type_title
 * @property-read mixed $period_type_title
 * @property-read mixed $department_title
 */
class UserFixedFinancialPolicy extends Model
{
    protected $table = 'users_salary_financial_policies_fixed';

    protected $guarded = ['id'];

    protected $dates = ['begin_date'];

    public $timestamps = false;

    use FixedSalary;

}
