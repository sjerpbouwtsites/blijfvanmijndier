@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3>Overzicht historie {{$source_name}}</h3>
	   	<h5><a href='{{ URL::to($source_type . '/' . $source_id ) }}' class="btn btn-default">Terug naar {{ $source_name }}</a></h5>		

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Historiedatum</th>
					<th>Actie</th>
					<th>Type</th>
					<th>Naam</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($histories as $history)
				<tr onclick="window.document.location='{{ URL::to($history->urlType . '/' . $history->urlId) }}';">
					<td>{{ $history->history_date }}</td>
					<td>{{ $history->actionDesc }}</td>
					<td>{{ $history->link_label }}</td>
					<td>{{ $history->link_name }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
