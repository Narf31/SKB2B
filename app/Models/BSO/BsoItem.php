<?php

namespace App\Models\BSO;

use App\Classes\Export\TagModels\BSO\TagBsoItem;
use App\Models\Acts\ReportAct;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Directories\Products;
use App\Models\Directories\TypeBso;
use App\Models\File;
use App\Models\Organizations\Organization;
use App\Models\Settings\PointsSale;
use App\Models\User;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;

class BsoItem extends Model{

    use ActiveConstTrait;

    const FILES_DOC = 'bso/';
    const TAG_MODEL = TagBsoItem::class;

    protected $table = 'bso_items';

    protected $guarded = ['id'];

    public $timestamps = false;


    const ACT_STATE = [
        -2 => 'Реестр будущий',
        -1 => 'Реестр текущий',
         0 => 'Реестр корзина'
    ];


    public function transfer(){
        return $this->hasOne(BsoTransfer::class, 'id', 'transfer_id');
    }

    public function supplier(){
        return $this->hasOne(BsoSuppliers::class, 'id', 'bso_supplier_id');
    }

    public function supplier_org(){
        return $this->hasOne(Organization::class, 'id', 'org_id');
    }

    public function type(){
        return $this->hasOne(BsoType::class, 'id', 'type_bso_id');
    }

    public function point_sale(){
        return $this->hasOne(PointsSale::class, 'id', 'point_sale_id');
    }

    public function bso_locations(){
        return $this->hasOne(BsoLocations::class, 'id', 'location_id');
    }

    public function bso_states(){
        return $this->hasOne(BsoState::class, 'id', 'state_id');
    }

    public function bso_serie(){
        return $this->hasOne(BsoSerie::class, 'id', 'bso_serie_id');
    }

    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function agent(){
        return $this->hasOne(User::class, 'id', 'agent_id');
    }

    public function acts(){
        return $this->hasMany(BsoActsItems::class, 'bso_id', 'id');
    }

    public function act_sk(){
        return $this->hasOne(ReportAct::class, 'id', 'acts_sk_id');
    }

    public function logs(){
        return $this->hasMany(BsoLogs::class, 'bso_id', 'id')
            ->groupby('id')
            ->groupby('bso_id')
            ->groupby('bso_act_id')
            ->groupby('bso_state_id')
            ->groupby('bso_location_id')
            ->groupby('bso_user_from')
            ->groupby('bso_user_to')
            ->groupby('user_id')
            ->groupby('ip_address')
            ->groupby('contract_id')
            ->groupby('reports_act_id')
            ->groupby('reports_order_id')
            ->groupby('is_deleted')
            ->groupby('cashbox_id')
            ->groupby('log_time')
            ->orderBy("log_time", "desc");
    }

    public function cars(){
        return $this->hasOne(BsoCarts::class, 'id', 'bso_cart_id');
    }

    public function contract_receipt(){
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function contract(){
        return $this->hasOne(Contracts::class, 'bso_id', 'id')->where('statys_id', '>', 0);
    }

    public function payments(){
        return $this->hasMany(Payments::class, 'bso_id', 'id')->where('is_deleted',  0);
    }

    public function scan()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function location(){
        return $this->hasOne(BsoLocations::class, 'id', 'location_id');
    }

    public function state(){
        return $this->hasOne(BsoState::class, 'id', 'state_id');
    }

    public function insurance()
    {
        return $this->hasOne(InsuranceCompanies::class, 'id', 'insurance_companies_id');
    }




    public function time_on_agent(){
        return ceil((time() - strtotime($this->transfer_to_agent_time))/60/60/24);
    }

    public function time_on_stock(){
        return ceil((time() - strtotime($this->time_create))/60/60/24);
    }


    public static function update_to_inital($ids){

        $update_data = [
            'transfer_id' => 0,
            'location_id' => 0,
            'user_org_id' => 0,
            'user_id' => 0,
            'org_id' => 0,
        ];

        self::query()->whereIn('id', $ids)->update($update_data);

        return true;

    }


    public static function getElectronicBso(User $agent_work, BsoSuppliers $bso_supplier, Products $product)
    {

        $bso_serie = BsoSerie::where('product_id', $product->id)
            ->where('bso_class_id', 1)
            ->where('is_actual', 1)
            ->get()->last();

        $bso = null;

        if($bso_serie){

            $bso_number_from = 1;
            $bso_last = BsoItem::where('product_id', $product->id)
                ->where('bso_class_id', 1)
                ->where('bso_serie_id', $bso_serie->id)
                ->orderBy("bso_number", 'desc')->get()->first();
            if($bso_last){
                $bso_number_from = ((int)$bso_last->bso_number)+1;
            }

            $bso_number = getNumbersBsoFormat($bso_number_from, $bso_serie->bso_count_number);


            $agent = $agent_work;

            $organization = $agent_work->organization;
            if($organization->parent_user) $agent = $organization->parent_user;

            $point_sale_id = (int)$agent->point_sale_id;
            if($agent->curator){
                $point_sale_id = (int)$agent->curator->point_sale_id;
            }

            $year2 = date("Y");
            $year2 = substr($year2, -2);


            //Создаем БСО и вешаем на Агента
            $bso = BsoItem::create([
                'bso_supplier_id' => $bso_supplier->id,
                'org_id' => $bso_supplier->id,
                'insurance_companies_id' => $bso_supplier->insurance_companies_id,
                'bso_class_id' => $bso_serie->bso_class_id,
                'type_bso_id' => $bso_serie->type_bso_id,
                'product_id' => $bso_serie->product_id,
                'bso_serie_id' => $bso_serie->id,
                'bso_number' => $bso_number,
                'bso_title' => $bso_serie->bso_serie.' '.$bso_number,
                'location_id' => 1,
                'state_id' => 0,
                'agent_id' => $agent->id,
                'agent_organization_id' => $agent->organization->id,
                'user_id' => $agent->id,
                'user_org_id' => $agent->organization->id,
                'time_create' => date('Y-m-d H:i:s'),
                'time_target' => date('Y-m-d H:i:s'),
                'last_operation_time' => date('Y-m-d H:i:s'),
                'transfer_to_agent_time' => date('Y-m-d H:i:s'),
                'bso_manager_id' => (int)auth()->id(),
                'point_sale_id' => $point_sale_id,
            ]);


            BsoLogs::setLogs(
                $bso->id,
                $bso->state_id,
                1,
                0,
                0,
                $agent->id,
                0,
                0,
                -2
            );
        }

        return $bso;

    }


    public static function getBso(){
        $bsos = BsoItem::query();

        //Проверка на доступы
        $user = auth()->user();
        $visibility_obj = $user->role->rolesVisibility(4)->visibility;



        if($visibility_obj == 0){ //Все

        }elseif ($visibility_obj == 1){//Все в рамках организации

            //$bsos->where("user_org_id", $user->organization_id);

        }elseif ($visibility_obj == 2){//Только свои

            $bsos->where("agent_id", $user->id);

        }elseif ($visibility_obj == 3){//Только свои и своих подчиненных

            $bsos->whereIn('agent_id', function($query)
            {

                $query->select(\DB::raw('users.id'))
                    ->from('users')
                    ->where('parent_id', '=', auth()->id())
                    ->orWhere('id', auth()->id())
                    ->orWhere('curator_id', '=', auth()->id())
                    ->orWhere('path_parent', 'like', "%:".auth()->id().":%");

            });

        }


        return $bsos;
    }


    public static function getBsoId($id){
        $bsos = BsoItem::getBso();

        $bsos->where('id', $id);
        return $bsos->get()->first();
    }

    public static function getBsoOnlineId($id){
        $bsos = BsoItem::query();

        $bsos->where('id', $id);
        return $bsos->get()->first();
    }


    public function setBsoLog($location_id, $postpone = 0)
    {
        BsoLogs::setLogs(
            $this->id,
            $this->state_id,
            $location_id,
            0,
            auth()->id(),
            0,
            0,
            0,
            $postpone
        );

        /*
        switch($location_id){
            case 2:
                $this->update(['transfer_to_sk_time' => date('Y-m-d H:i:s')]);
                break;
        }
        */
    }

    public static function countBsoInventory($bso_supplier_id, $type_bso_id)
    {
        $_items = self::where('bso_items.bso_supplier_id', $bso_supplier_id)
            ->where('bso_items.type_bso_id', $type_bso_id)
            ->leftJoin('bso_suppliers', 'bso_suppliers.id', 'bso_items.bso_supplier_id')
            ->leftJoin('type_bso', 'type_bso.id', 'bso_items.type_bso_id');

        $_items_location_2 = clone $_items;
        $_items_location_2->where('bso_items.location_id', 2);
        $_items_location_not_2 = clone $_items;
        $_items_location_not_2->where('bso_items.location_id', '!=', 2);

        $_stock = clone $_items;
        $_reserv = clone $_items;
        $_agents = clone $_items;

        $result = new \stdClass();
        $result->qty_all = $_items->count('bso_items.id');
        $result->qty_stock = $_stock->where('bso_items.location_id', 0)->where('bso_items.state_id', 0)->where('bso_items.is_reserved', 0)->count('bso_items.id');
        $result->qty_reserv = $_reserv->where('bso_items.location_id', 0)->where('bso_items.state_id', 0)->where('bso_items.is_reserved', 1)->count('bso_items.id');
        $result->qty_agents = $_agents->where('bso_items.location_id', 1)->count('bso_items.id');

        $result->qty_sold = (clone $_items_location_not_2)->where('bso_items.state_id', 2)->count('bso_items.id');
        $result->qty_spoiled = (clone $_items_location_not_2)->where('bso_items.state_id', 3)->count('bso_items.id');
        $result->qty_other = (clone $_items_location_not_2)->where('bso_items.state_id', '>', 3)->count('bso_items.id');

        $result->qty_sk_blank = (clone $_items_location_2)->where('bso_items.state_id', 0)->count('bso_items.id');
        $result->qty_sk_sold = (clone $_items_location_2)->where('bso_items.state_id', 2)->count('bso_items.id');
        $result->qty_sk_spoiled = (clone $_items_location_2)->where('bso_items.state_id', 3)->count('bso_items.id');
        $result->qty_sk_other = (clone $_items_location_2)->where('bso_items.state_id', '>', 3)->count('bso_items.id');

        return $result;
    }

}
