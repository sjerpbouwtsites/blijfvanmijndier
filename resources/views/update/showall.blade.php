@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3 class='titel-letter'>Overzicht updates</h3>

		<a class="{{ $selection }} btn btn-default" href='{{ URL::to('updates/selection') }}'>Toon laatste 2 weken</a> <a class="{{ $showall }} btn btn-default" href='{{ URL::to('updates/showall') }}'>Toon alles</a>

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Updatedatum</th>
					<th>Type</th>
					<th>Naam</th>
					<th>Medewerker</th>
					<th>Tekst</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($updates as $update)
				<tr onclick="window.document.location='{{ URL::to($update->link_type .'/' . $update->link_id . '/updates/' . $update->id) }}';">
					<td>{{ $update->start_date }}</td>
					<td>{{ $update->name_label }}</td>
					<td>{{ $update->name }}</td>
					<td>{{ $update->employeeName }}</td>
					<td>{{ $update->smallText }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
