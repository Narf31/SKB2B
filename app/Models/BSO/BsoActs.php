<?php

namespace App\Models\BSO;

use App\Classes\Export\TagModels\BSO\TagBsoActs;
use App\Helpers\Visible;
use App\Models\Contracts\Payments;
use App\Models\Organizations\Organization;
use App\Models\Settings\PointsSale;
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
class BsoActs extends Model
{
    protected $table = 'bso_acts';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TAG_MODEL = TagBsoActs::class;

    public function setItemsCarts($bso_cart_id)
    {
        $bso_items = BsoItem::where('bso_cart_id', $bso_cart_id)->get();

        foreach ($bso_items as $bso)
        {
            BsoActsItems::create([
                'bso_act_id' => $this->id,
                'bso_id' => $bso->id,
                'bso_title' => $bso->bso_title,
            ]);
        }

    }

    public function act_org(){
        return $this->hasOne(Organization::class, 'id', 'act_org_id');
    }

    public function user_from(){
        return $this->hasOne(User::class, 'id', 'user_id_from');
    }

    public function user_to(){
        return $this->hasOne(User::class, 'id', 'user_id_to');
    }

    public function bso_manager(){
        return $this->hasOne(User::class, 'id', 'bso_manager_id');
    }


    public function bso_state(){
        return $this->hasOne(BsoState::class, 'id', 'bso_state_id');
    }

    public function logs(){
        return $this->hasMany(BsoLogs::class, 'bso_act_id', 'id');
    }

    public function point_sale(){
        return $this->hasOne(PointsSale::class, 'id', 'tp_id');
    }

    public function type(){
        return $this->hasOne(BsoCartType::class, 'id', 'type_id');
    }

    public function payments(){
        return $this->hasMany(Payments::class, 'realized_act_id', 'id')->where('payments.is_deleted', 0)->where('payments.type_id', 0);
    }

    public function bso_items(){
        return $this->hasMany(BsoItem::class, 'realized_act_id', 'id');
    }

    public function get_bsos(){
        return BsoItem::query()->whereIn('id', $this->logs->pluck('bso_id'));
    }



    public function get_link()
    {
        $link = url("/bso_acts/show_bso_act/{$this->id}/");
        if($this->type_id == 7){
            $link = url("/bso_acts/acts_implemented/details/{$this->id}/");
        }

        return $link;
    }

    public static function getActs(){
        $act = Visible::apply(BsoActs::query(), 'bso_acts', ['user_id_from', 'user_id_to', 'bso_manager_id']);
        return $act;

    }

    public static function getActId($id){
        $act = BsoActs::getActs();
        return $act->where('id', $id)->get()->first();
    }


    public static function getRealizedActs($user_id, $type_id = -1){

        $bso_act = BsoActs::whereIn('type_id', [2,5,3,4])
            ->where('user_id_from', $user_id)
            ->where('realized_state_id', 0);

        // 1 договор; 2 - БСО (Испорченые)
        if($type_id != -1){
            $bso_state_id = null;
            if($type_id == 1){
                $bso_state_id = 2;
            }elseif($type_id == 2){
                $bso_state_id = 3;
            }elseif($type_id == 3){
                $bso_state_id = 0;
            }
            if($bso_state_id){
                $bso_act->where('bso_state_id', $bso_state_id);
            }

        }

        return $bso_act;
    }

    public static function getRealizedActsTP($user_id, $type_id = -1, $point_sale_id = null){

        $bso_act = BsoActs::whereIn('type_id', [2,5,3,4])
            ->where('realized_state_id', 0);

        if($user_id){
            $bso_act->where('user_id_from', $user_id);
        }
        if($point_sale_id){
            $bso_act->where('curr_tp_id', $point_sale_id);
        }


        return $bso_act;
    }


    public static function createRealizedActs($user_id, $type_id, $act_dop_name = '', $point_sale_id = 1, $new_point_sale_id=null){

        // 1 договор; 0 - БСО (Испорченые)
        $act_name = '';
        $act_pref = 'P';
        $type_act_id = 0;



        if($type_id == 1){
            $bso_state_id = 2;
            $act_name = 'Акт на проданные договоры';
            $act_pref = 'D';
            $type_act_id = 5;
        }elseif($type_id == 2){


            $bso_state_id = 2;
            $act_name = 'Акт с '.$act_dop_name;
            $act_pref = 'ТП';
            $type_act_id = 2;


        }elseif($type_id == 3){
            $bso_state_id = 0;
            $act_name = 'Акт на чистые БСО';
            $act_pref = 'C';
            $type_act_id = 3;
        }elseif($type_id == 4){
            $bso_state_id = 3;
            $act_name = 'Акт на испорченые БСО';
            $act_pref = 'I';
            $type_act_id = 4;
        }

        $act_number = BsoActs::where('type_id', $type_act_id)->count('id')+1;
        $act_title = $act_pref.str_pad( $act_number, 6, '0', STR_PAD_LEFT );

        $time_create = date( 'Y-m-d H:i:s' );
        $target_date = date( 'Y-m-d H:i:s', strtotime( '+2 weekdays' ) );

        $bso_act = BsoActs::create([
            'type_id' => $type_act_id,
            'time_create' => $time_create,
            'target_date' => $target_date,
            'act_number' => $act_title,
            'act_number_int' => $act_number,
            'user_id_from' => $user_id,
            'bso_state_id' => $bso_state_id,
            'realized_state_id' => 0,
            'act_name' => $act_name,
            'bso_manager_id' => auth()->id(),

        ]);

        if($type_id == 2){
            $bso_act->curr_tp_id = $point_sale_id;
            $bso_act->tp_id = $new_point_sale_id;
            $bso_act->save();
        }


        return $bso_act;
    }



    public function setBsoRealizedActs($bso_arr, $bso_user_from){

        $bso_act = $this;

        foreach ($bso_arr as  $bso_id){
            $bso_log = BsoLogs::setLogs($bso_id, $bso_act->bso_state_id, 9, $bso_act->id, auth()->id());
        }


        $bsos = implode( ',', $bso_arr );
        \DB::update("update bso_items set realized_act_id='{$bso_act->id}' where id in ($bsos) and realized_act_id=0");

        return $bso_act;
    }


    public function acceptRealizedActs()
    {
        $bso_act = $this;
        $items = [];
        $location_id = 4;
        $point_sale_id = null;

        if($bso_act->type_id == 2){

            $bsos = $bso_act->bso_items;
            foreach ($bsos as $bso){
                $items[] = $bso->id;
            }

            $point_sale_id = $bso_act->tp_id;

        }else{
            if($bso_act->bso_state_id == 2){ //проданные договоры
                $payments = $bso_act->payments;
                foreach ($payments as $payment){
                    $items[] = $payment->bso_id;
                    if($payment->bso_receipt_id > 0 ) $items[] = $payment->bso_receipt_id;
                }
            }

            if($bso_act->bso_state_id == 3){ //испорченые БСО
                $bsos = $bso_act->bso_items;
                foreach ($bsos as $bso){
                    $items[] = $bso->id;
                }
            }

            if($bso_act->bso_state_id == 0){ //чистые БСО
                $bsos = $bso_act->bso_items;
                $location_id = 0;
                foreach ($bsos as $bso){
                    $items[] = $bso->id;
                }
            }
        }



        $bso_act->acceptBSORealizedActs($items, $location_id, $point_sale_id);
        return true;
    }


    public function deleteRealizedActs()
    {
        $bso_act = $this;

        if($bso_act->type_id == 2){
            $bsos = $bso_act->bso_items;

            $items = [];
            foreach ($bsos as $bso){
                $items[] = $bso->id;
            }

            if($items) $bso_act->deleteBSORealizedActs($items);

        }else{
            if($bso_act->bso_state_id == 2){ //проданные договоры
                $payments = $bso_act->payments;

                $items = [];
                foreach ($payments as $payment){
                    $items[] = $payment->id;
                }

                if($items) $bso_act->deletePaymentRealizedActs($items);
            }

            if($bso_act->bso_state_id == 3 || $bso_act->bso_state_id == 0){ //испорченые БСО
                $bsos = $bso_act->bso_items;

                $items = [];
                foreach ($bsos as $bso){
                    $items[] = $bso->id;
                }

                if($items) $bso_act->deleteBSORealizedActs($items);
            }
        }



        $bso_act->delete();

        return true;
    }


    public function deleteItemsRealizedActs($items)
    {
        $bso_act = $this;

        if($bso_act->type_id == 2){

            $bso_act->deleteBSORealizedActs($items);

        }else{
            if($bso_act->bso_state_id == 2){ //проданные договоры
                $bso_act->deletePaymentRealizedActs($items);
            }

            if($bso_act->bso_state_id == 0){ //чистые БСО
                $bso_act->deleteBSORealizedActs($items);
            }

            if($bso_act->bso_state_id == 3){ //испорченые БСО
                $bso_act->deleteBSORealizedActs($items);
            }
        }




        return true;
    }

    public function deletePaymentRealizedActs($payments_arr)
    {
        $bso_act = $this;

        $payments = implode( ',', $payments_arr );
        $payments_sql = \DB::select("select id, bso_receipt_id from payments where id in ($payments)");

        $bso = [];
        foreach ($payments_sql as $payment){
            $bso[] = $payment->id;
            if((int)$payment->bso_receipt_id > 0) $bso[] = $payment->bso_receipt_id;
        }

        $bso_act->deleteBSORealizedActs($bso);

        \DB::update("update payments set realized_act_id=0 where id in ($payments) and realized_act_id={$bso_act->id}");
        return true;
    }

    public function deleteBSORealizedActs($bso_arr)
    {
        $bso_act = $this;

        foreach ($bso_arr as  $bso_id){
            $bso_log = BsoLogs::setLogs($bso_id, $bso_act->bso_state_id, 7, $bso_act->id, auth()->id());
        }

        $bsos = implode( ',', $bso_arr );
        \DB::update("update bso_items set realized_act_id='0' where id in ($bsos) and realized_act_id={$bso_act->id}");

        return true;
    }


    public function acceptBSORealizedActs($bso_arr, $location_id = 4, $point_sale_id = null)
    {


        $bso_act = $this;
        $user_id = auth()->id();

        foreach ($bso_arr as  $bso_id){
            $bso_log = BsoLogs::setLogs($bso_id, $bso_act->bso_state_id, $location_id, $bso_act->id, $user_id, $user_id);
        }

        $dop_point = '';
        if($point_sale_id){
            $dop_point = "point_sale_id = {$point_sale_id}, realized_act_id=0,";

        }

        $user_org_id = (int)auth()->user()->organization_id;
        $bsos = implode( ',', $bso_arr );
        $sql = "update bso_items set transfer_to_org_time=now(), location_id = $location_id, $dop_point user_id = $user_id, user_org_id=$user_org_id where id in ($bsos) ";
        \DB::update($sql);

        $bso_act->user_id_to = $user_id;
        $bso_act->realized_state_id = 1;
        $bso_act->save();
        return true;

    }


}
