<?php

namespace App\Models\MailsNotification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MailsNotification extends Model
{
    protected $table = 'mails_notification';

    protected $guarded = ['id'];

    public $timestamps = true;

}
