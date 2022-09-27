<?php

namespace App\Models\Contracts;

use App\Domain\Processes\Scenaries\Finance\FinanceDocuments;
use App\Domain\Processes\Scenaries\Finance\FinanceQuestion;
use App\Helpers\Visible;
use App\Models\Contracts\Orders\Finance\OrdersFinance;
use App\Models\Contracts\Orders\Finance\OrdersFinanceNotes;
use App\Models\Contracts\Orders\ProcessingOrdersLogs;
use App\Models\Directories\BanksCompanies;
use App\Models\Directories\Products;
use App\Models\Directories\ProductsPrograms;
use App\Models\Directories\Suppliers;
use App\Models\Directories\SuppliersPrograms;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Contracts
 * @property ContractsCalculation $selected_calculation
 * @property ContractMessage $errors
 * @property ContractMessage $messages
 * @property Collection $drivers
 * @property BsoSuppliers $bso_supplier
 */
class UnderwritingCheckUserLog extends Model {



    protected $table = 'matching_underwriting_user_log';
    protected $guarded = ['id'];
    public $timestamps = false;


    public function user(){
        return $this->hasOne(User::class, 'id' ,'user_id');
    }



}
