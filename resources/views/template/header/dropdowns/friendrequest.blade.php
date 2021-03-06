<div class="media list-group-item">
	<span class="pull-left thumb">
		{!! $pendingFriend->present()->photoThumb('thumb-sm', ['class' => 'img-circle']) !!}
	</span>
	<span class="media-body m-b-none">
		<!-- user's name -->
		<a href="{{ route('user/view', $pendingFriend->id) }}">
			{{ $pendingFriend->present()->name }}
		</a>
		<!-- controls -->
		<div class="pull-right">
			<a href="{{ route('user/request/accept', ['fromId' => $pendingFriend->id]) }}" class="btn btn-default btn-xs" {!! tooltip('Accept') !!}><i class="fa fa-check text-success"></i></a>
			<a href="{{ route('user/request/deny', ['fromId' => $pendingFriend->id]) }}" class="btn btn-default btn-xs m-l-xs" {!! tooltip('Deny') !!}><i class="fa fa-times text-danger"></i></a>
		</div>
		<small class="block text-muted">{{ $pendingFriend->pivot->created_at->diffForHumans() }}</small>
	</span>
</div>