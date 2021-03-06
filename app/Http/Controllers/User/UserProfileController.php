<?php namespace App\Http\Controllers\User;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\ActivityRepositoryInterface;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class UserProfileController extends BaseController
{

    /**
     * User Repository
     *
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * Activity Repository
     *
     * @var ActivityRepository $activityRepository
     */
    protected $activityRepository;

    /**
     * Instantiate the Object
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, ActivityRepositoryInterface $activityRepository)
    {
        parent::__construct();

        $this->userRepository       = $userRepository;
        $this->activityRepository   = $activityRepository;
    }

    /**
     * Display the specified resource.
     *
     * @param  string    $username
     * @return Response
     */
    public function getViewTimeline($username)
    {
        $user = $this->userRepository->loadUserTimeline($username);

        if (is_numeric($username) && ! is_numeric($user->username)) {
            return redirect()->route(\Route::currentRouteName(), $user->username);
        }

        $activities = $this->activityRepository->getFeedByUserId($user->id);
        // dd($activities);
        // $activities = $user->activities;

        if (! $user) {
            return redirect()->route('home')->withErrors(['User Error' => 'User not found!']);
        }

        return view()->make('profile.feed', compact('user', 'activities'));
    }

    /**
     * Display a User's photos
     *
     * @param  string    $username
     * @return Response
     */
    public function getViewPhotos(PhotoRepositoryInterface $photoRepository, $username)
    {
        $user   = $this->userRepository->loadUserTimeline($username);
        $photos = $photoRepository->loadUserPhotos($username);

        if (! $photos) {
            return redirect()->route('home')->withErrors(['Photo Error' => 'User has no photos!']);
        }

        return view()->make('profile.photos', compact('user', 'photos'));
    }

    /**
     * Display a User's Videos
     *
     * @param  string    $username
     * @return Response
     */
    public function getViewVideos($username)
    {
        $user   = $this->userRepository->loadUserTimeline($username);
        $videos = [];

        return view()->make('profile.videos', compact('user', 'videos'));
    }

    /**
     * Display a User's Calendar
     *
     * @param  string    $username
     * @return Response
     */
    public function getViewCalendar($username)
    {
        $user       = $this->userRepository->loadUserTimeline($username);
        $calendar   = [];

        return view()->make('profile.calendar', compact('user', 'calendar'));
    }
}
