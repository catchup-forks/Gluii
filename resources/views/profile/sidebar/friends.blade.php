<section class="panel panel-default m-b">
	<header class="panel-heading">
		<a href="#" class="font-bold text-black">Friends</a>
		({{ number_format($user->friends->count()) }})
	</header>
	<div class="panel-body no-padder">
		<div class="row userprofile-sidebar-list no-gutter">
			@if(! $user->friends->isEmpty())
				@foreach($user->friends->take(12) as $friend)
					<div class="col-lg-4">
						<a href="{{ route('user/view', $friend->username) }}">
							{!! $friend->present()->photoThumb('thumb-md', ['class' => 'userprofile-sidebar-list-item']) !!}
							<div class="sidebar-friend">
								<div class="friend-name">{!! $friend->present()->name !!}</div>
							</div>
						</a>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</section>