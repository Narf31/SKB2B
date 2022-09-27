<?php

namespace App\Models\Settings;

use App\Models\Users\Role;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Department
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Department whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Department whereTitle($value)
 * @mixin \Eloquent
 * @property integer $user_type_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Department whereUserTypeId($value)
 */
class Department extends Model
{
    protected $table = 'departments';

    protected $guarded = ['id'];

    public $timestamps = false;

    public static $rules = [
        'title' => 'required'
    ];

    const PAGE_ENTRY = [
        '' => 'Главная страница',
        '/contracts/online/' => 'Оформление договоров',
        '/reports/reports_sk/' => 'Отчетность',
        '/cashbox/payment_reports/' => 'Оплата отчетов',
        '/analitics/common/' => 'Аналитика',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function type_org()
    {
        return $this->hasOne(TypeOrg::class, 'id', "org_type_id");
    }


}
