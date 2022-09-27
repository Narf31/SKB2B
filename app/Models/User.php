<?php

namespace App\Models;

use App\Classes\Export\TagModels\Characters\TagUser;
use App\Classes\Notification\NotificationManager;
use App\Helpers\Visible;
use App\Models\Account\TableColumn;
use App\Models\BSO\BsoItem;
use App\Models\Cashbox\Cashbox;
use App\Models\Characters\Agent;
use App\Models\Contracts\Debts;
use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\Products;
use App\Models\Finance\Invoice;
use App\Models\Organizations\Organization;
use App\Models\Security\Security;
use App\Models\Settings\Department;
use App\Models\Settings\Notification;
use App\Models\Settings\PointsSale;
use App\Models\Settings\UserBalanceSettings;
use App\Models\Subject\Juridical;
use App\Models\Subject\Physical;
use App\Models\Users\Permission;
use App\Models\Users\PermissionGroup;
use App\Models\Users\PromoCode;
use App\Models\Users\Role;
use App\Models\Users\SalaryType;
use App\Models\Users\UsersBalance;
use App\Models\Users\UsersLimitBSO;
use App\Traits\Models\ActiveConstTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;
    use ActiveConstTrait;

	const FILES_FOLDER = 'users/photos';
    const FILES_DOC = 'users/docs';

    const TAG_MODEL = TagUser::class;

    const STATUS_USER = [
        0=>'Работает',
        1=>'Уволен',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'settings' => 'json',
    ];
    
    /*
     * Relations
     */

    public function notifications(){
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function point_sale(){
        return $this->hasOne(PointsSale::class, 'id', 'point_sale_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scans()
    {
        return $this->belongsToMany(File::class, 'users_scans', 'user_id', 'file_id');
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function image()
    {
        return $this->belongsTo(File::class);
    }

    public function smallImage()
    {
        return $this->belongsTo(File::class);
    }

    public function security_service() {
        return $this->hasOne(Security::class, 'object_id')->where('type_inquiry' , '=', Security::TYPE_INQUIRY_USER)->orderBy('created_at', 'desc');
    }

    public function bso_items(){
        return $this->hasMany(BsoItem::class, 'user_id', 'id');
    }

    public function info()
    {
        $subjectClass = $this->subject_type_id == 1 ? Physical::class : Juridical::class;
        return $this->hasOne($subjectClass, 'id', 'subject_id');
    }

    public function perent()
    {
        return $this->hasOne(User::class, 'id', 'parent_id');
    }

    public function curator(){
        return $this->hasOne(User::class, 'id', 'curator_id');
    }


    public function cashbox() {
        return $this->hasOne(Cashbox::class, 'user_id', 'id')->where('is_actual' , '=', 1)->limit(1);
    }

    public function columns()
    {
        return $this->belongsToMany(TableColumn::class, 'users2columns', 'user_id', 'column_id')->withPivot('orders');
    }

    public function promocode() {
        return $this->hasMany(PromoCode::class, 'user_id', 'id');
    }


    public function getNotifications(){

        $notifications = $this->notifications()
            ->where('is_read', 0);

        return $notifications;

    }

    public static function getALLUserWhere()
    {
        $user = auth()->user();

        $user_list = User::orderBy(\DB::raw("CONCAT(users.name, ' - ', organizations.title)"), 'asc');
        //$user_list->select('users.*', \DB::raw("CONCAT(users.name, ' ', users.email) as name"));
        $user_list->leftJoin('organizations', 'organizations.id', '=', 'users.organization_id');
        $user_list->select('users.*', \DB::raw("CONCAT(users.name, ' - ', organizations.title) as name"));


        $rolesVisibility = $user->role->rolesVisibility(1);

        if ($rolesVisibility) {

            $visibility = $rolesVisibility->visibility;


            if ($visibility == 0) {//Все

            } elseif ($visibility == 1) {//Все в рамках организации

                $user_list->where(function ($query) {
                    $query->where('users.parent_id', auth()->id())
                        ->orWhere('users.id', auth()->id())
                        ->orWhere('users.organization_id', auth()->user()->organization_id);
                });



            } elseif ($visibility == 2) {//Только свои

                $user_list->where('users.id', auth()->id());
            } elseif ($visibility == 3) {//Только свои и своих подчиненных



                $user_list->where(function ($query) {
                    $query->where('users.parent_id', auth()->id())
                        ->orWhere('users.id', auth()->id())
                        ->orWhere('users.path_parent', 'like', "%:".auth()->id().":%");
                });



            }
        } else {
            $user_list->where('users.id', auth()->id())->orderBy('name', 'asc');
        }

        return $user_list;

    }




    public static function getALLAgents($permission_id = 24, $get = true)
    {
        $user = User::orderBy(\DB::raw("CONCAT(users.name, ' - ', organizations.title)"), 'asc');
        //$user->where();
        $user->leftJoin('organizations', 'organizations.id', '=', 'users.organization_id');
        $user->select('users.*', \DB::raw("CONCAT(users.name, ' - ', organizations.title) as name"));


        $user->whereIn('users.role_id', function($query) use ($permission_id)
        {
            $query->select(\DB::raw('roles_permissions.role_id'))
                ->from('roles_permissions')
                ->where('roles_permissions.permission_id', $permission_id);

        });

        if($get){
         return $user->get();
        }else{
            return $user;
        }
    }

    public static function getALLAgentsToBSO()
    {
        $user = User::orderBy('name', 'asc');
        $user->whereIn('ban_level', [0, 1]);
        //$user->leftJoin('type_obj', 'type_obj.id', '=', 'objects.type_id')




        return $user->get();
    }

    public static function getALLUser()
    {
        return User::getALLUserWhere()->get();
    }

    public static function getUserId($id)
    {
        return User::getALLUserWhere()->where('users.id', $id)->get()->first();
    }

    public static function getALLParent()
    {
        return User::getALLUserWhere()->where('users.is_parent', 1)->get();
    }

    public static function getALLCurator()
    {
        return User::getALLUserWhere()->where('users.is_parent', 1)->get();
    }


    public static function getUserIsRole($role_name)
    {
        $roles = Permission::query()
            ->leftJoin('roles_permissions', 'permissions.id', '=', 'roles_permissions.permission_id')
            ->leftJoin('permissions_groups', 'permissions_groups.id', '=', 'permissions.group_id')
            ->where('permissions.title', $role_name)
            ->select('roles_permissions.role_id');

        $user = User::whereIn('role_id', $roles->get()->toArray());

        return $user;
    }

    /*
     * Accessors
     */

    public function getTimezoneAttribute()
    {
        return $this->attributes['timezone'] ?: config('app.timezone');
    }

    public function getUserLimitBSOToProduct($product_id, $type=0, $day = null)
    {
        $count_bso = 0;

        if($type == 0){ //вернуть лимит по продукту

            $my_limit = UsersLimitBSO::where('user_id', $this->id)->where('product_id', $product_id)->get()->first();
            if($my_limit) $count_bso = $my_limit->max_limit;

        }elseif($type == 1){ //узнать количество на руках по продукту
            $my_limit = BsoItem::where('user_id', $this->id)->where('location_id', 1);
            if($product_id > 0) $my_limit->where('product_id', $product_id);

            if($day && (int)$day > 0){

                $myData = date('Y-m-d 00:00:00', strtotime("- $day day"));
                $my_limit->where('transfer_to_agent_time', '<=',$myData);

            }


            $my_bso = $my_limit->get();
            if($my_bso) $count_bso = $my_bso->count();
        }


        return (int)$count_bso;
    }

    /*
     * Scopes
     */


    private function getByUserType($query, $type)
    {
        return $query->whereHas('department', function ($q) use ($type) {
            return $q->where('user_type_id', $type);
        });
    }

    /*
     * Functions
     */


    public function getUserBsoSupplier(){
        $bso_supplier = BsoSuppliers::query();
        $organization_id = $this->organization_id;

        if($this->curator) $organization_id = $this->curator->organization_id;

        $bso_supplier->where('purpose_org_id', $organization_id);
        return $bso_supplier->get()->first();
    }


    public function getParentUserList($prepend_name = 'Нет'){
        //Проверяем доступность пользователей
        return $this::getALLUser()->pluck('name', 'id')->prepend($prepend_name, 0);

    }

    public function hasPermission($permissionGroupName, $permissionName)
    {
        $permission = Permission::where('title', $permissionName)->whereHas('group', function ($query) use ($permissionGroupName) {

            $query->where('title', $permissionGroupName);

        })->first();

        if (!$permission || !$this->role) {

            return false;

        }

        return $this->role->hasPermission($permission->id);
    }

    public function hasGroupPermission($permissionGroupName)
    {
        $permissions = Permission::whereHas('group', function ($query) use ($permissionGroupName) {

            $query->where('title', $permissionGroupName);

        })->get();

        if (!sizeof($permissions) || !$this->role) {

            return false;

        }

        return $this->role->permissions()->whereIn('id', $permissions->pluck('id'))->exists();
    }

    public function ifSalaryTypeOfficial()
    {
        return $this->salary_type === SalaryType::OFFICIAL;
    }

    public function getScheduleForDay(Carbon $date)
    {
        return $this->schedules()->where('date', $date->format('Y-m-d'))->first();
    }


    public function getAllOrgUsers(){
        return User::query()->where('organization_id', '=', $this->organization_id)->get()->keyBy('id');
    }

    public function getSubUsers(){
        $res = [];
        if($this->is_parent){
            $res = User::query()->where('parent_id', '=', $this->id)->get()->keyBy('id');
        }
        return $res;
    }


    public function visibleAgents($permission_group_name){

        $agents = Agent::query();
        $agents = Visible::apply($agents, $permission_group_name, 'id');
        return $agents;
    }


    public function visibleUsers($permission_group_name){

        $users = User::query();
        $users = Visible::apply($users, $permission_group_name, 'id');
        return $users;
    }



    public function visibleInvoices(Builder $builder = null){
        $invoices = $builder ? $builder : Invoice::query();
        $invoices = Visible::apply($invoices, 'finance');
        return $invoices;
    }


    public function visibleDebts(Builder $builder = null){
        $debts = $builder ? $builder : Debts::query();
        $debts = Visible::apply($debts, 'finance', ['agent_id', 'manager_id']);
        return $debts;
    }

    public function visiblePayments(Builder $builder = null){
        $payments = $builder ? $builder : Payments::query();
        $payments = Visible::apply($payments, 'finance', ['agent_id', 'manager_id']);
        return $payments;
    }


     public function getBalance($balance_id){
        $_balanse = UsersBalance::where('user_id', $this->id)->where('balance_id', $balance_id)->get()->first();
        if(!$_balanse){
            $_balanse = UsersBalance::create([
                "user_id"    => $this->id,
                "balance_id" => $balance_id,
                "balance"    => 0,
            ]);
        }

        return $_balanse;
    }

    public function getBalanceId($balance_id){
        $_balanse = UsersBalance::where('id', $balance_id)->get()->first();

        return $_balanse;
    }



    public function getBalanceList(){

        $user_balances = UserBalanceSettings::where('is_actual', 1)->get();
        $result = [];

        foreach ($user_balances as $balances)
        {
            $_balanse = $this->getBalance($balances->id);
            $balenc = new \stdClass();
            $balenc->id = $_balanse->id;
            $balenc->balance = $_balanse->balance;
            $balenc->title = $balances->title." ".titleFloatFormat($_balanse->balance);
            $result[] = $balenc;
        }

        return $result;

    }


    public function getBalanceProfit(){
        $user_balances = UserBalanceSettings::where('is_actual', 1)->where('type_id', 0)->get()->first();
        return $this->getBalance($user_balances->id);
    }

    public function getBalanceCancelPayment(){
        $user_balances = UserBalanceSettings::where('is_actual', 1)->where('type_id', 3)->get()->first();
        return $this->getBalance($user_balances->id);
    }

    public function getPathParent()
    {
        $path = '';

        if($this->perent){
            if($this->parent_id != $this->id){
                $path = $this->perent->getPathParent();
            }
        }

        if(isset($this->organization) && $this->organization->org_type->is_provider == 0)
        {
            $this->update($this->organization->getArrayAgentContract());
        }



        return "{$path}:{$this->id}:";
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
                $array[] = $result;
            }


        }


        return $array;
    }

    public function getAllPermissionArray()
    {
        $userRolePermissions = [];
        if(isset($this->role)){
            foreach ($this->role->permissions_menu() as $permissions){
                $userRolePermissions[$permissions->groups][$permissions->permissions] = 1;
            }
        }


        return $userRolePermissions;
    }

    public function getTableColumns($table_key, $default_all = true)
    {
        $user = \Auth::user();
        $user_columns = $user->columns()->get()
            ->where('table_key', $table_key)
            //->sortBy('pivot.orders')
            ->toArray();
        if(count($user_columns)==0){
            if($default_all == true)
            {
                $user_columns = TableColumn::all()->where('table_key', $table_key)->toArray();
            }else{
                $user_columns = TableColumn::all()->where('is_default', 1)->where('table_key', $table_key)->toArray();
            }


        }
        foreach($user_columns as &$v){
            $v['_key'] = ($v['is_as']==1) ? $v['as_key'] : $v['column_key'];
        }

        return $user_columns;
    }


    public function is($role_name)
    {
        return $this->hasPermission('role', $role_name);

    }

}
