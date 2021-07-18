@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3>Mogelijke gastgezinnen</h3>

		@if (Session::has('message'))
	    	<div class="alert alert-info">{{ Session::get('message') }}</div>
		@endif

		<form action="{{ URL::current() }}">
		<input type="hidden" name="isSearchAction" value="true">
		<div class="col-md-2">
			<h4 class="list_h4">Gedrag</h4>
		    <div class="form-group">
		    	@foreach ($behaviourList as $behaviour)
		    	<div class="checkbox">
					{{ Form::checkbox('behaviourList[]', $behaviour->id, in_array($behaviour->id, $checked_behaviours) ? true : false) }} {{ $behaviour->description }}
					</div>
				@endforeach
		    </div>
			<h4 class="list_h4">Wonen</h4>
		    <div class="form-group">
		    	@foreach ($home_typeList as $hometype)
		    	<div class="checkbox">
					{{ Form::checkbox('hometypeList[]', $hometype->id, in_array($hometype->id, $checked_hometypes) ? true : false) }} {{ $hometype->description }}
					</div>
				@endforeach
		    </div>
		    <div class="form-group">
		    {{ Form::submit('Zoeken', array('class' => 'btn btn-primary')) }}
		    </div>
		</div>

		{{ Form::close() }}	

		<div class="col-md-10">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Naam</th>
						<th>Adres</th>
						<th>Telefoonnummer</th>
					</tr>
				</thead>
				<tbody>

				@foreach ($guests as $guest)
					<tr onclick="window.document.location='{{ URL::to('animals/' . $animal->id . '/matchguest/' . $guest->id) }}';">
						<td>{{ $guest->name }}</td>
						<td>{{ $guest->street }} {{ $guest->house_number }} {{ $guest->city }}</td>
						<td>{{ $guest->phone_number }}</td>
					</tr>
				@endforeach	

				</tbody>
			</table>
		</div>

	</div>
@stop
