@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3>Overzicht dierenartsen</h3>
	   	<h5><a href="{{ URL::to('vets/create') }}" class="btn btn-primary">Toevoegen</a></h5>

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

			@foreach ($vets as $vet)
				<tr onclick="window.document.location='{{ URL::to('vets/' . $vet->id) }}';">
					<td>{{ $vet->name }}</td>
					<td>{{ $vet->street }} {{ $vet->house_number }} {{ $vet->city }}</td>
					<td>{{ $vet->phone_number }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
