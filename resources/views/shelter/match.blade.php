@extends('layout')

@section('content')
	<div class="col-md-12">
   		<h3>Koppel pension aan <strong>{{ $animal->name }}</strong> (<small>{{ $animal->breedDesc }}</small>)</h3>

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
				<tr onclick="window.document.location='{{ URL::to('animals/' . $animal->id . '/matchshelter/' . $shelter->id) }}';">
					<td>{{ $shelter->name }}</td>
					<td>{{ $shelter->street }} {{ $shelter->house_number }} {{ $shelter->city }}</td>
					<td>{{ $shelter->phone_number }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
