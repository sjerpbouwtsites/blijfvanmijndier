@extends('layout')

@section('content')
	<div class="wrap">
		@foreach ($tiles as $tile)
			<div class="box">
				<a href="{{ URL::asset($tile->Url) }}">
				    <div class="boxInner">
				    	<i class="fa {{ $tile->Icon }} fa-lg"></i>
				      	<h5>{{ $tile->Text }}</h5>
				    </div>
				 </a>
			</div>
		@endforeach	
  	</div>

  	<div class='col-md-12'>
	  	<h3 class="home-h3">Laatste updates</h3>

		<table class="table table-hover table-home">
			<thead>
				<tr>
					<th>Updatedatum</th>
					<th>Type</th>
					<th>Naam</th>
					<th>Medewerker</th>
					<th>Tekst</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($updates as $update)
				<tr onclick="window.document.location='{{ URL::to($update->link_type . '/' . $update->link_id . '/updates/' . $update->id) }}';">
					<td>{{ $update->start_date }}</td>
					<td>{{ $update->name_label }}</td>
					<td>{{ $update->name }}</td>
					<td>{{ $update->employeeName }}</td>
					<td>{{ $update->smallText }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>

		<a class="btn btn-default" href='{{ URL::to('updates/selection') }}'>Toon meer updates</a>
	</div>

  	<div class='col-md-12'>
	  	<h3 class="home-h3">Update nodig</h3>

		<table class="table table-hover table-home">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Soort dier</th>
					<th>Ras</th>
					<th>Laatste update</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($animalsToUpdate as $animalToUpdate)
				<tr onclick="window.document.location='{{ URL::to('animals/' . $animalToUpdate->id . '/updates') }}';">
					<td>{{ $animalToUpdate->name }}</td>
					<td>{{ $animalToUpdate->animaltypeDesc }}</td>
					<td>{{ $animalToUpdate->breedDesc }}</td>
					<td>{{ $animalToUpdate->lastUpdate }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>

  	<div class='col-md-12'>
	  	<h3 class="home-h3">Nieuwste dieren</h3>

		<table class="table table-hover table-home">
			<thead>
				<tr>
					<th>Aanmelddatum</th>
					<th>Naam</th>
					<th>Soort dier</th>
					<th>Ras</th>
					<th>Laatste update</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($animals as $animal)
				<tr onclick="window.document.location='{{ URL::to('animals/' . $animal->id) }}';">
					<td>{{ $animal->registration_date }}</td>
					<td>{{ $animal->name }}</td>
					<td>{{ $animal->animaltypeDesc }}</td>
					<td>{{ $animal->breedDesc }}</td>
					<td>{{ $animal->lastUpdate }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>

@stop
