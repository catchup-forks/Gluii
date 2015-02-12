<?php namespace App\Repositories;

use App\Comment;
use App\Status;

class StatusRepository extends AbstractRepository {

	/**
	 * Get all Statuses from all Users for home page
	 *
	 * @param  integer $limit
	 * @return Collection
	 */
	public function allStatuses($limit = 10)
	{
		return Status::with([
				'profileuser' => function($q)
				{
					$q->addSelect('id', 'first_name', 'last_name', 'email');
				},
				'author' => function($q)
				{
					$q->addSelect('id', 'first_name', 'last_name', 'email');
				},
				'likes' => function($q)
				{
					$q->select('users.id', 'first_name', 'last_name')
						->withPivot('user_id');
				},
				'comments' => function($q)
				{
					$q->orderBy('id', 'ASC');
				},
				'comments.author' => function($q)
				{
					$q->addSelect('id', 'first_name', 'last_name', 'email');
				},
				'comments.likes' => function($q)
				{
					$q->select('users.id', 'first_name', 'last_name')
						->withPivot('user_id');
				},
			])
			->orderBy('statuses.updated_at', 'DESC')
			->limit($limit);
	}

	/**
	 * Get all statuses associated with a user.
	 *
	 * @param User $user
	 * @return mixed
	 */
	public function getAllForUser(User $user)
	{
		return $user->statuses()
			->with('user')
			->latest()
			->get();
	}

	/**
	 * Get the feed for a user.
	 *
	 * @param User $user
	 * @return mixed
	 */
	public function getFeedForUser(User $user)
	{
		$userIds = $user->followedUsers()->lists('followed_id');
		$userIds[] = $user->id;

		return Status::with('comments')
			->whereIn('user_id', $userIds)
			->latest()
			->get();
	}

	/**
	 * Retrieve a Status by their unique identifier.
	 *
	 * @param  integer  $identifier
	 * @return Status\null
	 */
	public function findStatusById($id)
	{
		return Status::find($id);
	}

	/**
	 * Retrieve a Comment by their unique identifier.
	 *
	 * @param  integer  $identifier
	 * @return Comment\null
	 */
	public function findCommentById($id)
	{
		return Comment::find($id);
	}

	/*
	|--------------------------------------------------------------------------
	| Status Add/Edit/Delete & Like
	|--------------------------------------------------------------------------
	|
	|
	*/

	/**
	 * Post a new Status on a User's profile
	 *
	 * @param  integer $profileUserId
	 * @param  integer $authorId
	 * @param  string $status
	 * @return Status
	 */
	public function postStatus($profileUserId, $authorId, $status)
	{
		return Status::create([
			'profile_user_id'	=> $profileUserId,
			'author_id'			=> $authorId,
			'body'				=> $status,
		]);
	}

	/**
	 * Like a Status
	 *
	 * @param  User    $user
	 * @param  integer  $statusId
	 * @return bool
	 */
	public function likeStatus(\App\User $user, $statusId)
	{
		return $user->likedstatuses()->attach($statusId);
	}

	/**
	 * Unfollow a Status
	 *
	 * @param $userIdToUnfollow
	 * @param User $user
	 * @return mixed
	 */
	public function unlikeStatus(\App\User $user, $statusId)
	{
		return $user->likedstatuses()->detach($statusId);
	}

	/*
	|--------------------------------------------------------------------------
	| Comment Add/Edit/Delete & Like
	|--------------------------------------------------------------------------
	|
	|
	*/

	/**
	 * Leave a Comment on a Status
	 *
	 * @param  integer $statusId
	 * @param  integer $userId
	 * @param  string $body
	 * @return Comment
	 */
	public function postNewComment($statusId, $userId, $body)
	{
		return Comment::create([
			'status_id'	=> $statusId,
			'user_id'	=> $userId,
			'body'		=> $body,
		]);
	}

	/**
	 * Like a Comment
	 *
	 * @param  User    $user
	 * @param  integer $commentId
	 * @return mixed
	 */
	public function likeComment(\App\User $user, $commentId)
	{
		return $user->likedcomments()->attach($commentId);
	}

	/**
	 * Unfollow a Comment
	 *
	 * @param User    $user
	 * @param integer $commentId
	 * @return mixed
	 */
	public function unlikeComment(\App\User $user, $commentId)
	{
		return $user->likedcomments()->detach($commentId);
	}

}