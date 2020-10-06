@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3>Overzicht documenten van {{ $animal->name }}</h3>
	   	<h5><a href="{{ URL::to('animals/' . $animal->id . '/documents/create') }}" class="btn btn-primary">Toevoegen</a> <a href='{{ URL::to('animals/' . $animal->id ) }}' class="btn btn-default">Terug naar {{ $animal->name }}</a></h5>

		@if (Session::has('message'))
	    	<div class="alert alert-info">{{ Session::get('message') }}</div>
		@endif

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Documentsoort</th>
					<th>Tekst</th>
				</tr>
			</thead>
			<tbody>

			@foreach ($documents as $document)
				<tr onclick="window.document.location='{{ URL::to('animals/' . $animal->id . '/documents/' . $document->id) }}';">
					<td>{{ $document->doctypeName }}</td>
					<td>{{ $document->text }}</td>
				</tr>
			@endforeach	

			</tbody>
		</table>
	</div>  	
@stop
