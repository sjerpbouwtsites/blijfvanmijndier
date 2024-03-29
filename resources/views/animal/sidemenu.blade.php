	<div class="col-md-3">
		<ul class="list-group action-menu">
		  <li class="list-group-item menu-title"><i class="fa fa-bars fa-fw"></i>&nbsp; Menu</a></li>
		  
		  @if($guest_id > 0)
			<li class="list-group-item"><a href="{{ URL::asset('guests/' . $guest_id) }}"><i class="fa fa-users fa-fw"></i>&nbsp; Gastgezin</a></li>
		  @elseif($shelter_id == 0)
		  	<li class="list-group-item"><a href="{{ URL::asset('animals/' . $animal->id . '/match') }}"><i class="fa fa-search fa-fw"></i>&nbsp; Zoek gastgezin</a></li>
		  @endif
		  
		  @if($shelter_id > 0)
			<li class="list-group-item"><a href="{{ URL::asset('animals/' . $animal->id . '/shelter') }}"><i class="fa fa-search fa-fw"></i>&nbsp; Pension</a></li>
		  @elseif($guest_id == 0)
		  	<li class="list-group-item"><a href="{{ URL::asset('animals/' . $animal->id . '/shelter') }}"><i class="fa fa-search fa-fw"></i>&nbsp; Zoek pension</a></li>
		  @endif
		  	
     	  	<li class="list-group-item"><a href="{{ URL::to('animals/' . $animal->id . '/owner') }}"><i class="fa fa-female fa-fw"></i>&nbsp; Eigenaar</a></li>



			<li class="list-group-item {{$updates_checked['has_icons'] ? 'has-update-icons' : ''}}">
				<a href="{{ URL::to('animals/' . $animal->id . '/updates') }}">
					<i class="fa fa-pencil fa-fw"></i>&nbsp; Updates
				</a>
				@include('generic.updates-icons', ['icon_data' => $updates_checked])
		    </li>

		    <li class="list-group-item"><a href="{{ URL::to('animals/' . $animal->id . '/histories') }}"><i class="fa fa-history fa-fw"></i>&nbsp; Historie</a></li>
    		<li class="list-group-item"><a href="{{ URL::to('animals/' . $animal->id . '/documents') }}"><i class="fa fa-file fa-fw"></i>&nbsp; Documenten</a></li>
    		<li class="list-group-item"><a href="{{ URL::to('animals/' . $animal->id . '/outofproject') }}"><i class="fa fa-sign-out fa-fw"></i>&nbsp; Afmelden</a></li> 	

		  <!-- 
		  <li class="list-group-item"><a href=""><i class="fa fa-camera fa-fw"></i>&nbsp; Foto's</a></li>
		  <li class="list-group-item"><a href=""><i class="fa fa-file fa-fw"></i>&nbsp; Documenten</a></li>
		  <li class="list-group-item"><a href=""><i class="fa fa-sign-out fa-fw"></i>&nbsp; Afmelden</a></li> 
		  -->
		</ul>

		@include('update.lastupdates', ['updates' => $updates])

	</div>
