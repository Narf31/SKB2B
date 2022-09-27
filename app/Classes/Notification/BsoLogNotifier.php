<?php
namespace App\Classes\Notification;


use App\Classes\Notification\BSO\StandardBSOSampler;
use App\Classes\Notification\Contracts\ToAgentSampler;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Settings\Notification;


class BsoLogNotifier{

    public $notification;
    public $log;


    public function __construct($param){

        if($param instanceof Notification){

            $this->notification = $param;
            $this->log = $param->bso_log;

        }elseif($param instanceof BsoLogs){

            $this->log = $param;
        }
    }


    public function get_config(){
        $config = [
            0 => ['template' => 'bso.in_org',],
            1 => [
                'template' => 'bso.to_agent',
                'sampler' => ToAgentSampler::class
            ],
            2 => ['template' => 'bso.to_sk',],
            3 => ['template' => 'bso.to_courier',],
            4 => ['template' => 'bso.from_agent',],
            5 => ['template' => 'bso.unleashed',],
            6 => ['template' => 'bso.remove_from_act',],
            7 => ['template' => 'bso.remove_from_act2',],
            8 => ['template' => 'bso.remove_from_report',],
            9 => ['template' => 'bso.included_in_act',],
            10 => ['template' => 'bso.included_in_report',],
            11 => ['template' => 'bso.change_kv',],
            12 => ['template' => 'bso.payed',],
            13 => ['template' => 'bso.accept',],
            14 => ['template' => 'bso.untied_receipt',],
            100 => ['template' => 'bso.reserve',],
            101 => ['template' => 'bso.remove_from_reserve',],
        ];

        return isset($config[$this->log->location_id]) ? $config[$this->log->location_id] : [];
    }

    public function notify(){

        $bso_item = BsoItem::query()->where('id', $this->log->bso_id);
        $sampler = isset($this->get_config()['sampler']) ? $this->get_config()['sampler'] : StandardBSOSampler::class;

        if($users = $sampler::sample($bso_item->first())){
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