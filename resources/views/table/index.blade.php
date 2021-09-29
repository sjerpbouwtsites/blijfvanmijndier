@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3 class='titel-letter'>Overzicht tabellen</h3>
   		<h5><a href="{{ URL::to('tables/create') }}" class="btn btn-primary">Toevoegen</a></h5>

			 @include('session_messages')

		<form action="{{ URL::current() }}">
		<div class="col-md-4">
	        {{ Form::select('tablegroup_id', $types, $tablegroup_id, ['class' => 'form-control']) }}
		</div>
		<div class="col-md-1">
		    {{ Form::submit('Filter', array('class' => 'btn btn-primary')) }}
		</div>
		{{ Form::close() }}	

		<br/>

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Groep</th>
					<th>Omschrijving</th>
					<!-- <th>Acties</th> -->
				</tr>
			</thead>
			<tbody>

			@foreach ($tables as $table)
				<tr onclick="window.document.location='{{ URL::to('tables/' . $table->id . '/edit') }}';">
					<td>{{ $table->tableGroupDesc }}</td>
					<td>{{ $table->description }}</td>

					<!-- <td class="col-actions">
						<a href="{{ URL::to('tables/' . $table->id . '/edit') }}">Wijzigen</a> | 
						<a href="{{ URL::to('tables/' . $table->id) }}">Verwijderen</a>
					</td> -->
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>
@stop
