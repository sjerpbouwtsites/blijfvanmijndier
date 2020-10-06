@extends('layout')

@section('content')
	<div class="col-md-12">
   		<h3>Koppel eigenaar aan <strong>{{ $animal->name }}</strong> (<small>{{ $animal->breedDesc }}</small>)</h3>

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Adres</th>
					<th>Telefoonnummer</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($owners as $owner)
				<tr onclick="window.document.location='{{ URL::to('animals/' . $animal->id . '/matchowner/' . $owner->id) }}';">
					<td>{{ $owner->name }} {{ $owner->prefix }} {{ $owner->surname }}</td>
					<td>{{ $owner->street }} {{ $owner->house_number }} {{ $owner->city }}</td>
					<td>{{ $owner->phone_number }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
