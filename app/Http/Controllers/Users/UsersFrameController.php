<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Settings\Department;
use App\Models\Subject\Juridical;
use App\Models\Subject\Physical;
use App\Models\Subject\Type as SubjectType;
use App\Models\User;
use App\Models\Users\UsersLimitBSO;
use Illuminate\Http\Request;

class UsersFrameController extends Controller {


	public function __construct() {

		$this->middleware('permissions:directories,organizations_user');

	}



	public function frame(Request $request){

	    $user_id = (int)$request->user_id;
        if($user_id == 0){
            $user = new User;
        }else{
            $user = User::findOrFail($user_id);
        }

        $user->organization_id = $request->org_id;



        return view('users.frame.edit', [
            'user' => $user,
            'departments'    => Department::where('org_type_id', $user->organization->org_type_id)->get(),
            'userInfoFields' => $this->getUserInfoFields(),
        ]);

	    
    }

    public function save(Request $request){
        $user_id = (int)$request->user_id;
        if($user_id == 0){
            $user = new User;
        }else{
            $user = User::findOrFail($user_id);
        }


        $user->name                   = $request->name;
        $user->email                  = $request->email;
        //$user->role_id                = $request->role_id;
        $user->subject_type_id        = (int)$request->subject_type_id;
        $user->organization_id        = $request->organization_id;
        $user->mobile_phone           = $request->mobile_phone;
        $user->work_phone             = $request->work_phone;
        $user->status_user_id         = $request->status_user_id;
        $user->is_parent              = (int)$request->is_parent;
        $user->financial_group_id     = (int)$request->financial_group_id;
        $user->department_id          = (int)$request->department_id;
        $user->filial_id              = (int)$request->filial_id;
        //$user->ais_id                 = $request->ais_id;


        $user->parent_id              = (int)$request->parent_id;


        if ($request->has('password') && strlen($request->get('password'))>3) {
            $user->password = bcrypt($request->password);
        }


        $user->info()->delete();

        $subjectClass = $user->subject_type_id == SubjectType::PHYSICAL ? Physical::class : Juridical::class;

        $subject = $subjectClass::create($request->only($subjectClass::getFields()));

        $user->subject_id = $subject->id;

        $user->save();

        if($user->department) $user->role_id = $user->department->role_id;

        $user->path_parent = $user->getPathParent();


        $user->save();
        return parentReloadTab();

    }


    public function getUserInfoFields() {
        return [
            'physical'  => Physical::getFields(),
            'juridical' => Juridical::getFields(),
        ];
    }


    public function limit(Request $request)
    {

        $user = User::find($request->user_id);
        $products = Products::where('is_actual', 1)
            ->orderBy('title', 'asc')
            ->get();


        return view('users.frame.limit', [
            'user' => $user,
            'products' => $products
        ]);


    }

    public function save_limit(Request $request)
    {
        $user = User::find($request->user_id);
        UsersLimitBSO::where('user_id', $user->id)->delete();

        $productsArray = $request->get('product_id') ?: [];
        $max_limitArray = $request->get('max_limit') ?: [];

        foreach ($productsArray as $key => $product_id){

            UsersLimitBSO::create([
                'user_id' => $user->id,
                'product_id' => $product_id,
                'max_limit' => $max_limitArray[$key],
            ]);

        }

        return parentReload();

    }


}
