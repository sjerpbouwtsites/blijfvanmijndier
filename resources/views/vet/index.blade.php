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
					<th>Link</th>
				</tr>
			</thead>
			<tbody>


				@foreach ($vets as $vet)
				<tr >
					<td onclick="window.document.location='{{ URL::to('vets/' . $vet->id) }}';">{{ $vet->name }} {{ $vet->prefix }} {{ $vet->surname }}</td>
					<td onclick="window.document.location='{{ URL::to('vets/' . $vet->id) }}';">{{ $vet->street }} {{ $vet->house_number }} {{ $vet->city }}</td>
					<td onclick="window.document.location='{{ URL::to('vets/' . $vet->id) }}';">{{ $vet->phone_number }}</td>
					<td>
							<a href='{{ URL::to('vets/' . $vet->id) }}/edit' title='openen in nieuw tabblad' target='_blank'>✍</a>
					</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
