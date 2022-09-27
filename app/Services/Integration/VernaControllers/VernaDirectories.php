<?php

namespace App\Services\Integration\VernaControllers;

use App\Models\Clients\GeneralUlOf;
use App\Models\Contracts\SubjectsFlDocType;
use App\Models\Directories\Products;
use App\Models\Directories\Products\Data\Kasko\BaseRateKasko;
use App\Models\Directories\ProductsPrograms;
use App\Models\Organizations\Organization;
use App\Models\Settings\Country;
use App\Models\Settings\PointsSale;
use App\Models\Settings\SettingsSystem;
use App\Models\Subject\Physical;
use App\Models\User;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleColor;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleMarksKasko;
use App\Models\Vehicle\VehicleModels;
use App\Models\Vehicle\VehicleModelsClassificationKasko;
use App\Models\Vehicle\VehicleModelsKasko;
use App\Models\Vehicle\VehiclePurpose;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Services\Integration\VernaControllers\Auxiliary\Tariff;
use Mockery\Exception;

class VernaDirectories
{

    public $Send = null;

    public function __construct()
    {
        $this->Send = new VernaSend();
    }


    public function getDicti($type){
        $con = '';
        switch ($type) {
            case 0:
                $con = 'ucAimCarUse';//- варианты использования ТС
                break;
            case 1:
                $con = 'ucCatalogDocumentClasses';//- типы документов контрагента
                break;
            case 2:
                $con = 'ucCatalogVehicleDocumentClasses';//- типы документов ТС
                break;
            case 3:
                $con = 'ucTechSurveyDoc';//- типы документов технического осмотра ТС
                break;
            case 4:
                $con = 'ucOrgForm';//- Организационно-правовая форма
                break;
            case 5:
                $con = 'ucSubjEconomic';//- Отрасль (Сектор экономики)
                break;
            case 6:
                $con = 'ucSubjOKVD';//- Справочник ОКВЭД
                break;
            case 7:
                $con = 'ucColorAuto';//- Цвет ТС
                break;
            case 8:
                $con = 'ucCatalogCountryes';//- Страны
                break;
        }

        $params = new \stdClass();
        $params->userConstName = $con;


        $response = $this->Send->send('products/osago/dicti', $params, 'GET');

        return $response;


    }

    public function ucCatalogCountryes()
    {
        $response = $this->getDicti(8);
        if($response && isset($response->data) && isset($response->data->result) && isset($response->data->result->row))
        {
            foreach ($response->data->result->row as $row){
                $title = $row->fullName;
                $country = Country::where('title_ru', mb_strtoupper($title))->get()->first();
                if($country){
                    $country->isn = $row->isn;
                    $country->save();
                }
            }
            return true;
        }
        return false;
    }



    public function ucColorAuto()
    {
        $response = $this->getDicti(7);
        if($response && isset($response->data) && isset($response->data->result) && isset($response->data->result->row))
        {
            VehicleColor::query()->truncate();
            foreach ($response->data->result->row as $row){
                VehicleColor::create([
                    'title' => $row->fullName,
                    'isn' => $row->isn,
                ]);
            }
            return true;
        }
        return false;
    }


    public function countries()
    {
        $response = $this->Send->send('catalog/countries', null, 'GET');
        if($response && isset($response->data) && isset($response->data->result) && isset($response->data->result->row))
        {
            Country::query()->truncate();
            foreach ($response->data->result->row as $row){
                Country::create([
                    'title' => $row->FullName,
                    'title_ru' => $row->FullName,
                    'const_name' => $row->UserConstName,
                    'isn' => $row->ISN,
                ]);
            }

            return true;
        }

        return false;

    }


    public function ucOrgForm()
    {
        $response = $this->getDicti(4);
        if($response && isset($response->data) && isset($response->data->result) && isset($response->data->result->row))
        {

            foreach ($response->data->result->row as $row){
                $hash = GeneralUlOf::getHesh($row->fullName);
                $_g = GeneralUlOf::getFindHesh($hash);
                if($_g){
                    $_g->hash = $hash;
                    $_g->isn = $row->isn;
                    $_g->save();
                }else{
                    GeneralUlOf::create([
                        'full_title' => $row->fullName,
                        'hash' => $hash,
                        'isn' => $row->isn,
                    ]);
                }

            }

            return true;
        }


        return false;
    }

    public function ucCatalogDocumentClasses()
    {
        $response = $this->getDicti(1);
        if($response && isset($response->data) && isset($response->data->result) && isset($response->data->result->row))
        {
            SubjectsFlDocType::query()->truncate();
            foreach ($response->data->result->row as $row){
                SubjectsFlDocType::create([
                    'title' => $row->fullName,
                    'isn' => $row->isn,
                ]);
            }

            return true;
        }

        return false;
    }



    public function categories()
    {

        $response = $this->Send->send('products/osago/categories', null, 'GET');
        if($response && isset($response->data) && isset($response->data->result) && isset($response->data->result->row))
        {
            VehiclePurpose::query()->truncate();
            foreach ($response->data->result->row as $row){
                VehiclePurpose::create([
                    'title' => $row->remark,
                    'isn' => $row->isn,
                ]);
            }
            return true;
        }

        return false;

    }

    public function getrsacarmodels()
    {
        return $this->getVERNAVehicleCatalog();

        $response = $this->Send->send('products/osago/getrsacarmodels', null, 'GET');
        if($response && isset($response->data) && isset($response->data->result) && isset($response->data->result->row))
        {
            $cats = [];
            $marks = [];
            VehicleModels::query()->truncate();
            foreach ($response->data->result->row as $row){

                $cats[$row->categoryId] = $row->categoryName;
                $marks[$row->markaId] = ['title'=>$row->markaName, 'category_id'=>$row->categoryId];
                VehicleModels::create([
                    'title' => $row->modelName,
                    'isn' => $row->modelId,
                    'rsa_code' =>$row->RSACode,
                    'mark_id' =>$row->markaId,
                    'category_id' =>$row->categoryId,
                ]);

            }

            $this->updateCat($cats);
            $this->updateMark($marks);
            return true;
        }

        return false;
    }


    public function getVERNAVehicleCatalog()
    {
        $response = $this->Send->send('products/osago/getVERNAVehicleCatalog', null, 'GET');
        if($response && isset($response->data) && isset($response->data->result) && isset($response->data->result->types))
        {
            $cats = [];
            $marks = [];
            VehicleModels::query()->truncate();

            foreach ($response->data->result->types->subtypes as $category){
                if(isset($category->ISN)){
                    $cats[$category->ISN] = $category->FullName;
                    if(isset($category->manufacturers)){
                        foreach ($category->manufacturers as $manufacturer){
                            if(isset($manufacturer->ISN)){
                                $marks[$manufacturer->ISN] = ['title'=>$manufacturer->FullName, 'category_id'=>$category->ISN];
                                if(isset($manufacturer->models)){
                                    foreach ($manufacturer->models as $model){
                                        if(isset($model->ISN)){
                                            VehicleModels::create([
                                                'title' => $model->FullName,
                                                'isn' => $model->ISN,
                                                'rsa_code' => null,
                                                'mark_id' => $manufacturer->ISN,
                                                'category_id' => $category->ISN,
                                            ]);
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }

            $this->updateCat($cats);
            $this->updateMark($marks);
            return true;
        }

        return false;
    }


    private function updateCat($cats)
    {
        VehicleCategories::query()->truncate();
        foreach ($cats as $key => $cat){
            VehicleCategories::create([
                'title' => $cat,
                'isn' => $key,
            ]);
        }
        return true;
    }

    private function updateMark($marks)
    {
        VehicleMarks::query()->truncate();
        foreach ($marks as $key => $mark){
            VehicleMarks::create([
                'title' => $mark['title'],
                'category_id' => $mark['category_id'],
                'isn' => $key,
            ]);
        }
        return true;
    }



    public function getSalesPoints()
    {

        ini_set("memory_limit",-1);

        $response = $this->Send->send('agent/getSalesPoints', null, 'GET');

        if(isset($response->data) && isset($response->data->result) && isset($response->data->result->row)){
            $products = Products::where('code_api', '>', 0)->get()->pluck('id', 'code_api')->toArray();
            foreach ($response->data->result->row as $org){

                $organization = Organization::where('frontnodeisn', (int)$org->frontnodeisn)
                    ->where('subjisn', (int)$org->subjIsn)->get()->first();
                if(!$organization){
                    $organization = new Organization();
                    $organization->frontnodeisn = (int)$org->frontnodeisn;
                    $organization->subjisn = (int)$org->subjIsn;
                }
                $organization->title = (string)$org->subjName;
                $organization->code_partner = (isset($org->sellerCode))?(string)$org->sellerCode:'';

                $organization->points_sale_id = $this->getFrontPointID((int)$org->deptIsn, (string)$org->deptName);
                $organization->org_type_id = 2;
                $organization->is_actual = ((string)$org->active == 'Y')?1:0;
                $organization->curator_id =  $this->getCuratorID((int)$org->emplIsn, (string)$org->emplName, $organization->points_sale_id, (string)$org->email);
                $organization->save();
                $this->getOperatorsBySalesPoint($organization);
                $this->getProductsBySalesPoint($organization, $products);

                $organization->updateUsersAgentContract();

            }
            return true;
        }

        return false;
    }

    public function getOperatorsBySalesPoint($organization)
    {

        $response = $this->Send->send('agent/getOperatorsBySalesPoint?frontnodeisn='.$organization->frontnodeisn, null, 'GET');
        if(isset($response->data) && isset($response->data->result) && isset($response->data->result->row)){

            foreach ($response->data->result->row as $bd_user)
            {
                //dd($bd_user);
                /*
                  +"frontUserIsn": "5369"
                  +"userIsn": "31757895"
                  +"userName": "Hermes Api User"
                  +"userOsLogin": "HERMES"
                  +"lastName": "Hermes"
                  +"firstName": "Api"
                  +"parentName": "User"
                  +"email": "hermes@verna-group.ru"
                  +"phoneMobile": "+7-(666)-666-66-66"
                  +"frontUserIsActive": "Y"
                  +"userIsBlocked": "N"
                  +"frontUserRole": "1"
                  +"noSubjectEditing": "0"
                  +"noSearchAgr": "0"
                  +"userRoles": "Д5З8И7Л8С8Щ8"
                  +"remark": "HERMES 01"
                  +"sellerCode": "9302001"
                  +"sellerRole": "0"
                */

                $role_id = 15;
                if($bd_user && isset($bd_user->userIsn)){
                    $user = User::where('export_user_id', (int)$bd_user->userIsn)->get()->first();
                    if(!$user){

                        $organization_id = $organization->id;

                        $email = $bd_user->email;
                        $email = mb_strtolower($email);

                        $pass = GeneralSubjectsInfo::createGeneralSubjectPassword(8);
                        $password = bcrypt(trim($pass));
                        $subject = $this->getUserSubject($bd_user->userName);
                        $is_parent = 0;

                        $check_user = User::where('email', $email)->get()->last();
                        if($check_user){
                            //$check_user->export_user_id = $user->id;
                            //$check_user->export_parent_id = $user->reports_to_id;
                            //$check_user->save();
                            $email = "duble-{$check_user->id}-{$bd_user->userIsn}-$email";
                        }

                        $user = User::create([
                            'name' => "{$bd_user->userName}",
                            'email' => $email,
                            'password' => $password,
                            'subject_type_id' => 1,
                            'subject_id' => $subject->id,
                            'role_id' => isset($bd_user->ETRole) ? $bd_user->ETRole : $role_id,
                            'organization_id' => $organization_id,
                            'status_user_id' => (isset($bd_user->frontUserIsActive) && $bd_user->frontUserIsActive == "Y")?0:1,
                            'is_parent' => $is_parent,
                            'financial_group_id' => 0,
                            'department_id' => 4,
                            'point_sale_id' => $organization->points_sale_id,
                            'sales_condition' => 1,
                            'export_user_id' => (int)$bd_user->userIsn,
                        ]);
                    }else{
                        $user->status_user_id = (isset($bd_user->frontUserIsActive) && $bd_user->frontUserIsActive == "Y")?0:1;
                        $user->role_id = isset($bd_user->ETRole) ? $bd_user->ETRole : $role_id;
                        $user->save();
                    }
                }

            }

            return true;
        }


        return false;

    }

    public function getProductsBySalesPoint($organization, $products)
    {

        $response = $this->Send->send('agent/getProductsBySalesPoint?frontnodeisn='.$organization->frontnodeisn, null, 'GET');

        if(isset($response->data) && isset($response->data->result) && isset($response->data->result->row)){

            $acces_products = [];
            foreach ($response->data->result->row as $bd_product)
            {
                if(isset($products[$bd_product->productIsn])){
                    $acces_products[] = $products[$bd_product->productIsn];
                }
            }

            $organization->products_sale = \GuzzleHttp\json_encode($acces_products);
            $organization->save();

            return true;
        }

        return false;

    }

    public function getFrontPointID($isn, $title)
    {
        $points_sale = PointsSale::where('deptIsn', (int)$isn)->get()->first();
        if(!$points_sale){
            $points_sale = PointsSale::create([
                'title' => $title,
                'deptIsn' => $isn,
                'is_actual' => 1,
                'is_sale' => 1,
            ]);
        }

        return $points_sale->id;
    }

    public function getCuratorID($emplIsn, $emplName, $points_sale_id, $email){


        $email = mb_strtolower($email);

        $role_id = 7;
        $organization_id = 1;

        $user = User::where('export_user_id', (int)$emplIsn)->get()->first();
        if(!$user)
        {


            $check_user = User::where('email', $email)->get()->last();
            if($check_user){
                $email = "duble-{$check_user->id}-{$emplIsn}-$email";
            }



            $pass = GeneralSubjectsInfo::createGeneralSubjectPassword(8);
            $password = bcrypt(trim($pass));
            $subject = $this->getUserSubject($emplName);
            $is_parent = 1;

            $user = User::create([
                'name' => "{$emplName}",
                'email' => $email,
                'password' => $password,
                'subject_type_id' => 1,
                'subject_id' => $subject->id,
                'role_id' => $role_id,
                'organization_id' => $organization_id,
                'status_user_id' => 0,
                'is_parent' => $is_parent,
                'financial_group_id' => 0,
                'department_id' => 8,
                'point_sale_id' => $points_sale_id,
                'sales_condition' => 1,
                'export_user_id' => $emplIsn,
            ]);
        }else{
            $user->role_id = $role_id;
            $user->save();
        }

        return $user->id;
    }

    public function getUserSubject($emplName)
    {

        $first_arr = explode(' ', $emplName);


        $first = $emplName;

        $middle = '';
        $second = '';
        if(isset($first_arr) && isset($first_arr[1]) && strlen($first_arr[1]) > 0){
            $second = $first_arr[0];
            $first = $first_arr[1];
            if(isset($first_arr[2]) && strlen($first_arr[2]) > 0){
                $middle = $first_arr[2];
            }
        }


        return Physical::create([
            'second_name' => $second,
            'first_name' => $first,
            'middle_name' => $middle,
            'is_export' => 1,
        ]);
    }


    public function getTransdekraManufacturers()
    {

        //VehicleMarksKasko::query()->truncate();
        $response = $this->Send->send('products/osago/getTransdekraManufacturers', null, 'GET');

        if(isset($response->data) && isset($response->data->result) && isset($response->data->result->row)) {
            foreach ($response->data->result->row as $bd_mark) {

                $mark = VehicleMarksKasko::where('code', $bd_mark->Code)->get()->first();
                if(!$mark){
                    $mark = VehicleMarksKasko::create([
                        'code' => $bd_mark->Code,
                        'title' => $bd_mark->FullName,
                    ]);
                }

                self::getTransdekraModels($mark);

            }
            return true;
        }

        return false;

    }

    public function getTransdekraModels($mark)
    {

        VehicleModelsKasko::where('PARENTCODE', $mark->code)->delete();
        $response = $this->Send->send('products/osago/getTransdekraModels?MakerKey='.$mark->code, null, 'GET');
        //$response = $this->Send->send('products/osago/getTransdekraModels?MakerKey='.$mark, null, 'GET');
        //dd($response);
        if(isset($response->data) && isset($response->data->result) && isset($response->data->result->row)) {
            foreach ($response->data->result->row->row as $bd_model) {

                if($bd_model && isset($bd_model->CODE) && isset($bd_model->NAME)){
                    $model = VehicleModelsKasko::create([
                        'CODE' => $bd_model->CODE,
                        'PARENTCODE' => $bd_model->PARENTCODE,
                        'NAME' => $bd_model->NAME,
                        'ModelISN' => $bd_model->ModelISN,
                    ]);


                    VehicleModelsClassificationKasko::where('PARENTCODE', $bd_model->CODE)->delete();

                    if(isset($bd_model->row)){
                        if(is_array($bd_model->row)){
                            foreach ($bd_model->row as $classification){
                                self::getCreateClassification($classification);
                            }
                        }else{
                            self::getCreateClassification($bd_model->row);
                        }
                    }
                }
            }
            return true;
        }


        return false;

    }


    public function getCreateClassification($classification)
    {
        if($classification && isset($classification->CODE) && isset($classification->NAME)){
            VehicleModelsClassificationKasko::create([
                'CODE' => $classification->CODE,
                'PARENTCODE' => $classification->PARENTCODE,
                'NAME' => $classification->NAME,
                'ModelISN' => $classification->ModelISN,
                'TypeKey' => $classification->TypeKey,
                'TypeName' => $classification->TypeName,
                'CategoryKey' => $classification->CategoryKey,
                'CategoryName' => $classification->CategoryName,
                'BodyKey' => $classification->BodyKey,
                'BodyName' => $classification->BodyName,
                'TransmissionKey' => $classification->TransmissionKey,
                'TransmissionName' => $classification->TransmissionName,
                'FuelKey' => $classification->FuelKey,
                'FuelName' => $classification->FuelName,
                'PrivodKey' => $classification->PrivodKey,
                'PrivodName' => $classification->PrivodName,
                'EngVol' => $classification->EngVol,
                'EngPwr' => $classification->EngPwr,
                'CarDoors' => $classification->CarDoors,
                'CarSeats' => $classification->CarSeats,
                'MaxWeight' => $classification->MaxWeight,
                'ProdStart' => $classification->ProdStart,
                'ProdEnd' => $classification->ProdEnd,
            ]);
        }

        return true;
    }

    public function updateTariffDefault()
    {
        BaseRateKasko::query()->truncate();
        foreach (VehicleMarks::query()->get() as $marks){
            foreach ($marks->models() as $model){
                $base_triff = Tariff::getCarTariff($marks->title, $model->title);
                if($base_triff){
                    $payment_damage = getFloatFormat($base_triff[0]);
                    $payment_hijacking = $payment_damage*$base_triff[1];
                    $this->saveTariffDefaultYear($model, $payment_damage, $payment_hijacking);
                }

            }
        }

        return true;
    }

    public function saveTariffDefaultYear($model, $payment_damage, $payment_hijacking)
    {
        $programs = ProductsPrograms::all();

        for($i=0; $i<=7; $i++){
            //Ушерб + 0,50 Угон - 0,02
            if($i > 0){
                $payment_damage = getFloatFormat($payment_damage+getTotalSumToPrice($payment_damage,0.50));
                $temp_hijacking = $payment_hijacking-($payment_hijacking*0.20);
                if($temp_hijacking > 0){
                    $payment_hijacking = getFloatFormat($temp_hijacking);
                }
            }

            //dump("$i = $payment_damage = $payment_hijacking");

            foreach ($programs as $kasko){
                BaseRateKasko::create([
                    'product_id' => $kasko->product_id,
                    'program_id' => $kasko->id,
                    'year' => $i,
                    'payment_damage' => getFloatFormat($payment_damage),
                    'payment_hijacking' => getFloatFormat($payment_hijacking),
                    'mark_id' => $model->mark_id,
                    'model_id' => $model->isn,
                ]);
            }


        }
        return true;
    }


    public function getIndividualPassport($number)
    {
        try {
            $response = @file_get_contents("https://focus-api.kontur.ru/api3/checkPassport?passportNumber={$number}&key=ca22c738621e20ba94eba9d0ce02570c4a54f4b9");
            if($response){
                return \GuzzleHttp\json_decode($response)[0];
            }

        } catch (Exception $e) {

        }
        return null;
    }


    public function getIndividuals($fio, $dateOfBirth = '')
    {
        $params = [
            'birthDate' => (strlen($dateOfBirth) >0 ? setDateTimeFormatRu($dateOfBirth, 1) : ''),
            'fullName' => $fio,
        ];
        return $this->Send->send('checkSanctionedListEntry', $params, 'POST');
    }

    public function getCompanyStructure()
    {
        $params = [
            'onlyActive' => 'N',
        ];
        $response = $this->Send->send('getCompanyStructure', $params, 'POST');

        foreach ($response->data->result as $row){
            $this->checkUserVerna($row);
        }

        return true;
    }

    public function checkUserVerna($row){
        if(isset($row->email)){
            $this->updataUserVerna($row);
        }

        if(isset($row->row)){
            if(is_array($row->row)){
                foreach ($row->row as $_row){
                    $this->checkUserVerna($_row);
                }
            }elseif(isset($row->row->email)){
                $this->updataUserVerna($row->row);
            }

        }
        return true;
    }


    public function updataUserVerna($bd_user){


        if(isset($bd_user->email) && strlen($bd_user->email) > 0){


            $user = User::where('export_user_id', (int)$bd_user->userIsn)->get()->first();
            $role_id = isset($bd_user->ETRole) ? $bd_user->ETRole : 7;
            $email = $bd_user->email;
            $email = mb_strtolower($email);
            $check_user = User::where('email', $email)->get()->last();
            if($check_user){
                $email = "duble-{$check_user->id}-{$bd_user->isn}-{$bd_user->email}";
            }

            if(!$user)
            {
                $organization_id = 1;
                $pass = GeneralSubjectsInfo::createGeneralSubjectPassword(8);
                $password = bcrypt(trim($pass));
                $subject = $this->getUserSubject($bd_user->userName);
                $is_parent = 0;

                $user = User::create([
                    'name' => "{$bd_user->userName}",
                    'email' => $email,
                    'password' => $password,
                    'subject_type_id' => 1,
                    'subject_id' => $subject->id,
                    'role_id' => $role_id,
                    'organization_id' => $organization_id,
                    'status_user_id' => (isset($bd_user->userIsBlocked) && $bd_user->userIsBlocked == "N")?0:1,
                    'is_parent' => $is_parent,
                    'financial_group_id' => 0,
                    'department_id' => 8,
                    'point_sale_id' => 1,
                    'sales_condition' => 1,
                    'export_user_id' => (int)$bd_user->userIsn,
                    'export_parent_id' => (int)$bd_user->parentIsn,
                ]);
            }else{
                //$user->email = $email;
                $user->status_user_id = (isset($bd_user->userIsBlocked) && $bd_user->userIsBlocked == "N")?0:1;
                $user->role_id = $role_id;
                $user->save();
            }
            return true;
        }


        return false;
    }


    public function getCompanyStructureUpdate()
    {
        //Проставляем руководителей
        $parent_users = User::where('export_parent_id', '>', 0);
        $parent_users->select(['export_parent_id']);
        $users = User::query();
        $users->whereRaw('`export_user_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings());

        dd(getLaravelSql($users));

        foreach ($users->get() as $user){
            dd($user);
            $user->is_parent = 1;
            if((int)$user->export_parent_id > 0){
                $parent = self::getExportUserId($user->export_parent_id);
                if($parent){
                    $user->parent_id = $parent->id;
                }
            }
            $user->save();
        }
        return true;
    }

    public static function getExportUserId($id, $type = 'export_user_id')
    {
        return User::where($type, $id)->get()->first();
    }

}