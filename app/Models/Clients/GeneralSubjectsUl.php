<?php

namespace App\Models\Clients;

use App\Models\Contracts\Contracts;
use App\Models\Orders\Damages;
use App\Models\Settings\Bank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GeneralSubjectsUl extends Model
{


    protected $table = 'general_subjects_ul';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function bank(){
        return $this->hasOne(Bank::class, 'id','bank_id');
    }

    public function gentral_manager(){
        $general_manager = GeneralSubjects::query();
        $general_manager->leftJoin('general_podft_fl', 'general_podft_fl.general_subject_id', '=', 'general_subjects.id');
        $general_manager->where('general_podft_fl.general_organization_id', $this->general_subject_id);
        //$general_manager->where('general_podft_fl.job_position', 'Генеральный Директор');
        $general_manager->select(['general_subjects.*']);

        $general_manager = $general_manager->get()->last();
        if(!$general_manager){
            $general_manager = new GeneralSubjects();
            $general_manager->type_id = 0;
            $general_manager->podft = new GeneralPodftUl();
            $general_manager->podft->job_position = 'Генеральный Директор';
            $general_manager->podft->general_organization_id = $this->id;
            $general_manager->data = new GeneralSubjectsFl();
            $general_manager->data->birthdate = null;
        }
        return $general_manager;
    }

    public function of(){
        return $this->hasOne(GeneralUlOf::class, 'code','of_id');
    }

    const OF = [
        0 => 'Не выбрано',
        1 => 'ООО',
        2 => 'ОАО',
        3 => 'ЗАО',
        4 => 'ТСЖ',
        5 => 'ФГУП',
        6 => 'МУП',
        7 => 'ГУП субъекта РФ',
        8 => 'Федеральное казенное предприятие',
        9 => 'Казенное предприятие субъекта РФ',
        10 => 'Муниципальное казенное предприятие',
        11 => 'Государственная корпорация',
        12 => 'Производственный кооператив',
        13 => 'Некоммерческие партнерства',
        14 => 'АО',
        15 => 'НАО',
        16 => 'ПАО',
        17 => 'Ассоциация (Союз)',
    ];

    const OWNERSHIP = [
        0 => 'Не выбрано',
        1 => 'Федеральная собственность',
        2 => 'Собственность субъектов Российской Федерации',
        3 => 'Муниципальная собственность',
        4 => 'Частная собственность',
        5 => 'Иностранная собственность',
        6 => 'Совместная иностранная и российская собственность',
        7 => 'Иное',
    ];


    public function actuals_general_manager(){
        $general_manager = $this->hasOne(GeneralSubjects::class, 'id','general_manager_id')->get()->first();
        if(!$general_manager){
            $general_manager = new GeneralSubjects();
            $general_manager->type_id = 0;
        }
        return $general_manager;
    }


    public function saveFrame($request)
    {

        $this->of_id = (int)$request->of_id;
        $this->full_title = $request->full_title;
        $this->full_title_en = $request->full_title_en;
        $this->ownership_id = (int)$request->ownership_id;
        $this->inn = $request->inn;
        $this->kpp = $request->kpp;
        $this->ogrn = $request->ogrn;
        $this->date_orgn = getDateFormatEn($request->date_orgn);
        $this->issued = $request->issued;
        $this->place_registration = $request->place_registration;
        $this->bank_id = (int)$request->bank_id;
        $this->bik = $request->bik;
        $this->rs = $request->rs;
        $this->ks = $request->ks;
        $this->share_capital = getFloatFormat($request->share_capital);
        $this->presence_permanent_management_body = $request->presence_permanent_management_body;
        $this->license_information = $request->license_information;
        $this->management_structure = $request->management_structure;
        $this->undertaken_identify_beneficial = $request->undertaken_identify_beneficial;
        $this->okpo = $request->okpo;
        $this->oktmo = $request->oktmo;
        $this->okfs = $request->okfs;

        $this->okato = $request->okato;
        $this->okogy = $request->okogy;
        $this->okopf = $request->okopf;

        $this->okved_code = $request->okved_code;
        $this->okved_title = $request->okved_title;

        //$this->general_manager_id = (int)$request->general_manager_id;
        //$this->general_accountant_id = (int)$request->general_accountant_id;

        $this->save();
        return true;
    }

}
