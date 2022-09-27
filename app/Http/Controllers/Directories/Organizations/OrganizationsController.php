<?php

namespace App\Http\Controllers\Directories\Organizations;

use App\Http\Controllers\Controller;

use App\Models\Directories\Products;
use App\Models\Log\LogEvents;
use App\Models\Organizations\Organization;
use App\Models\Organizations\OrgBankAccount;
use App\Models\Settings\Department;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:directories,organizations');
        
        $this->breadcrumbs[] = [
            'label' => 'Справочники',
        ];
        
        $this->breadcrumbs[] = [
            'label' => 'Организации',
            'url' => 'directories/organizations/organizations',
        ];

    }

    public function index()
    {
        $control_url = '/directories/organizations';

        return view('organizations.organizations.index', [
            'organizations' => Organization::getALLOrg()->get(),
            'control_url' => $control_url
        ]);
    }

    public function get_table(Request $request)
    {

        $control_url = '/directories/organizations';
        $like = (env('DB_CONNECTION') == "pgsql") ? 'ilike' : 'like';
        $organizations = Organization::getALLOrg();

        if (isset($request->title) && strlen($request->title)>=3) {
            $organizations->where('title', $like, "%" . $request->title . "%");
        }

        if (isset($request->inn) && strlen($request->inn)>=3) {
            $organizations->where('inn', $like, "%" . $request->inn . "%");
        }

        if (isset($request->curator) && strlen($request->curator)>=3) {

            $curator = $request->curator;
            $organizations->whereHas('curator', function ($query) use ($curator,$like) {
                $query->where('name', $like,  '%' .$curator. '%');
            });
        }

        $page = (int)$request->page;
        if ($page == 0) {
            $page = 1;
        }
        $page_count = isset($request->page_count) && (int)$request->page_count > 0 ? (int)$request->page_count : 25;


        $max_row = (int)$organizations->count();
        $page_max = ceil($max_row/$page_count);
        $view_row = ($page_count*($page));

        $organizations->skip(($page_count*($page-1)))->take(($page_count));
        $res = $organizations->get();

        if($res->count() < $page_count){
            $view_row = ($max_row);
        }

        return response()->json([
            'result' => view('organizations.organizations.get_table', [
                'organizations'=>$res,
                'control_url' => $control_url
            ])->render(),
            'page_max' => $page_max,
            'page_sel' => $page,
            'max_row' => $max_row,
            'view_row' => $view_row,
        ]);


    }

    public function create()
    {
        $organization = new Organization();
        return view('organizations.edit',
            ['organization'     => $organization]
        )->with('breadcrumbs', $this->breadcrumbs);
    }

    public function show($id){
        return redirect("/directories/organizations/organizations/{$id}/edit/");
    }

    public function edit($id)
    {
        
        $user = Auth::user();
        $organization = Organization::getIdOrg($id);

        if(isset($organization->id)){

            $this->breadcrumbs[] = [
            'label' => $organization->title,
            ];
            
            return view('organizations.edit', [
                'organization'      => $organization,
                'org_bank_account'  => OrgBankAccount::where('org_id', $id)->get(),
                'permission_delete' => $user->hasPermission('directories', 'organizations_edit')
            ])->with('breadcrumbs', $this->breadcrumbs);
        }else{
            return view('errors.403', ['exception'=>1]);
        }

    }


    public function store(Request $request)
    {
        return $this->save(new Organization, $request);
    }

    public function update($id, Request $request)
    {
        if((int)$id > 0 ) $org = Organization::findOrFail($id);
        else $org = new Organization;

        return $this->save($org, $request);
    }


    private function save(Organization $organization, Request $request)
    {
        $organization->saveData($request);
        return redirect("/directories/organizations/organizations/{$organization->id}/edit/")->with('success', trans('form.success_update'));
    }

    public function delete($id)
    {
        $user = Auth::user();

        if($user->hasPermission('directories', 'organizations_edit')){
            $org = Organization::findOrFail($id);
            $org->update([
                'is_delete' => 1
            ]);

            LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 9);

            User::where('organization_id', $id)->update([
                'status_user_id' => 1
            ]);

        }else{
            return view('errors.403', ['exception'=>1]);
        }

        return response('', 200);
    }



    public function get_html_block($id, Request $request)
    {
        $user = Auth::user();

        if($id == 0) $organization = new Organization();
        else $organization = Organization::getIdOrg($id);

        $send_urls = "/directories/organizations/organizations/".(int)$organization->id;
        return view($request->view, [
            'organization' => $organization,
            'send_urls' => $send_urls,
            'users' => new User(),
            'data' => $request->data,
            'permission_delete' => $user->hasPermission('directories', 'organizations_edit')
        ]);

    }



    public function get_users_list($id){

        $organization  = Organization::query()->findOrFail($id);
        $users = $organization->users();

        if(request()->has('department_id') && request()->get('department_id') > 0){
            $users->where('department_id', (int)request()->get('department_id'));
        }

        return [
            'organization' => $organization,
            'users' => $users->get(),
        ];
    }

    public function get_users_table($id){
        $data = $this->get_users_list($id);
        $data['html'] = view("organizations.users_table", $data)->render();
        return $data;
    }


    public function getViewTariff($id, $product_id, Request $request)
    {
        $organization = Organization::getIdOrg($id);
        $product = Products::find($product_id);
        $special_settings = Products\ProductsAgentsSpecialSsettings::where('product_id', $product_id)->where('organization_id', $id)->get()->first();
        if(!$special_settings){
            $special_settings = Products\ProductsAgentsSpecialSsettings::create(['product_id'=>$product_id, 'organization_id'=>$id, 'settings'=>0]);
        }

        if(isset($request->settings)){
            $special_settings->settings = (int)$request->settings;
            if($request->settings == 0){
                $special_settings->json = null;
            }

            if($request->settings == 1){

                $_special_settings = null;
                if($product->special_settings){
                    $_special_settings = $product->special_settings;
                }
                $special_settings->json = ($_special_settings)?$_special_settings->json:null;
            }

            $special_settings->save();
            return redirect("/directories/organizations/organizations/{$id}/tariff/{$product_id}");
        }

        $view = 'index';
        if($product->slug != ''){
            if(\View::exists("organizations.tariff.{$product->slug}")){
                $view = $product->slug;
            }
        }


        $this->breadcrumbs[] = [
            'label' => $organization->title,
            'url' => "{$organization->id}",
        ];

        $this->breadcrumbs[] = [
            'label' => "Тариф - {$product->title}",
            'url' => "tariff/$product_id"
        ];

        $this->breadcrumbs[] = [
            'label' => '',
        ];


        $json = null;

        if($special_settings->json){
            $json = \GuzzleHttp\json_decode($special_settings->json, true);
        }

        return view("organizations.tariff.{$view}", [
            "breadcrumbs" => $this->breadcrumbs,
            "product" => $product,
            "organization" => $organization,
            "special_settings" => $special_settings,
            "json" => $json,
            "request" => $request,
        ]);


    }

    public function saveTariff($id, $product_id, Request $request)
    {
        $product = Products::find($product_id);
        $special_settings = Products\ProductsAgentsSpecialSsettings::where('product_id', $product_id)->where('organization_id', $id)->get()->first();
        $result = new \stdClass();
        $result->msg = 'Ошибка сохраниения данных';
        $result->state = $special_settings->saveJson($request, $product->slug);

        return response()->json($result);

    }


}
