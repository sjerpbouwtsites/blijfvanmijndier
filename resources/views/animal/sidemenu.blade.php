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
					@if ($updates_checked['has_icons'])
					<ul class='sidemenu-update-icons'>
						@foreach ($updates_checked['icons'] as $icon)
							<li class='sidemenu-update-icons__item'>
								<i class='fa fa-{{$icon}}'></i>
							</li>
						@endforeach
					</ul>                
					@endif
				</a>
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
<style>
	
 /* single show animal */
 .has-update-icons {
    border-left: 10px solid  #ce1d1d;
 }
 .has-update-icons *{
    color: #ce1d1d;
 }
 .sidemenu-update-icons {
    display: inline-flex;
    margin: 0;
    margin-left: .5em;
    padding: 0;
    list-style-type: none;
 
 }
 .sidemenu-update-icons__item + .sidemenu-update-icons__item{
 margin-left: .5em;
 }
</style>