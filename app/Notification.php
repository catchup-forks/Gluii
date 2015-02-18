<?php namespace App;

use App\Gluii\Presenters\Setup\PresentableTrait;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

	use PresentableTrait;

	/**
	 * @var array
	 */
	protected $fillable = ['user_id', 'notification_type', 'friend_id', 'notification_route_params'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'is_read'					=> 'boolean',
		'notification_route_params'	=> 'array',
	];

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	|
	*/

	/**
	 * Relationship with User
	 *
	 * @return User
	 */
	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

	/**
	 * Relationship with Friend
	 *
	 * @return User
	 */
	public function friend()
	{
		return $this->belongsTo('App\User', 'friend_id');
	}

	/*
	|--------------------------------------------------------------------------
	| Attributes
	|--------------------------------------------------------------------------
	|
	|
	*/

	/**
	 * Attribute for notification_route_params
	 *
	 * @param  string $value
	 * @return stdClass
	 */
	public function getNotificationRouteParamsAttribute($value)
	{
		return json_decode($value);
	}

}