<?php

namespace App\Models\Directories\Products\Data;

use App\Models\Clients\GeneralSubjects;
use App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LADocuments;
use App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LAProcedures;
use App\Models\Organizations\Organization;
use App\Models\Security\Security;
use App\Models\Settings\Country;
use App\Models\Settings\Currency;
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
class LiabilityArbitrationManager extends Model
{
    protected $table = 'products_liability_arbitration_manager';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function general_insurer() {
        return $this->hasOne(GeneralSubjects::class, 'id', 'general_insurer_id');
    }

    public function cro() {
        return $this->hasOne(Organization::class, 'id', 'cro_id');
    }

    public function procedure() {
        return $this->hasOne(LAProcedures::class, 'id', 'procedure_id');
    }

    public function documents($key) {

        return $this->hasMany(LADocuments::class, 'contract_id', 'contract_id')->where('document_id', $key)->get();
    }

    public function agent()
    {
        return $this->hasOne(User::class, 'id', 'kv_agent_id');
    }

    public function agent2()
    {
        return $this->hasOne(User::class, 'id', 'kv_agent2_id');
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'kv_manager_id');
    }

    public function manager2()
    {
        return $this->hasOne(User::class, 'id', 'kv_manager2_id');
    }

    public function manager3()
    {
        return $this->hasOne(User::class, 'id', 'kv_manager3_id');
    }



    public function setDefault()
    {

        $this->kv_manager_id = auth()->id();
        $this->save();

        return true;
    }


    const TYPE_AGR = [
        1 => 'Годовой',
        2 => 'Процедурный',
    ];

    const CURRENT_PROCEDURES = [
        1 => '0 (без назначений)',
        2 => '1-5 текущих процедур',
        3 => '6-10 текущих процедур',
        4 => '11-15 текущих процедур',
        5 => '> 15 текущих процедур',
    ];



    const FILE_CATEGORY = [
        0 => 'Андеррайтинг',
        1 => 'Заявление',
        2 => 'ТЗ',
        3 => 'Иные документы',
    ];








}
