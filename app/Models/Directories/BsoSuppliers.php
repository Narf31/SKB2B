<?php

namespace App\Models\Directories;

use App\Models\Acts\ReportAct;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoType;
use App\Models\Contracts\Payments;
use App\Models\File;
use App\Models\Finance\Invoice;
use App\Models\Organizations\Organization;
use App\Models\Reports\ReportOrders;
use App\Models\Security\Security;
use App\Models\Settings\City;
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
class BsoSuppliers extends Model
{
    protected $table = 'bso_suppliers';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function hold_kv()
    {
        return $this->hasMany(HoldKv::class, 'bso_supplier_id');
    }

    public function hold_kv_product($product_id)
    {
        return $this->hasMany(HoldKv::class, 'bso_supplier_id')->where('product_id', $product_id)->get()->first();
    }

    public function financial_policy()
    {
        return $this->hasMany(FinancialPolicy::class, 'bso_supplier_id');
    }

    public function type_bso()
    {
        return $this->hasMany(BsoType::class, 'insurance_companies_id')
            ->where('is_actual', '=', 1)
            ->where('title', '!=', '');
    }

    public function bso_items(){
        return $this->hasMany(BsoItem::class, 'bso_supplier_id');
    }

    public function bso_items_to_sk_acts(){
        return $this->bso_items()->whereIn('state_id', [0,1,3,4,5])->whereIn('location_id', [0,4]);

    }

    public function invoices(){
        return $this->hasMany(Invoice::class, 'supplier_id');
    }

    public function purpose_org(){
        return $this->hasOne(Organization::class, 'id', 'purpose_org_id');
    }

    public function insurance(){
        return $this->hasOne(InsuranceCompanies::class, 'id', 'insurance_companies_id');
    }

    public function city(){
        return $this->hasOne(City::class, 'id', 'city_id');
    }




    public function reports_acts(){
        return $this->hasMany(ReportAct::class, 'bso_supplier_id', 'id');
    }

    public function reports_border(){
        return $this->reports()->where('type_id', '=', 0);
    }

    public function reports_dvoy(){
        return $this->reports()->where('type_id', '=', 1);
    }

    public function reports(){
        return $this->hasMany(ReportOrders::class, 'bso_supplier_id', 'id')->orderBy('id', 'desc');
    }







    public static function getFilials()
    {
        $bso_supplier = null;

        $user = auth()->user();
        $rolesVisibility = $user->role->rolesVisibility(4);

        if ($rolesVisibility) {

            $bso_supplier = BsoSuppliers::query();
            $bso_supplier->where('is_actual', 1);


            $visibility = $rolesVisibility->visibility;
            if ($visibility == 0) {//Все


            } elseif ($visibility == 1) {//Все в рамках организации
                $bso_supplier->where('purpose_org_id', $user->organization_id);

            }else{
                $bso_supplier = null;
            }


        }

        return $bso_supplier;
    }



}
