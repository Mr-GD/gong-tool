<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Admin;

use common\Model\MysqlModel;

/**
 * Class Admin
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $created_at
 * @property int $updated_at
 * @property string $email
 * @property bool $email_verify
 *
 * @package App\Models
 */
class Admin extends MysqlModel
{
	protected $table = 'admin';
	protected $perPage = 30;

	protected $casts = [
		'email_verify' => 'bool'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'password',
		'email',
		'email_verify',
	];

    public function beforeSave()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
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
