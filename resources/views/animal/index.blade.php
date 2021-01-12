@extends('layout')

@section('content')
	<div class="col-md-12">
		<div class="col-md-4"><h3>Overzicht dieren</h3></div>
	</div>
	<div class="col-md-12">

		@if (Session::has('message'))
	    	<div class="alert alert-info">{{ Session::get('message') }}</div>
		@endif

		<div class="card-deck">

			<a href="{{ URL::to('animals/create') }}">
			<div class="card new_animal panel">
			  <img class="card-img-top" src="img/placeholder.jpg" alt="Nieuw dier toevoegen" width="150" height="150">
			  <div class="card-block">
			    <h4 class="card-title">Toevoegen</h4>
			    <p class="card-text">Nieuw dier toevoegen</p>
			  </div>
			</div>
			</a>

			@foreach ($animals as $animal)
			<a href="{{ URL::to('animals/' . $animal->id) }}">
				@if($animal->needUpdate == 1)
					<div class="card panel update_back update_border">
				@else
					<div class="card panel">	
				@endif	
			  <img class="card-img-top" src="{{ $animal->animalImage }}" alt="{{ $animal->name }}" width="150" height="150">
			  <div class="card-block">
			    <h4 class="card-title">{{ $animal->name }}</h4>
			    <p class="card-text">{{ $animal->breedDesc }}</p>
			  </div>
			</div>
			</a>
			@endforeach	
		</div>	
	</div>  	

	<?php echo $animalsOldView; ?>

@stop
