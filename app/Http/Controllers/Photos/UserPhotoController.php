<?php namespace App\Http\Controllers\Photos;

use App\Http\Controllers\BaseController;
use App\Repositories\PhotoRepositoryInterface;
use Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class UserPhotoController extends BaseController {

	/**
	 * Photo Repository
	 *
	 * @var PhotoRepository $repository
	 */
	protected $repository;

	/**
	 * Instantiate the Object
	 *
	 * @param PhotoRepository $repository
	 */
	public function __construct(PhotoRepositoryInterface $repository)
	{
		parent::__construct();

		$this->repository = $repository;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getViewPhotos($userId)
	{
		$user = $this->repository->loadPhotos($userId);

		if(! $user)
			return redirect()->route('home')->withErrors(['Photo Error' => 'Photo not found!']);

		return view()->make('profile.photos', compact('user'));
	}
}