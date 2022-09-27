<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


class ContractsScorings extends Model {


    protected $table = 'contracts_scorings';

    protected $guarded = ['id'];

    public $timestamps = true;


    const TYPE = [
        1 => 'АвтоКод',
        2 => 'AudaTex',
        3 => 'SpectrumData',
    ];

    const QUERY_TYPE = [
        1 => ['GRZ'=>'Гос номер', 'VIN'=>'VIN'],
        2 => ['VIN'=>'VIN'],
        3 => ['FIO'=>'ФИО и дата рожрения'],
    ];


    public static function getContractsScorings($contract, $type_id, $query_type_id, $query)
    {
        self::where('contract_id', $contract->id)->where('type_id', $type_id)->update(['is_actual'=>0]);
        $scoringData = self::where('contract_id', $contract->id)
            ->where('type_id', $type_id)
            ->where('query_type_id', $query_type_id)
            ->where('query', $query);

        $scoring = $scoringData->get()->first();
        if(!$scoring){
            $scoring = self::create([
                'contract_id' => $contract->id,
                'type_id' => $type_id,
                'query_type_id' => $query_type_id,
                'query' => $query,
                'title' => self::TYPE[$type_id].': '.$query,
                'state_id' => 0,
                'is_actual' => 0,
            ]);
        }

        return $scoring;
    }


}
