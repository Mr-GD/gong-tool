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

    protected $fillable = [
        'ext',
        'path',
        'created_at',
    ];

    public function beforeSave()
    {
        // TODO: Implement beforeSave() method.
    }

    public function afterSave()
    {
        // TODO: Implement afterSave() method.
    }

    public function beforeDelete()
    {
        // TODO: Implement beforeDelete() method.
    }

    public function afterDelete()
    {
        // TODO: Implement afterDelete() method.
    }
}
