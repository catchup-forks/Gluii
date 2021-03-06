<?php namespace App\Repositories;

use App\Models\Comment;
use App\Models\Status;

class StatusRepository extends AbstractRepository implements StatusRepositoryInterface
{

    /**
     * Retrieve a Status by their unique identifier.
     *
     * @param  integer  $identifier
     * @return Status|null
     */
    public function findStatusById($id)
    {
        return Status::findOrFail($id);
    }

    /**
     * Retrieve a Status by ID with Subscribers eager-loaded
     *
     * @param  integer $id
     * @return Status|null
     */
    public function findStatusByIdWithSubscribers($id)
    {
        return Status::with('subscribers')->find($id);
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
    | Status Feeds
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Get all Statuses from all Users for home page
     *
     * @param  integer $limit
     * @return Collection
     */
    public function allStatuses($limit = 15)
    {
        return Status::loadRelationships()
            ->orderBy('statuses.updated_at', 'DESC')
            ->paginate($limit);
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
     * @param  integer $userId
     * @param  string $status
     * @return Status
     */
    public function postStatus($profileUserId, $userId, $status)
    {
        return Status::create([
            'profile_user_id'    => $profileUserId,
            'user_id'            => $userId,
            'body'                => $status,
        ]);
    }

    /**
     * Deletes a Status including Subscriptions, CommentLikes, Comments, and StatusLikes
     *
     * @param  integer $id
     * @return bool
     */
    public function deleteStatusById($id)
    {
        $status = Status::with([
                'subscribers',
                'likes',
                'comments',
                'comments.likes'
            ])
            ->find($id);

        if (! $status) {
            return false;
        }

        // Delete the Subscriptions, CommentLikes, Comments, StatusLikes, then the Status
    }

    /*
    |--------------------------------------------------------------------------
    | Status Subscribers
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Subscribe Users for a new Status
     *
     * @param  Status $status
     * @return Collection
     */
    public function subscribeNewStatus(Status $status)
    {
        $subscribers = array_unique([$status->profile_user_id, $status->user_id]);

        return $status->subscribers()->sync($subscribers);
    }

    /**
     * Return the first User subscribed to a Status or a new one
     *
     * @param  Status  $status
     * @param  integer $userId
     * @return User
     */
    public function subscriberFirstOrNew(Status $status, $userId)
    {
        if ($subscriber = $this->subscriberCheck($status, $userId)) {
            return $subscriber;
        }

        return $status->subscribers()->attach($userId);
    }

    /**
     * Check if a User is subscribed to a Status
     *
     * @param  Status  $status
     * @param  integer $userId
     * @return bool|User
     */
    public function subscriberCheck(Status $status, $userId)
    {
        $subscriber = $status->subscribers()
            ->wherePivot('user_id', '=', $userId)
            ->first();

        if (! $subscriber) {
            return false;
        }

        return $subscriber;
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
            'status_id'    => $statusId,
            'user_id'    => $userId,
            'body'        => $body,
        ]);
    }

    /**
     * Like a Comment
     *
     * @param  User    $user
     * @param  integer $commentId
     * @return mixed
     */
    public function likeComment(\App\Models\User $user, $commentId)
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
    public function unlikeComment(\App\Models\User $user, $commentId)
    {
        return $user->likedcomments()->detach($commentId);
    }
}
