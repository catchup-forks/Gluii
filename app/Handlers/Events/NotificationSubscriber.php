<?php namespace App\Handlers\Events;

use App\Events\Statuses\StatusReceivedNewComment;
use App\Events\Statuses\UserReceivedNewStatus;
use App\Repositories\NotificationRepositoryInterface;

class NotificationSubscriber {

	/**
	 * Notify subscribed Users of a new Comment
	 *
	 * @param  StatusReceivedNewComment $event
	 * @return void
	 */
	public function whenStatusReceivedNewComment(StatusReceivedNewComment $event, NotificationRepositoryInterface $repository)
	{
		$subscribers = $event->status->subscribers->lists('id');

		// Remove the User that posted the Comment from being notified
		$subscribers = array_diff($subscribers, [$event->fromId]);

		$repository->pushMany($subscribers, 'status.comment', $event->fromId, ['id' => $event->status->id]);
	}

	/**
	 * When a User receives a Status on their wall
	 *
	 * @param  UserReceivedNewStatus $event
	 * @return void
	 */
	public function whenUserReceivedNewStatus(UserReceivedNewStatus $event, NotificationRepositoryInterface $repository)
	{
		// dd($event);
	}

	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param  Illuminate\Events\Dispatcher  $events
	 * @return array
	 */
	public function subscribe($events)
	{
		$events->listen(\App\Events\Statuses\StatusReceivedNewComment::class,
			'App\Handlers\Events\NotificationSubscriber@whenStatusReceivedNewComment');
		$events->listen(\App\Events\Statuses\UserReceivedNewStatus::class,
			'App\Handlers\Events\NotificationSubscriber@whenUserReceivedNewStatus');
	}

}