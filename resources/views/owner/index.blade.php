@extends('layout')

@section('content')
	<div class="col-md-12">
   		<h3 class='titel-letter'>Overzicht eigenaren</h3>
	   	<h5><a href="{{ URL::to('owners/create') }}" class="btn btn-primary">Toevoegen</a></h5>

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

			@foreach ($owners as $owner)
				<tr >
					<td onclick="window.document.location='{{ URL::to('owners/' . $owner->id) }}';">{{ $owner->name }} {{ $owner->prefix }} {{ $owner->surname }}</td>
					<td onclick="window.document.location='{{ URL::to('owners/' . $owner->id) }}';">{{ $owner->street }} {{ $owner->house_number }} {{ $owner->city }}</td>
					<td onclick="window.document.location='{{ URL::to('owners/' . $owner->id) }}';">{{ $owner->phone_number }}</td>
					<td>
							<a href='{{ URL::to('owners/' . $owner->id) }}/edit' title='openen in nieuw tabblad' target='_blank'>✍</a>
					</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
