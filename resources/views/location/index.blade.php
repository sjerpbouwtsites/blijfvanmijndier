@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3>Overzicht opvanglocaties</h3>
	   	<h5><a href="{{ URL::to('locations/create') }}" class="btn btn-primary">Toevoegen</a></h5>

		@if (Session::has('message'))
	    	<div class="alert alert-info">{{ Session::get('message') }}</div>
		@endif

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Adres</th>
					<th>Telefoonnummer</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($locations as $location)
				<tr onclick="window.document.location='{{ URL::to('locations/' . $location->id) }}';">
					<td>{{ $location->name }}</td>
					<td>{{ $location->street }} {{ $location->house_number }} {{ $location->city }}</td>
					<td>{{ $location->phone_number }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
