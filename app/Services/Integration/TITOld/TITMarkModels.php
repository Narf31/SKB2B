<?php

namespace App\Services\Integration\TITOld;

use App\Models\Organizations\Organization;
use App\Models\Settings\SettingsSystem;
use App\Models\Subject\Physical;
use App\Models\User;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Services\OData\ClientOData;
use Mockery\Exception;

class TITMarkModels
{


    public function updateMark(){

        $url = SettingsSystem::getDataParam('odata', 'url');


        if(strlen($url) > 0){
            $client = new ClientOData($url,[
                'auth' => [
                    SettingsSystem::getDataParam('odata', 'login'),
                    SettingsSystem::getDataParam('odata', 'pass')
                ],
                'timeout' => 300,
            ]);


            VehicleMarks::query()->update(['ref_key_1c' => null]);

            $data = $client->{urlencode('Catalog_КонтМаркиТС')}->get();
            if($client->isOk()) {
                $marks_1c = $data->values();
                foreach ($marks_1c as $mark_1c)
                {
                    if(isset($mark_1c['Активна']) && $mark_1c['Активна'] == true){
                        VehicleMarks::where('title', $mark_1c['Description'])->update(['ref_key_1c' => $mark_1c['Ref_Key']]);
                    }
                }

                return true;
            }
        }

        return false;
    }


    public function updateModel(){

        $url = SettingsSystem::getDataParam('odata', 'url');

        if(strlen($url) > 0){
            $client = new ClientOData($url,[
                'auth' => [
                    SettingsSystem::getDataParam('odata', 'login'),
                    SettingsSystem::getDataParam('odata', 'pass')
                ],
                'timeout' => 300,
            ]);

            $categorys = [
                'A' => 3367,
                'B' => 3366,
                'C' => 3364,
                'D' => 3372,
            ];

            VehicleModels::query()->update(['ref_key_1c' => null]);

            $data = $client->{urlencode('Catalog_КонтМодельТС')}->get();
            if($client->isOk()) {
                $models_1c = $data->values();
                foreach ($models_1c as $model_1c)
                {

                    if(isset($model_1c['Активна']) && $model_1c['Активна'] == true){

                        $category_id = (isset($categorys[$model_1c['КодКатегории']]))? $categorys[$model_1c['КодКатегории']] : null;

                        if(!$category_id){


                        }else{

                            $mark = VehicleMarks::where('ref_key_1c', $model_1c['Owner_Key'])->where('category_id', $category_id)->first();
                            if($mark){
                                $model = VehicleModels::query()->where('title', $model_1c['Description'])->where('category_id', $category_id)->first();
                                if(!$model){
                                    $model = new VehicleModels();
                                    $model->title = $model_1c['Description'];
                                    $model->mark_id = $mark->isn;
                                    $model->mark_id2 = $mark->id;
                                    $model->category_id = $category_id;
                                }
                                $model->rsa_code = $model_1c['НомерРСА'];
                                $model->ref_key_1c = $model_1c['Ref_Key'];
                                $model->save();
                            }
                        }
                    }
                }

                return true;
            }
        }

        return false;
    }




}