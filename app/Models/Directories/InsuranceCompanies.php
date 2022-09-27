<?php

namespace App\Models\Directories;

use App\Models\File;
use App\Models\Security\Security;
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
class InsuranceCompanies extends Model
{
    protected $table = 'insurance_companies';

    protected $guarded = ['id'];

    public $timestamps = false;

    const FILES_FOLDER = 'insurance_companies/logo';
    const TEMPLATES_FOLDER = 'insurance_companies/template';

    public function logo()
    {
        return $this->hasOne(File::class, 'id', 'logo_id');
    }

    public function algorithms()
    {
        return $this->hasMany(InstallmentAlgorithms::class, 'insurance_companies_id');
    }

    public function type_bso()
    {
        return $this->hasMany(TypeBso::class, 'insurance_companies_id');
    }


    public function bso_suppliers()
    {
        return $this->hasMany(BsoSuppliers::class, 'insurance_companies_id');
    }


    /*
     * REPORTS
     *
     */


    public function getPaymentsTotal(){
        $payment_total = 0;

        foreach($this->bso_suppliers as $supplier){
            $payment_total += $supplier->getPaymentsTotal();
        }

        return $payment_total;
    }


    public function getPaymentsTotalKV($report_type){
        $sum_kv = 0;
        foreach($this->bso_suppliers as $supplier){
            $sum_kv += $supplier->getPaymentsTotalKV($report_type);
        }
        return $sum_kv;
    }


}
