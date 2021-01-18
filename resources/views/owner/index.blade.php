@extends('layout')

@section('content')
	<div class="col-md-12">
   		<h3>Overzicht eigenaren</h3>
	   	<h5><a href="{{ URL::to('owners/create') }}" class="btn btn-primary">Toevoegen</a></h5>

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

			@foreach ($owners as $owner)
				<tr onclick="window.document.location='{{ URL::to('owners/' . $owner->id) }}';">
					<td>{{ $owner->name }} {{ $owner->prefix }} {{ $owner->surname }}</td>
					<td>{{ $owner->street }} {{ $owner->house_number }} {{ $owner->city }}</td>
					<td>{{ $owner->phone_number }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
