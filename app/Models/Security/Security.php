<?php

namespace App\Models\Security;

use App\Models\Orders\EventLabelRfid;
use App\Models\Orders\OrdersEventLabelRfid;
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
class Security extends Model
{

    const STATUS_SEND = 0;
    const STATUS_WORK = 1;
    const STATUS_ARCHIVE = 2;

    const TYPE_INQUIRY_CLIENT = 0;
    const TYPE_INQUIRY_ORG = 1;
    const TYPE_INQUIRY_USER = 2;
    const TYPE_INQUIRY_ORDER = 3;
    const TYPE_INQUIRY_EMERGENCY= 4;

    const STATUS_SECURITY_SERVICE = [0 => 'Не пороверен', 1 => 'Пороверен', 3 => 'Заблокирован'];

    protected $table = 'security';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function send_user() {
        return $this->belongsTo(User::class, 'send_user_id');
    }

    public function work_user() {
        return $this->belongsTo(User::class, 'work_user_id');
    }

    public function emergency() {
        return $this->belongsTo(EventEmergency::class, 'object_id');
    }

    public function event_order() {
        return $this->belongsTo(OrdersEventLabelRfid::class, 'object_id');
    }

    public function type_inquiry_title($type_inquiry){
        $title = '';
        switch ($type_inquiry) {
            case Security::TYPE_INQUIRY_CLIENT:
                $title = 'Проверка клиента';
                break;
            case Security::TYPE_INQUIRY_ORG:
                $title = 'Проверка организации партнера';
                break;
            case Security::TYPE_INQUIRY_USER:
                $title = 'Проверка сотрудника';
                break;
            case Security::TYPE_INQUIRY_ORDER:
                $title = 'Заявка: ';
                break;
            case Security::TYPE_INQUIRY_EMERGENCY:
                $title = 'Экстренные кнопки: ';
                break;
        }
        return $title;
    }

    public function type_link($type_inquiry, $object_id){
        $link = "";
        switch ($type_inquiry) {
            case Security::TYPE_INQUIRY_CLIENT:
                $link = "/clients/clients/$object_id/edit";
                break;
            case Security::TYPE_INQUIRY_ORG:
                $link = "/organizations/organizations/$object_id/edit";
                break;
            case Security::TYPE_INQUIRY_USER:
                $link = "/users/users/$object_id/edit";
                break;
            case Security::TYPE_INQUIRY_ORDER:
                $link = "/orders/orders/event/$object_id";
                break;
        }
        return $link;
    }

    public function status_title($status){
        $title = '';
        switch ($status) {
            case Security::STATUS_SEND:
                $title = 'Создана';
                break;
            case Security::STATUS_WORK:
                $title = 'В работе';
                break;
            case Security::STATUS_ARCHIVE:
                $title = 'Архив';
                break;
        }
        return $title;
    }



}
