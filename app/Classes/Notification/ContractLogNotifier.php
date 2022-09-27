<?php
namespace App\Classes\Notification;

use App\Classes\Notification\Contracts\CorrectedSampler;
use App\Classes\Notification\Contracts\StandardContractSampler;
use App\Classes\Notification\Contracts\ToCheckSampler;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsLogs;
use App\Models\Settings\Notification;


class ContractLogNotifier{

    public $notification;
    public $log;

    public function __construct($param){

        if($param instanceof Notification){

            $this->notification = $param;
            $this->log = $param->contract_log;

        }elseif($param instanceof ContractsLogs){

            $this->log = $param;
        }

    }

    public function get_config(){
        $config = [
            -1  => [
                'template' => 'contracts.deleted',
            ],
            0   => [
                'template' => 'contracts.created',
            ],
            1   => [
                'template' => 'contracts.to_check',
                'sampler' => ToCheckSampler::class
            ],
            2   => [
                'template' => 'contracts.corrected',
                'sampler' => CorrectedSampler::class
            ],
            3   => [
                'template' => 'contracts.proof',
            ],
            4   => [
                'template' => 'contracts.released',
            ],
            5   => [
                'template' => 'contracts.checkout',
            ]
        ];

        return isset($config[$this->log->status_id]) ? $config[$this->log->status_id] : [];
    }

    public function notify(){

        $sampler = isset($this->get_config()['sampler']) ? $this->get_config()['sampler'] : StandardContractSampler::class;

        $contract = Contracts::query()->where('id', $this->log->contract_id);

        if($users = $sampler::sample($contract->first())){
            foreach($users as $user){
                Notification::create([
                    'user_id' => $user->id,
                    'contract_log_id' => $this->log->id,
                ]);
            }
        }


    }


    public function display(){



        if(isset($this->get_config()['template'])){


            $template = "settings.notifications.{$this->get_config()['template']}";


            if(view()->exists($template)){

                return view($template, [
                    'log' => $this->log,
                    'notification' => $this->notification,
                ])->render();
            }
        }

        return "";

    }



}