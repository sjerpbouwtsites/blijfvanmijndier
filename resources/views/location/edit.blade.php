@extends('layout')

@section('content')
	<div class="col-md-12">
		@if( $location->id > 0 )
			<h3>Wijzigen opvanglocatie</h3>
		@else
			<h3>Nieuwe opvanglocatie</h3>
		@endif
		@if (Session::has('message'))
		<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
		{{ Html::ul($errors->all()) }}

		@if( $location->id > 0 )
			{{ Form::model($location, array('route' => array('locations.update', $location->id), 'method' => 'PUT', 'class'=>'form-horizontal')) }}	
			{{ Form::hidden('id') }}
		@else
			{{ Form::open(array('url' => 'locations', 'class'=>'form-horizontal')) }}
		@endif
	
		<div class="col-md-6">
			<h4>Details</h4>

			<input type='hidden' name='address_id' value="<?=$location['address_id']?>" >
            @include('form_text', ['field' => 'name', 'label' => 'Naam'])
            @include('form_group_address', [
							'lattitude' => $location['lattitude'],
							'longitude' => $location['longitude']
						])
            @include('form_text', ['field' => 'phone_number', 'label' => 'Telefoonnummer'])
            @include('form_text', ['field' => 'email_address', 'label' => 'Emailadres'])

		    <div class="form-group form_buttons">
		    	{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
		    	<a href="{{ URL::to('locations/' . $location->id) }}" class="btn btn-default">Annuleren</a>
		    </div>
		</div>
		<div class="col-md-6">
			<div class="col-md-2">
			</div>
			<div class="col-md-5">
			</div>
			<div class="col-md-5">
			</div>
		</div>

		{{ Form::close() }}	

	</div>    	
@stop
