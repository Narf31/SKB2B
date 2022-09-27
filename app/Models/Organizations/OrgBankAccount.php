<?php

namespace App\Models\Organizations;

use App\Models\Settings\Bank;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $org_id
 * @property string $account_number
 * @property integer $bank_id
 * @property float $non_cash
 * @property string $bik
 * @property integer $is_actual
 * @property-read \App\Models\Settings\Bank $bank
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\OrgBankAccount whereOrgId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\OrgBankAccount whereAccountNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\OrgBankAccount whereBankId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\OrgBankAccount whereNonCash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\OrgBankAccount whereBik($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\OrgBankAccount whereIsActual($value)
 */
class OrgBankAccount extends Model
{
    protected $table = 'org_bank_account';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
