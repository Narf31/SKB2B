<?php

namespace App\Models\Orders;

use App\Models\BSO\BsoItem;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicyGroup;
use App\Models\Directories\Products;
use App\Models\File;
use App\Models\Reports\ReportOrders;
use App\Models\Security\Security;
use App\Models\Settings\FinancialGroup;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OrdersLogs extends Model
{


    protected $table = 'orders_logs';

    protected $guarded = ['id'];

    public $timestamps = false;





    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


}
