	<div class="col-md-3">
		<ul class="list-group action-menu">
		  <li class="list-group-item menu-title"><i class="fa fa-bars fa-fw"></i>&nbsp; Menu</a></li>
		  <li class="list-group-item"><a href="{{ URL::to('guests/' . $guest->id . '/updates') }}"><i class="fa fa-pencil fa-fw"></i>&nbsp; Updates</a>
		  <li class="list-group-item"><a href="{{ URL::to('guests/' . $guest->id . '/histories') }}"><i class="fa fa-history fa-fw"></i>&nbsp; Historie</a></li>
		</ul>

		@include('update.lastupdates', ['updates' => $updates])

	</div>
