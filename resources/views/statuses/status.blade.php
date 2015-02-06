<section class="panel panel-default panel-status">
	<div class="panel-body">
		<!-- status actions dropdown -->
		@include('statuses.partials.status-dropdown')
		<!-- status body -->
		@include('statuses.partials.status-content')
		<!-- interactions -->
		@include('statuses.partials.status-interactions')
	</div>
	<footer class="panel-footer">
		<ul class="list-group no-borders no-radius no-bg m-b-none auto">
			<!-- Status Comments -->
			@if(! $status->comments->isEmpty())
				@foreach($status->comments as $comment)
					@include('statuses.partials.comment')
				@endforeach
			@endif

			<!-- Reply Form -->
			@include('statuses.forms.new-comment')
		</ul>
	</footer>
</section>