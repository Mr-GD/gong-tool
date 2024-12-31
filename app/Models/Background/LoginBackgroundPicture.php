<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Background;

use Illuminate\Database\Eloquent\Model;

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
class LoginBackgroundPicture extends Model
{
	protected $table = 'login_background_picture';
	protected $perPage = 30;
	public $timestamps = false;

	protected $casts = [
		'storage_mode' => 'bool'
	];

	protected $fillable = [
		'url',
		'storage_mode'
	];
}
