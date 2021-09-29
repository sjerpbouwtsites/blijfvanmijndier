@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3 class='titel-letter'>Overzicht updates van {{ $name }}</h3>
	   	<h5><a href="{{ URL::to($link_type . '/' . $link_id . '/updates/create') }}" class="btn btn-primary">Toevoegen</a> <a href='{{ URL::to($link_type . '/' . $link_id ) }}' class="btn btn-default">Terug naar {{ $name }}</a></h5>

			 @include('session_messages')

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Updatedatum</th>
					<th>Soort update</th>
					<th>Medewerker</th>
					<th>Tekst</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($updates as $update)
				<tr onclick="window.document.location='{{ URL::to($link_type . '/' . $link_id . '/updates/' . $update->id) }}';">
					<td>{{ $update->start_date }}</td>
					<td>{{ $update->updatetypeDesc }}</td>
					<td>{{ $update->employeeName }}</td>
					<td>{{ $update->text }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
