<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\File;

/**
 * App\Models\Users\Scan
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Scan whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Scan whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Scan whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Scan whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Scan whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Scan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Scan extends Model {

    const FILES_DOC = 'users/scans';

    protected $table = 'users_scans';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function file() {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

}
