<?php namespace App\Events\Auth;

use App\Models\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Event
{

    use SerializesModels;

    /**
     * @var App\Models\User $user
     */
    public $user;

    /**
     * Construct the Event
     *
     * @param App\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
