@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3>Overzicht gastgezinnen</h3>
	   	<h5><a href="{{ URL::to('guests/create') }}" class="btn btn-primary">Toevoegen</a></h5>

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

			@foreach ($guests as $guest)
				<tr onclick="window.document.location='{{ URL::to('guests/' . $guest->id) }}';">
					<td>{{ $guest->name }}</td>
					<td>{{ $guest->street }} {{ $guest->house_number }} {{ $guest->city }}</td>
					<td>{{ $guest->phone_number }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
