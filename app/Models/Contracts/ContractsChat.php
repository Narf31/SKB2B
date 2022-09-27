<?php

namespace App\Models\Contracts;

use App\Classes\Export\TagModels\Contracts\TagContracts;
use App\Domain\Entities\Payments\EPayment;
use App\Domain\Samplers\Contracts\TabsVisibility;
use App\Helpers\Visible;
use App\Models\Actions\ExpectedPayments;
use App\Models\Actions\PaymentAccept;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicy;
use App\Models\Directories\InstallmentAlgorithms;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Directories\ProductsPrograms;
use App\Models\Directories\TypeBso;
use App\Models\File;
use App\Models\Orders\Inspection;
use App\Models\Orders\InspectionComments;
use App\Models\Orders\InspectionOrdersLogs;
use App\Models\Settings\Bank;
use App\Models\Settings\InstallmentAlgorithmsList;
use App\Models\User;
use App\Services\Front\IntegrationFront;
use App\Services\Pushers\PusherRepository;
use App\Traits\Models\ActiveConstTrait;
use App\Traits\Models\CustomRelationTrait;
use App\Traits\Models\GetRelatedTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Directories\Products;
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
class ContractsChat extends Model {


    const FILES_DOC = 'contracts/chats';

    protected $table = 'contracts_chat';

    protected $guarded = ['id'];

    protected $dates = ['date_sent', 'date_receipt'];

    const STATUS_SENT = 0;
    const STATUS_RECEIPT = 1;

    const PLAYER = 1;
    const EMPLOYEE = 0;

    public $timestamps = false;

    public function contract(){
        return $this->belongsTo(Contracts::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function receipt()
    {
        return $this->hasOne(User::class, 'id', 'receipt_id');
    }



    public function player()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    public function getStatusTitleAttribute()
    {
        return $this->status == self::STATUS_RECEIPT ? 'Прочитано' : 'Не прочитано';
    }


    public static function saveMsg($contract_id, $text, $is_player = 0)
    {
        $chat = new ContractsChat();
        $chat->contract_id = $contract_id;
        $chat->sender_id = auth()->id();
        $chat->text = $text;
        $chat->date_sent = getDateTime();
        $chat->status = 0;
        $chat->is_player = $is_player;
        $chat->save();

        //if($is_player == 0){
            $Pusher = new PusherRepository();
            $Pusher->triggerContractChat($chat);
        //}






        return $chat;
    }


    public function getFileArray()
    {
        $result = [];
        if($this->is_file == 1){
            $file = $this->file;

            $view_url = "/images/extensions/".mb_strtolower($file->ext).".png";

            if (in_array($file->ext, ['jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG']))
            {
                $view_url = $file->url;
            }

            $result = [
                'ext' => $file->ext,
                'name' => $file->name,
                'view_url' => $view_url,
                'url' => $file->url,
            ];
        }

        return $result;
    }

}
