<h4>{{ $title }}</h4>

@if(!$list->isEmpty()) 
	<!-- <ul> -->
	<!-- <div class="card-deck"> -->
	    @foreach ($animals as $animal)
	        <!-- <li><a href="{{ URL::to('animals/' . $animal->id) }}">{{ $animal->name}}</a></li> -->
	        <!-- <a title="{{ $animal->name }}" href="{{ URL::to('animals/' . $animal->id) }}" ><img class="" src="{{ URL::asset($animal->animalImage) }}" width="75" height="75"></a> -->
	        <a href="{{ URL::to('animals/' . $animal->id) }}">
			<div class="card panel">
			  <img class="card-img-top" src="{{ URL::asset($animal->animalImage) }}" alt="{{ $animal->name }}" width="60" height="60">
			  <div class="card-block">
			    <h5 class="card-title">{{ $animal->name }}</h5>
			    <a href="{{ URL::to('animals/' . $animal->id) . '/' . $link }}"><h7>Ontkoppel</h7></a>
			  </div>
			</div>
			</a>
	    @endforeach 
	<!-- </div> -->
	<!-- </ul> -->
@else
	Geen dieren
@endif	
