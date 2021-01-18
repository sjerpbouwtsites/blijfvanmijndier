@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3>Overzicht pensions</h3>
	   	<h5><a href="{{ URL::to('shelters/create') }}" class="btn btn-primary">Toevoegen</a></h5>

			 @include('session_messages')

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Adres</th>
					<th>Telefoonnummer</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($shelters as $shelter)
				<tr onclick="window.document.location='{{ URL::to('shelters/' . $shelter->id) }}';">
					<td>{{ $shelter->name }}</td>
					<td>{{ $shelter->street }} {{ $shelter->house_number }} {{ $shelter->city }}</td>
					<td>{{ $shelter->phone_number }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
