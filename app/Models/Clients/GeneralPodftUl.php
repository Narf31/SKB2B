<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Model;

class GeneralPodftUl extends Model
{


    protected $table = 'general_podft_ul';

    protected $guarded = ['id'];

    public $timestamps = false;


    const IN_WHOSE_INTERESTS = [
        0 => 'Не выбрано',
        1 => 'В своих',
        2 => 'В интересах третьего лица',
    ];





    public function saveData($data)
    {

        $this->purpose_establishing_relationship = $data->purpose_establishing_relationship;
        $this->financial_business_objectives = $data->financial_business_objectives;
        $this->financial_position = $data->financial_position;
        $this->information_business_reputation = $data->information_business_reputation;


        $this->in_whose_interests_id = isset($data->in_whose_interests_id)?1:0;
        $this->is_recipient_grants = isset($data->is_recipient_grants)?1:0;
        $this->is_budgetary_institution = isset($data->is_budgetary_institution)?1:0;
        $this->is_founder = isset($data->is_founder)?1:0;
        $this->is_beneficiary = isset($data->is_beneficiary)?1:0;
        $this->is_documents_submitted_paper = isset($data->is_documents_submitted_paper)?1:0;


        $this->save();

        return true;
    }


}
