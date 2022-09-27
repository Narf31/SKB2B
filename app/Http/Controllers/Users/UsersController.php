<?php

namespace App\Http\Controllers\Users;

use App\Classes\Export\ExportManager;
use App\Helpers\Visible;
use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Log\LogEvents;
use App\Models\Organizations\Organization;
use App\Models\Settings\TemplateCategory;
use App\Models\Subject\Juridical;
use App\Models\Subject\Physical;
use App\Models\Subject\Type as SubjectType;
use App\Models\User;
use App\Models\Users\Role;
use App\Models\Users\SalaryType;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;
use Validator;
use Mail;
use App\Mail\UserAdd;

class UsersController extends Controller {

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository) {

        $this->middleware('permissions:users,users');

        $this->filesRepository = $filesRepository;


        $this->breadcrumbs[] = [
            'label' => 'Пользователи',
            'url' => 'users/users',
        ];
    }

    public function index(Request $request) {


        return view('users.users.index', [

        ]);
    }

    public function show($id){
        return redirect("/users/users/{$id}/edit/");
    }


    public function edit($id) {

        $user = User::find($id);

        $this->breadcrumbs[] = [
            'label' => $user->name,
        ];

        return view('users.users.edit', [
                    'user' => $user,
                    'roles' => Role::all()->pluck('title', 'id')->prepend(trans('form.select.not_selected'), ''),
                    'organizations' => Organization::query()->where('is_delete', 0)->get()->pluck('title', 'id')->prepend(trans('form.select.not_selected'), ''),
                    'userInfoFields' => $this->getUserInfoFields(),
                    'salaryTypes' => [
                        SalaryType::OFFICIAL => trans('users/users.edit.salary_types.official'),
                        SalaryType::UNOFFICIAL => trans('users/users.edit.salary_types.unofficial'),
                    ]
                ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function create() {

        $this->breadcrumbs[] = [
            'label' => 'Новый пользователь',
        ];

        return view("users.users.create", [
                    'roles' => Role::all()->pluck('title', 'id')->prepend(trans('form.select.not_selected'), ''),
                    'organizations' => Organization::query()->where('is_delete', 0)->get()->pluck('title', 'id')->prepend(trans('form.select.not_selected'), ''),
                    'userInfoFields' => $this->getUserInfoFields(),
                    'salaryTypes' => [
                        SalaryType::OFFICIAL => trans('users/users.edit.salary_types.official'),
                        SalaryType::UNOFFICIAL => trans('users/users.edit.salary_types.unofficial'),
                    ]
                ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function update($id, Request $request) {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'email' => 'required|unique:users|max:255|email',
        ]);

        if ($validator->fails() && $user->email !== $request->email) {
            return redirect(url("/users/users/$user->id/edit/"))->withInput()->with('error', "Ошибка валидации email");
        }

        LogEvents::event($user->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 1, 0, 0, $request->all());

        return $this->save($user, $request);
    }

    public function store(Request $request) {
        $user = new User;

        //$user->save();

        $validator = Validator::make($request->all(), [
                    'email' => 'required|unique:users|max:255|email',
                    'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(url("/users/users/create"))->withInput()->with('error', implode(', ', $validator->errors()->all()));
        }


        LogEvents::event($user->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 1, 0, 0, $request->all());

        return $this->save($user, $request);
    }

    private function save(User $user, Request $request) {

        $this->validate($request, [
            'name' => '',
            'image' => 'mimes:jpeg,png,bmp',
        ]);




        $user->name = $request->name;
        $user->email = mb_strtolower(trim($request->email));
        $user->role_id = $request->role_id;
        $user->subject_type_id = $request->subject_type_id;
        $user->organization_id = $request->organization_id;
        $user->mobile_phone = $request->mobile_phone;
        $user->work_phone = $request->work_phone;
        $user->status_user_id = $request->status_user_id;
        $user->is_parent = (int) $request->is_parent;
        $user->is_notification = (int) $request->is_notification;
        $user->financial_group_id = (int) $request->financial_group_id;
        $user->department_id = (int) $request->department_id;


        $user->parent_id = (int) $request->parent_id;
        $user->curator_id = (int) $request->curator_id;


        $user->point_sale_id = (int) $request->point_sale_id;

        $user->ban_level = (int) $request->ban_level;
        $user->ban_reason = $request->ban_reason;

        $user->front_user_id = $request->front_user_id;
        $user->front_user_title = $request->front_user_title;


        $is_ok = true;

        if ($request->has('password') && strlen($request->get('password')) >= 6) {
            $user->password = bcrypt(trim($request->password));

        }else{
            if($request->has('password') && strlen($request->get('password')) >= 1){
                $is_ok = false;
            }
        }



        $user->agent_contract_title = $request->agent_contract_title;
        $user->agent_contract_begin_date = setDateTimeFormat($request->agent_contract_begin_date);
        $user->agent_contract_end_date = setDateTimeFormat($request->agent_contract_end_date);

        $user->sales_condition = (int)$request->sales_condition;


        if($request->get('products_sale')){
            $user->products_sale = \GuzzleHttp\json_encode($request->get('products_sale'));
        }else{
            $user->products_sale = null;
        }

        //if(isset($request->status_security_service)) $user->status_security_service = $request->status_security_service;

        if ($request->hasFile('image')) {
            $user->image()->associate($this->filesRepository->makeFile($request->image, User::FILES_FOLDER . "/{$user->id}/"));
            $user->smallImage()->associate($this->filesRepository->makeResizedImage($request->image, User::FILES_FOLDER));
        }

        $user->info()->delete();

        $subjectClass = $user->subject_type_id == SubjectType::PHYSICAL ? Physical::class : Juridical::class;

        $subject = $subjectClass::create($request->only($subjectClass::getFields()));

        $user->subject_id = $subject->id;
        $user->save();


        $user->path_parent = $user->getPathParent();
        $user->save();

        if(\App::environment() != 'local'){
            //Mail::to($user->email)->send(new UserAdd($request));
        }

        if($is_ok == true){
            return redirect(url("/users/users/$user->id/edit/"))->with('success', trans('form.success_update'));
        }

        return redirect(url("/users/users/$user->id/edit/"))->with('error', 'Пароль должен содержать минимум 6 символов!');
    }

    public function destroy($id) {
        $user = User::find($id);

        $user->delete();

        return response('', 200);
    }

    public function getUserInfoFields() {
        return [
            'physical' => Physical::getFields(),
            'juridical' => Juridical::getFields(),
        ];
    }


    public function generate_contract($id) {
        $agent = Visible::apply(User::query(), 'users', 'id')->where('id', $id);
        return (new ExportManager(TemplateCategory::get('contract_agent'), $agent))->handle();
    }



    public function get_table(Request $request)
    {
        $like = (env('DB_CONNECTION') == "pgsql") ? 'ilike' : 'like';
        $users = User::getALLUserWhere();

        if (isset($request->fio) && strlen($request->fio)>=3) {
            $users->where('name', $like, "%" . $request->fio . "%");
        }

        if (isset($request->email) && strlen($request->email)>=3) {
            $users->where('users.email', $like, "%" . $request->email . "%");
        }

        if (isset($request->parent) && strlen($request->parent)>=3) {

            $parent = $request->parent;
            $users->whereHas('perent', function ($query) use ($parent,$like) {
                $query->where('name', $like,  '%' .$parent. '%');
            });
        }

        if (isset($request->curator) && strlen($request->curator)>=3) {

            $curator = $request->curator;
            $users->whereHas('curator', function ($query) use ($curator,$like) {
                $query->where('name', $like,  '%' .$curator. '%');
            });
        }

        $page = (int)$request->page;
        if ($page == 0) {
            $page = 1;
        }
        $page_count = isset($request->page_count) && (int)$request->page_count > 0 ? (int)$request->page_count : 25;


        $max_row = (int)$users->count();
        $page_max = ceil($max_row/$page_count);
        $view_row = ($page_count*($page));

        $users->skip(($page_count*($page-1)))->take(($page_count));
        $res = $users->get();

        if($res->count() < $page_count){
            $view_row = ($max_row);
        }



        return response()->json([
            'result' => view('users.users.get_table', [
                'users'=>$res
            ])->render(),
            'page_max' => $page_max,
            'page_sel' => $page,
            'max_row' => $max_row,
            'view_row' => $view_row,
        ]);


    }


    public function getViewTariff($id, $product_id, Request $request)
    {
        $user = User::find($id);
        $product = Products::find($product_id);
        $special_settings = Products\ProductsAgentsSpecialSsettings::where('product_id', $product_id)->where('user_id', $id)->get()->first();
        if(!$special_settings){
            $special_settings = Products\ProductsAgentsSpecialSsettings::create(['product_id'=>$product_id, 'user_id'=>$id, 'settings'=>0]);
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
            return redirect("/users/users/{$id}/tariff/{$product_id}");
        }

        $view = 'index';
        if($product->slug != ''){
            if(\View::exists("users.tariff.{$product->slug}")){
                $view = $product->slug;
            }
        }


        $this->breadcrumbs[] = [
            'label' => $user->name,
            'url' => "{$user->id}",
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

        return view("users.tariff.{$view}", [
            "breadcrumbs" => $this->breadcrumbs,
            "product" => $product,
            "user" => $user,
            "special_settings" => $special_settings,
            "json" => $json,
            "request" => $request,
        ]);


    }

    public function saveTariff($id, $product_id, Request $request)
    {
        $product = Products::find($product_id);
        $special_settings = Products\ProductsAgentsSpecialSsettings::where('product_id', $product_id)->where('user_id', $id)->get()->first();
        $result = new \stdClass();
        $result->msg = 'Ошибка сохраниения данных';
        $result->state = $special_settings->saveJson($request, $product->slug);

        return response()->json($result);

    }

}
