<?php

namespace App\Models\Reports;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Organizations\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReportPayments extends Model {


    protected $table = 'reports_payments';
    public $timestamps = true;
    protected $guarded = ['id'];



    public function contract(){
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function payment(){
        return $this->hasOne(Payments::class, 'id', 'payment_id');
    }

    public function agent(){
        return $this->hasOne(User::class, 'id', 'agent_id');
    }

    public function agent_parent(){
        return $this->hasOne(User::class, 'id', 'agent_parent_id');
    }

    public function agent_curator(){
        return $this->hasOne(User::class, 'id', 'agent_curator_id');
    }

    public function agent_organization(){
        return $this->hasOne(Organization::class, 'id', 'agent_organization_id');
    }

    public function reports_border(){
        return $this->hasOne(ReportOrders::class,'id', 'reports_order_id');
    }

    public function reports_dvoy(){
        return $this->hasOne(ReportOrders::class, 'id', 'reports_dvou_id');
    }

}