<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Background;

use common\Model\MysqlModel;
use common\Trait\File\RemoteFileHandle;

/**
 * Class LoginBackgroundPicture
 *
 * @property int $id
 * @property string $url
 * @property bool|null $storage_mode
 * @property int $created_at
 *
 * @package App\Models
 */
class LoginBackgroundPicture extends MysqlModel
{
    use RemoteFileHandle;

    protected $table = 'login_background_picture';
    protected $perPage = 30;
    public $timestamps = false;

    protected $casts = [
        'storage_mode' => 'int'
    ];

    protected $fillable = [
        'url',
        'storage_mode',
        'created_at',
    ];
}
