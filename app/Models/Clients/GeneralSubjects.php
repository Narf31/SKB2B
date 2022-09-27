<?php

namespace App\Models\Clients;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LAProcedures;
use App\Models\Orders\Damages;
use App\Models\Organizations\Organization;
use App\Models\Settings\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GeneralSubjects extends Authenticatable
{

    use Notifiable;

    protected $table = 'general_subjects';

    protected $guarded = ['id'];

    public $timestamps = false;

    const PERSON_CATEGORY = [
        0 => 'Не выбрано',
        1 => 'Клиент',
        2 => 'Представитель клиента',
        3 => 'Пострадавший',
        4 => 'Бенефициарный владелец',
        5 => 'Агент',
        6 => 'Партнер',
        7 => 'Корреспондент',
        8 => 'Сотрудник',
        9 => 'Перестраховщик',
        10 => 'Банк',
    ];

    const STATUS_WORK = [
        0 => 'Одобрен',
        1 => 'Проверка',
        2 => 'Запрет',
    ];

    const RISK_LEVEL = [
        0 => 'Не выбрано',
        1 => 'Высокий',
        2 => 'Низкий',
    ];


    public function employees_podft(){
        return $this->hasMany(GeneralPodftFl::class, 'general_organization_id', 'id');
    }


    public function interactions_connections(){
        return $this->hasMany(GeneralInteractionsConnections::class, 'general_subject_id');
    }

    public function interactions_connections_type($type){
        return $this->hasMany(GeneralInteractionsConnections::class, 'general_subject_id')->where('type_id', $type)->get();
    }

    public function founders_type($type){
        return $this->hasMany(GeneralFounders::class, 'general_subject_id')->where('type_id', $type)->get();
    }

    public function documents(){
        return $this->hasMany(GeneralSubjectsDocuments::class, 'general_subject_id');
    }

    public function address(){
        return $this->hasMany(GeneralSubjectsAddress::class, 'general_subject_id');
    }

    public function damages(){
        return $this->hasMany(Damages::class, 'insurer_id');
    }

    public function data(){
        if($this->type_id == 0){
            return $this->hasOne(GeneralSubjectsFl::class, 'general_subject_id');
        }else{
            return $this->hasOne(GeneralSubjectsUl::class, 'general_subject_id');
        }
    }

    public function podft(){
        if($this->type_id == 0){
            return $this->hasOne(GeneralPodftFl::class, 'general_subject_id');
        }else{
            return $this->hasOne(GeneralPodftUl::class, 'general_subject_id');
        }
    }



    public function citizenship(){
        return $this->hasOne(Country::class, 'id','citizenship_id');
    }


    public function risk_user(){
        return $this->hasOne(User::class, 'id','risk_user_id');
    }


    public function user(){
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function user_parent(){
        return $this->hasOne(User::class, 'id','user_parent_id');
    }

    public function user_curator(){
        return $this->hasOne(User::class, 'id','user_curator_id');
    }

    public function user_organization(){
        return $this->hasOne(Organization::class, 'id','user_organization_id');
    }

    public function logs(){
        return $this->hasMany(GeneralSubjectsLogs::class, 'general_subject_id')->orderBy('date_sent', 'desc');
    }


    public function procedures() {
        return $this->hasMany(LAProcedures::class, 'general_subject_id', 'id');
    }

    public function getAddressType($type_id){
        $address = $this->address()->where('type_id', $type_id)->get()->first();
        if(!$address) {
            $address = new GeneralSubjectsAddress();
            $address->general_subject_id = $this->id;
            $address->type_id = $type_id;
        }

        return $address;
    }

    public function getDocumentsType($type_id,$is_main = 0){
        $_doc_sql =  $this->documents();

        if((int)$type_id >= 0){
            $_doc_sql->where('type_id', $type_id);
        }
        if($is_main){
            $_doc_sql->where('is_main', 1);
        }

        $document =$_doc_sql->get()->first();
        if(!$document) {
            $document = new GeneralSubjectsDocuments();
            $document->general_subject_id = $this->id;
            $document->type_id = $type_id;
        }

        return $document;
    }

    public function getMainDocumentsType($type_id){

        $document = $this->getDocumentsType($type_id,1);
        if(!isset($document->id)){
            $document = $this->getDocumentsType(-1,1);
            if($document->type_id < 0){
                $document->type_id = $type_id;
            }
        }

        return $document;
    }



    public function checkOrCreateDocumentsType($type_id, $serie, $number){
        $_doc_sql =  $this->documents();

        if((int)$type_id >= 0){
            $_doc_sql->where('type_id', $type_id);
            $_doc_sql->where('serie', $serie);
            $_doc_sql->where('number', $number);
        }

        $document =$_doc_sql->get()->first();
        if(!$document) {
            $document = new GeneralSubjectsDocuments();
            $document->general_subject_id = $this->id;
            $document->type_id = $type_id;
        }

        return $document;
    }

    public function checkDublicateDocument($document,$type_id,$serie, $number)
    {
        $_doc_sql =  $this->documents();
        $_doc_sql->where('type_id', $type_id);
        $_doc_sql->where('serie', $serie);
        $_doc_sql->where('number', $number);

        $doc = $_doc_sql->get()->first();
        if($doc) {
            if($doc->id == $document->id){
                return false;
            }else{
                return true;
            }
        }

        return false;
    }

    public function contracts(){

        $contracts = Contracts::where('contracts.statys_id', 4)
            ->leftJoin('subjects', 'subjects.id', '=', 'contracts.insurer_id')
            ->leftJoin('general_subjects', 'general_subjects.id', '=', 'subjects.general_subject_id')
            ->where('general_subjects.id', $this->id)
            ->select(['contracts.*']);

        return $contracts;
    }



    public function contract($id){
        $contract = $this->contracts()->where('contracts.id', $id)->get()->first();
        return $contract;
    }


    public function getActualContracts(){
        $contracts = $this->contracts()
            ->where('contracts.begin_date', '<=', getDateTime())
            ->where('contracts.end_date', '>=', getDateTime());
        return $contracts->get();
    }


    public function getDocument($type_id)
    {
        $_doc_sql = $this->documents();

        if((int)$type_id >= 0){
            $_doc_sql->where('type_id', $type_id);
        }


        return $_doc_sql->get()->first();
    }

    public function getAddress($type_id)
    {
        return $this->address()->where('type_id', $type_id)->get()->first();
    }


    public function getDocumentTitle($type_id)
    {
        $title = '';
        $document = $this->getDocument($type_id);
        if($document){
            $title = GeneralSubjectsDocuments::TYPE[$type_id]." серия {$document->serie} номер {$document->number} выдан ".setDateTimeFormatRu($document->date_issue,1);
        }

        return $title;
    }

    public function getAddressTitle($type_id)
    {
        $title = '';
        $addres = $this->getAddress($type_id);
        if($addres){
            $title = $addres->address;
        }

        return $title;
    }

    public function getView($user)
    {
        if($user->hasPermission('subject', 'edit')){
            return 'edit';
        }
        return 'view';
    }

    public static function getGeneralSubjectsId($id, $user = null){
        $general = self::getAllGeneralSubjects(-1, $user);
        $general->where('general_subjects.id', $id);
        return $general->get()->first();
    }


    public static function getAllGeneralSubjects($type = -1, $user = null, $is_group = 1){

        $general = GeneralSubjects::query();


        if($type == -1){
            $general->select('general_subjects.*');
        }else{

            $general->where('general_subjects.type_id', (int)$type);



        }


        if($user){
            $rolesVisibility = $user->role->rolesVisibility(13);

            if ($rolesVisibility) {

                $visibility = $rolesVisibility->visibility;

                if ($visibility == 0) {//Все


                } elseif ($visibility == 1) {//Все в рамках организации

                    $general->where(function ($query) use ($user) {
                        $query->where('general_subjects.user_id', $user->id)
                            ->orWhere('general_subjects.user_parent_id', $user->id)
                            ->orWhere('general_subjects.user_curator_id', $user->id)
                            ->orWhere('general_subjects.user_organization_id', $user->organization_id);
                    });


                } elseif ($visibility == 2) {//Только свои

                    $general->where('general_subjects.user_id', $user->id);

                } elseif ($visibility == 3) {//Только свои и своих подчиненных

                    $general->where(function ($query) use ($user) {
                        $query->where('general_subjects.user_id', $user->id)
                            ->orWhere('general_subjects.user_parent_id', $user->id)
                            ->orWhere('general_subjects.user_curator_id', $user->id);
                    });
                }
            } else {

                $general->where('user_id', $user->id);

            }
        }


        $general->orderBy('general_subjects.id', 'desc');




        return $general;

    }

}
