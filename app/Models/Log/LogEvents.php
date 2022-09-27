<?php

namespace App\Models\Log;

use App\Models\Security\Security;
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
class LogEvents extends Model
{
    protected $table = 'log_events';

    protected $guarded = ['id'];

    public $timestamps = true;

    const LOG_VIEW = 0;
    const LOG_CREATE = 1;
    const LOG_UPDATE = 2;
    const LOG_DELETE = 3;


    const EVENT_DEFAULT_NAME = [
        0 => 'Просмотр',
        1 => 'Создания',
        2 => 'Изменения',
        3 => 'Удалениие',
    ];

    const LOG_TYPE = [
        0 => 'Роли',
        1 => 'Пользователи',
        2 => 'Города',
        3 => 'Точки продаж',
        4 => 'Подразделения',
        5 => 'Финансовые группы',
        6 => 'Банки',
        7 => 'Тип организаций',
        8 => 'Продукты',
        9 => 'Организации',
        10 => 'Банковские реквизиты',
        11=> 'Страховые компании',
        12 => 'Алгоритмы рассрочки',
        13 => 'Типы БСО',
        14 => 'Серия БСО',
        15 => 'Доп. серии БСО',
        16 => 'Поставщики БСО',
        17 => 'Удержание КВ',
        18 => 'Финансовые политики',
        19 => 'Сегментация',
        20 => 'Категории Расходов/Доп доходов',
    ];

    public static function event($object_id, $event, $type_id, $root_object_id = 0, $perent_object_id = 0, $data_array = null)
    {
        $map = '';
        $title = '';
        if($data_array != null){
            $map = var_export($data_array, 1);
            if(isset($data_array['title'])) $title = $data_array['title'];
            if(isset($data_array['name'])) $title = $data_array['name'];
            if(isset($data_array['bso_serie'])) $title = $data_array['bso_serie'];
            if(isset($data_array['bso_dop_serie'])) $title = $data_array['bso_dop_serie'];


        }



        LogEvents::create([
            'user_id' => auth()->id(),
            'object_id' => $object_id,
            'event' => $event.' '.$title,
            'type_id' => $type_id,
            'root_object_id' => $root_object_id,
            'perent_object_id' => $perent_object_id,
            'data_map' => $map
        ]);

        return true;
    }


    public function user()
    {
        return $this->hasOne(User::class, 'id', "user_id");
    }

}
