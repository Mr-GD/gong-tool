<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use common\Model\MysqlModel;

/**
 * Class Kodbox
 *
 * @property int $id
 * @property string $ext
 * @property int $created_at
 * @property string $path
 *
 * @package App\Models
 */
class Kodbox extends MysqlModel
{
    protected $table = 'kodbox';
    protected $perPage = 30;
    public $timestamps = false;

    protected $fillable = [
        'ext',
        'path',
        'created_at',
    ];
}
