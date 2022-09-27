<?php

namespace App\Models\Organizations;

use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicyGroup;
use App\Models\Directories\Products;
use App\Models\File;
use App\Models\Reports\ReportOrders;
use App\Models\Reports\ReportPayments;
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
class Organization extends Model
{

    const FILES_DOC = 'organizations/docs';

    const ASSOCIATION_TYPE = [
        0 => 'Нет',
        1 => 'Ассоциация',
        2 => 'Член Ассоциации',
        3 => 'Партнер ассоциации',
    ];

    protected $table = 'organizations';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function bso_supplier(){
        return $this->hasMany(BsoSuppliers::class, 'purpose_org_id', 'id');
    }

    public function scans()
    {
        return $this->belongsToMany(File::class, 'org_scans', 'org_id', 'file_id');
    }

    public function bank_account()
    {
        return $this->hasMany(OrgBankAccount::class, 'org_id');
    }

    public function org_type() {
        return $this->hasOne(TypeOrg::class, 'id', 'org_type_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    public function settings_role_district()
    {
        return $this->hasMany(SupervisionSettingsRoleDistrict::class, 'org_id');
    }

    public function settings_role_district_area()
    {
        return $this->hasMany(SupervisionSettingsRoleDistrictArea::class, 'org_id');
    }

    public function financial_group() {
        return $this->hasOne(FinancialGroup::class, 'id', 'financial_group_id');
    }

    public static function main_company() {
        return Organization::where('is_main_company' , '=', '1')
            ->where('org_type_id' , '=', '1')
            ->get()->first();
    }

    public function security_service() {
        return $this->hasOne(Security::class, 'object_id')->where('type_inquiry' , '=', Security::TYPE_INQUIRY_ORG)->orderBy('created_at', 'desc');
    }

    public function parent_user() {
        return $this->hasOne(User::class, 'id','parent_user_id');
    }

    public function curator(){
        return $this->hasOne(User::class, 'id', 'curator_id');
    }


    public function reports_border(){
        return $this->reports()->where('type_id', '=', 0);
    }

    public function reports_dvoy(){
        return $this->reports()->where('type_id', '=', 1);
    }

    public function reports(){
        return $this->hasMany(ReportOrders::class, 'agent_organization_id', 'id')->orderBy('id', 'desc');
    }




    public function getOrgParentUser($user_id){
        return $this::where('parent_user_id', $user_id);
    }

    public function getListOrg($type = 0){
        return $this::getOrganizationPermissions($type)->get()->pluck('title', 'id')->prepend('Нет', 0);
    }

    public function getOrganizationPermissions($type = 0){
        $org = Organization::where('is_actual', 1);//getALLOrg();
        $org->where('is_delete', 0);
        if($type>0 && $type != 100) return $org->where('org_type_id', $type);
        elseif($type==100) return Organization::where('org_type_id', 0);
        else  return $org;

    }

    public function types()
    {
        return $this->hasOne(TypeOrg::class, 'id', "org_type_id");
    }

    public function cutTitle()
    {
        $title = $this->title;
        if(mb_strlen($title) > 40){
            $title = mb_substr($title,0,40)."...";
        }
        return $title;

    }

    public static function getOrgProvider(){
        $org = Organization::getALLOrg();
        $org->where('is_actual', '=', '1')
            ->whereIn('org_type_id', function($query)
        {
            $query->select(\DB::raw('type_org.id'))
                ->from('type_org')
                ->where('type_org.is_provider', 1);

        });
        return $org;
    }

    public static function getOrgIsContract(){
        $org = Organization::getALLOrg();
        $org->where('is_actual', '=', '1')
            ->whereIn('org_type_id', function($query)
            {
                $query->select(\DB::raw('type_org.id'))
                    ->from('type_org')
                    ->where('type_org.is_contract', 1);

            });
        return $org;
    }


    public static function getIdOrg($id){
        $org = Organization::getALLOrg();
        $org->where('id', $id);
        return $org->get()->last();
    }

    public static function getALLOrg($org_type_id = 0){

        $organization = Organization::query();
        $organization->where('is_delete', 0);

        $user = auth()->user();


        $visibility_obj = $user->role->rolesVisibility(2)->visibility;
        if($visibility_obj == 0){

        }elseif ($visibility_obj == 1){

            $organization->where('id', $user->organization->id);


        }elseif ($visibility_obj == 2){
            $organization->where('parent_user_id', auth()->id());
        }elseif ($visibility_obj == 3){


            $organization->where(function($query){
                $query->whereIn('parent_user_id', function($query)
                {

                    $query->select(\DB::raw('users.id'))
                        ->from('users')
                        ->where('id', '=', auth()->id())
                        ->orWhere('parent_id', '=', auth()->id())
                        ->orWhere('curator_id', '=', auth()->id());

                });
                $query->orWhereIn('curator_id', function($query)
                {

                    $query->select(\DB::raw('users.id'))
                        ->from('users')
                        ->where('id', '=', auth()->id())
                        ->orWhere('parent_id', '=', auth()->id())
                        ->orWhere('curator_id', '=', auth()->id());

                });

            });

        }

        if($org_type_id > 0){
            $organization->where('org_type_id', $org_type_id);
        }

        return $organization;
    }


    public function saveData($request)
    {
        $organization = $this;


        if(isset($request->payment_type_agent)){
            $organization->payment_type_agent         = $request->payment_type_agent;
            $organization->api_key                    = $request->api_key;
            $organization->secret_key                 = $request->secret_key;

        }else{
            if(auth()->user()->hasPermission('directories', 'organizations_edit') || auth()->user()->id == $organization->parent_user_id){
                $organization->fact_address            = $request->fact_address;
                $organization->phone                   = $request->phone;
                $organization->email                   = $request->email;
                $organization->comment                 = $request->comment;
                $organization->user_contact_title      = $request->user_contact_title;
            }

            if(auth()->user()->hasPermission('directories', 'organizations_edit')){

                $organization->is_actual               = (int)$request->is_actual;
                $organization->is_main_company         = (int)$request->is_main_company;
                $organization->org_type_id             = (int)$request->org_type_id;
                $organization->title                   = $request->title;

                $organization->inn                     = $request->inn;
                $organization->kpp                     = $request->kpp;
                $organization->title_doc               = $request->title_doc;
                $organization->general_manager         = $request->general_manager;
                $organization->address                 = $request->address;

                $organization->points_sale_id          = $request->points_sale_id;


                $organization->parent_org_id               = (int)$request->parent_org_id;
                $organization->parent_user_id              = (int)$request->parent_user_id;


                $organization->agent_contract_title = $request->agent_contract_title;
                $organization->agent_contract_begin_date = setDateTimeFormat($request->agent_contract_begin_date);
                $organization->agent_contract_end_date = setDateTimeFormat($request->agent_contract_end_date);

                $organization->ban_level = (int) $request->ban_level;
                $organization->ban_reason = $request->ban_reason;

                $organization->curator_id = ((int)$request->curator_id > 0) ? (int) $request->curator_id : auth()->id();
                $organization->financial_group_id = (int) $request->financial_group_id;

                if($request->get('products_sale')){
                    $organization->products_sale = \GuzzleHttp\json_encode($request->get('products_sale'));
                }else{
                    $organization->products_sale = null;
                }


                $organization->save();
                if($organization->org_type->is_provider != 1){
                    $organization->updateUsersAgentContract();
                }



            }
        }



        $organization->save();

        return $organization->id;
    }


    /*
    * REPORTS
    *
    */



    public function getPaymentsTotal($payment_flow = -1){
        $payments = $this->getBasePaymentsReports();
        $payments->where('reports_payments.reports_order_id', '<=', "0");

        if($payment_flow == 0){
            return $payments->sum("dep_total");
        }
        if($payment_flow == 1){
            return $payments->sum("kred_total");
        }


        return 0;
    }


    public function getPaymentsTotalKV($report_type){
        //$report_type - 0 Бордеро 1 ДВОУ
        $payments = $this->getPayments($report_type);


        $sum_kv = 0;
        if((int)$report_type == 0) $sum_kv = $payments->sum("financial_policy_kv_bordereau_total");
        if((int)$report_type == 1) $sum_kv = $payments->sum("financial_policy_kv_dvoy_total");
        return $sum_kv;
    }

    public function getPayments($report_type = -1){
        //$report_type - 0 Бордеро 1 ДВОУ - 1 ВСЕ

        $payments = $this->getBasePaymentsReports();

        if((int)$report_type == 0){
            $payments->where('reports_payments.reports_order_id', '<=', "0");

        }elseif((int)$report_type == 1){
            $payments->where('reports_payments.reports_dvou_id', '<=', "0");
        }else{

            $payments->where(function($query){

                $query->where(function($query2){
                    $query2->where('reports_payments.reports_order_id', '<=', "0");

                })->orWhere(function($query2){
                    $query2->where('reports_payments.reports_dvou_id', '<=', "0");
                });
            });
        }




        return $payments;
    }


    public function getBasePaymentsReports(){

        $payments = ReportPayments::query();
        $payments->where('reports_payments.agent_organization_id', $this->id);
        return $payments;
    }


    public function getBasePayments(){

        $payments = Payments::where('payments.is_deleted', "0");
        $payments->where('payments.agent_organization_id', $this->id);
        return $payments;
    }


    public function getArrayAgentContract()
    {
       return [
            'agent_contract_title' => $this->agent_contract_title,
            'agent_contract_begin_date' => $this->agent_contract_begin_date,
            'agent_contract_end_date' => $this->agent_contract_end_date,
            'ban_level' => $this->ban_level,
            'ban_reason' => $this->ban_reason,
            'curator_id' => $this->curator_id,
            'point_sale_id' => $this->points_sale_id,
            'financial_group_id' => $this->financial_group_id,
            'products_sale' => $this->products_sale,
        ];
    }

    public function updateUsersAgentContract()
    {
        User::where('organization_id', $this->id)->update($this->getArrayAgentContract());
        return true;
    }


    public function getProductsSale($is_view = 0)
    {

        $json = [];
        if($this->products_sale) $json = \GuzzleHttp\json_decode($this->products_sale);
        if($is_view == 1){
            $title = '';
            if($json){
                $products =  Products::whereIn('id', $json)->get();
                foreach ($products as $product){
                    $title .= $product->title."<br/>";
                }
            }
            return $title;
        }

        return $json;
    }

    public function getProductsSaleSpecialSettings()
    {
        $array = [];

        if($this->products_sale)
        {


            $json = \GuzzleHttp\json_decode($this->products_sale);
            $products =  Products::whereIn('id', $json)->get();
            foreach ($products as $product){
                $result = new \stdClass();
                $result->id = $product->id;
                $result->title = $product->title;

                $result->tariff = 'По умолчанию';
                $special_settings = Products\ProductsAgentsSpecialSsettings::where('product_id', $product->id)->where('organization_id', $this->id)->get()->first();
                if($special_settings){
                    $result->tariff = 'Уникальный';
                }


                $array[] = $result;
            }


        }


        return $array;
    }


    public function getDebtBrokerToSk()
    {
        $reports = ReportOrders::where('reports_orders.agent_organization_id', $this->id);
        $reports->where('reports_orders.is_deleted', '=', 0);
        $reports->where('reports_orders.accept_status','!=','4');

        $to_transfer = $reports->sum('reports_orders.to_transfer_total');
        $to_return = $reports->sum('reports_orders.to_return_total');



        $to_transfer_total = 0;
        $to_return_total = 0;
        foreach ($reports->get() as $report){
            $to_transfer_total += $report->report_payment_sums->where('type_id', 1)->sum('amount');
            $to_return_total += $report->report_payment_sums->where('type_id', 0)->sum('amount');
        }

        return [
            'to_transfer' => $to_transfer,
            'to_return' => $to_return,
            'to_transfer_fact' => $to_transfer_total,
            'to_return_fact' => $to_return_total,
            'to_transfer_total' => $to_transfer-$to_transfer_total,
            'to_return_total' => $to_return-$to_return_total,
        ];
    }

}
