<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

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
}
