<?php

namespace App\Models\Contracts;

use App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto;
use Illuminate\Database\Eloquent\Model;

class ObjectInsurer extends Model
{
    protected $table = 'object_insurer';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function auto(){
        return $this->hasOne(ObjectInsurerAuto::class, 'object_insurer_id', 'id');
    }


    public function data(){

        $data = null;


        if($this->type == 1){
            if($this->auto){
                $data = $this->auto;
            }else{
                $data = new ObjectInsurerAuto();
                $data->object_insurer_id = $this->id;
            }
        }


        return $data;
    }

    public static function saveOrCreateObject($data)
    {
        $object = ObjectInsurer::query();

        if(isset($data->id) && (int)$data->id>0){
            $object = ObjectInsurer::find($data->id);
        }else{
            $object = ObjectInsurer::create(['title'=> $data->title]);
        }

        $object->saveDataObject($data);

        return $object->id;
    }



    public function saveDataObject($data)
    {
        $object = $this;

        if((int)$data->type == 1)
        {
            $auto = ObjectInsurerAuto::where('object_insurer_id', $object->id)->get()->first();
            if(!$auto){
                $auto = ObjectInsurerAuto::create([
                    'object_insurer_id' => $object->id,
                ]);
            }
        }


        return $object;
    }



}
