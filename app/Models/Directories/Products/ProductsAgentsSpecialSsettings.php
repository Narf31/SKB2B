<?php

namespace App\Models\Directories\Products;

use App\Models\File;
use App\Models\Security\Security;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use App\Processes\Tariff\Settings\Product\TariffMigrants;
use App\Processes\Tariff\Settings\Product\TariffNSPrisoners;
use App\Processes\Tariff\Settings\Product\TariffPrf;
use App\Processes\Tariff\Settings\Product\TariffVzr;
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
class ProductsAgentsSpecialSsettings extends Model
{

    protected $table = 'products_agents_special_settings';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function saveJson($request, $slug){

        $json = null;
        if($this->json){
            $json = \GuzzleHttp\json_decode($this->json, true);
        }

        if($slug == 'vzr'){
            $json = TariffVzr::saveTariff($json, $request);
        }

        if($slug == 'prf'){
            $json = TariffPrf::saveTariff($json, $request);
        }

        if($slug == 'migrants'){
            $json = TariffMigrants::saveTariff($json, $request);
        }

        if($slug == 'nsPrisoners'){
            $json = TariffNSPrisoners::saveTariff($json, $request);
        }

        $this->json = \GuzzleHttp\json_encode($json);
        $this->save();

        if(strlen($this->json)>0){
            return true;
        }

        return false;
    }

}
