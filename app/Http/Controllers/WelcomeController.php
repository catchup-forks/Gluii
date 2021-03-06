<?php namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;

class WelcomeController extends BaseController
{

    /*
    |--------------------------------------------------------------------------
    | Welcome Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function getTestEvent()
    {
        \Event::fire(new App\Events\Users\UserRegistered(1));

        return view('welcome');
    }
}
