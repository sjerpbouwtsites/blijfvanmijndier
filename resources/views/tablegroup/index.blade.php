@extends('layout')

@section('content')
	<h3>Overzicht met tabellen</h3>

	@include('session_messages')
	
	<div class="wrap">
		@foreach ($tablegroups as $tablegroup)
			<a href="{{ URL::to('tablegroups/' . $tablegroup->id) }}">
			    <div class="col-md-6">
			  		<div class="col-md-3">
			  			<img class="img-thumbnail" src="http://www.petrescue.org.nz/theme/PetRescue/img/placeholder.jpg" alt="" width="150" height="150">
			  		</div>
			  		<div class="col-md-9">
			      		<h3>{{ $tablegroup->name }}</h3>
			  		</div>
			    </div>
			 </a>
		@endforeach	
  	</div>
@stop
