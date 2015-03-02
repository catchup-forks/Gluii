<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * News Feeds
 */
Route::get('/', ['as' => 'home', 'uses' => 'HomeController@getIndex']);

Route::group(['prefix' => 'feed'], function () {
	# Families
	Route::get('families', ['as' => 'feed/families', 'uses' => 'HomeController@getIndex']);
	# Areas
	Route::get('areas', ['as' => 'feed/areas', 'uses' => 'HomeController@getIndex']);
	# Influencers
	Route::get('influencers', ['as' => 'feed/influencers', 'uses' => 'HomeController@getIndex']);
});

/**
 * Authentication
 *
 * @namespace Auth
 */
Route::group(['namespace' => 'Auth'], function () {
	# Log In
	Route::get('login', ['as' => 'auth/login', 'uses' => 'AuthController@getLogin']);
	Route::post('login', 'AuthController@postLogin');
	# Log Out
	Route::get('logout', ['as' => 'auth/logout', 'uses' => 'AuthController@getLogout']);

	# Forgot Password
	Route::get('forgot-password', ['as' => 'auth/forgot-password', 'uses' => 'PasswordController@getForgotPassword']);
	Route::post('forgot-password', 'PasswordController@postForgotPassword');
	# Reset Password
	Route::get('reset-password/{id}/{code}', ['as' => 'auth/reset-password', 'uses' => 'PasswordController@getResetPassword']);
	Route::post('reset-password/{id}/{code}', 'PasswordController@postResetPassword');

	# Register
	Route::get('register', ['as' => 'auth/register', 'uses' => 'AuthController@getRegister']);
	Route::post('register', 'AuthController@postRegister');
	# Activate Account
	Route::get('activate/{id}/{code}', [ 'as' => 'auth/activate', 'uses' => 'ActivationsController@activate' ])->where('id', '\d+');
});

/**
 * User Profile Viewing
 *
 * @namespace User
 */
Route::group(['prefix' => 'u/{id}', 'namespace' => 'User'], function () {
	# View Timeline
	Route::get('/', ['as' => 'user/view', 'uses' => 'UserProfileController@getViewTimeline']);
	# Photos Index
	Route::get('photos', ['as' => 'user/photos', 'uses' => 'UserProfileController@getViewPhotos']);
	# Videos Index
	Route::get('videos', ['as' => 'user/videos', 'uses' => 'UserProfileController@getIndex']);
	# Calendar
	Route::get('calendar', ['as' => 'user/calendar', 'uses' => 'UserProfileController@getCalendar']);
});

/**
 * Friend & Friend Requests
 */
Route::group(['prefix' => 'friends', 'namespace' => 'User'], function () {

	# Friend Requests
	Route::group(['prefix' => 'requests'], function () {
		# Send Request
		Route::post('addnew', ['as' => 'user/request/add', 'uses' => 'FriendRequestController@postSendFriendRequest']);
		# Accept Request
		Route::get('accept', ['as' => 'user/request/accept', 'uses' => 'FriendRequestController@getAcceptFriendRequest']);
		# Deny Request
		Route::get('deny', ['as' => 'user/request/deny', 'uses' => 'FriendRequestController@getDenyFriendRequest']);
		# Cancel/Delete Request
		Route::get('cancel', ['as' => 'user/request/cancel', 'uses' => 'FriendRequestController@getRemoveFriend']);
	});
});

/**
 * Statuses
 *
 * @namespace User
 */
Route::group(['prefix' => 'statuses', 'namespace' => 'User'], function () {
	# View Single
	Route::post('{id}/view', ['as' => 'status/view', 'uses' => 'StatusController@getViewStatus']);
	# Post Status
	Route::post('post-new', ['as' => 'status/new', 'uses' => 'StatusController@postNewStatus']);
	# Like Status
	Route::post('like', ['as' => 'status/like', 'uses' => 'StatusController@postLikeStatus']);
	# Unlike Status
	Route::post('unlike', ['as' => 'status/unlike', 'uses' => 'StatusController@postUnlikeStatus']);
	# Delete Status
	Route::post('delete', ['as' => 'status/delete', 'uses' => 'StatusController@postDeleteStatus']);

	# Comments
	Route::group(['prefix' => 'comments'], function () {
		# Post Comment
		Route::post('post-new', ['as' => 'status/comment/new', 'uses' => 'StatusController@postNewComment']);
		# Like Comment
		Route::post('like', ['as' => 'status/comment/like', 'uses' => 'StatusController@postLikeComment']);
		# Unlike Comment
		Route::post('unlike', ['as' => 'status/comment/unlike', 'uses' => 'StatusController@postUnlikeComment']);
		# Delete Comment
		Route::post('delete', ['as' => 'status/comment/delete', 'uses' => 'StatusController@postDeleteComment']);
	});
});

/**
 * Notifications
 *
 * @namespace User
 */
Route::group(['prefix' => 'notifications', 'namespace' => 'User'], function () {
	# Friend Requests
	Route::get('friend-requests', ['as' => 'notifications/friend-requests', 'uses' => 'NotificationController@getFriendRequests']);
	# Messages
	Route::get('messages', ['as' => 'notifications/messages', 'uses' => 'NotificationController@getMessages']);
	# Notifications
	Route::get('notifications', ['as' => 'notifications/notifications', 'uses' => 'NotificationController@getNotifications']);
});

/**
 * Manage Photos
 *
 * @namespace Photos
 */
Route::group(['prefix' => 'photos', 'namespace' => 'Photos'], function () {
	# Photo Manager
	Route::get('photos', ['as' => 'user/manage/photos', 'uses' => 'PhotoUploadController@getIndex']);
	# Upload Pictures
	Route::get('upload', ['as' => 'user/manage/photos/upload', 'uses' => 'PhotoUploadController@getUploadPhoto']);
	Route::post('upload', 'PhotoUploadController@postUploadPhoto');

});

/*
|--------------------------------------------------------------------------
| Influencers / Families / Calendar /+ Explore
|--------------------------------------------------------------------------
|
|
*/

/**
 * Explore
 *
 * @namespace Explore
 */
Route::group(['namespace' => 'Explore', 'prefix' => 'explore'], function () {
	# Feed
	Route::get('/', ['as' => 'explore', 'uses' => 'ExploreController@getIndex']);
});

/**
 * Festivals
 *
 * @namespace Festivals
 */
Route::group(['namespace' => 'Festivals', 'prefix' => 'festivals'], function () {
	# Feed
	Route::get('/', ['as' => 'festivals', 'uses' => 'FestivalsController@getIndex']);
});

/**
 * Families
 *
 * @namespace Families
 */
Route::group(['namespace' => 'Families', 'prefix' => 'families'], function () {
	# Feed
	Route::get('/', ['as' => 'families', 'uses' => 'FamiliesController@getIndex']);
	# View Family
	Route::get('{slug}', ['as' => 'families/view', 'uses' => 'FamiliesController@getIndex']);
});

/**
 * Influencers
 *
 * @namespace Influencers
 */
Route::group(['namespace' => 'Influencers', 'prefix' => 'influencers'], function () {
	# Feed
	Route::get('/', ['as' => 'influencers', 'uses' => 'InfluencersController@getIndex']);
	# View Family
	Route::get('{slug}', ['as' => 'influencers/view', 'uses' => 'InfluencersController@getIndex']);
});

/*
|--------------------------------------------------------------------------
| Random Tests
|--------------------------------------------------------------------------
|
|
*/

# Testing Event
Route::get('test-event', ['as' => 'test-event', 'uses' => 'WelcomeController@getTestEvent']);
# Testing Email
Route::get('test-email', ['as' => 'test-email', 'uses' => 'HomeController@getTestEmail']);