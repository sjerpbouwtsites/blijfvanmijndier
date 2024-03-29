@extends('layout')

@section('content')
	<div class="col-md-12">
		@if( $owner->id > 0 )
			<h3>Wijzigen eigenaar</h3>
		@else
			<h3>Nieuwe eigenaar</h3>
		@endif
		@include('session_messages')
		{{ Html::ul($errors->all()) }}

		@if( $owner->id > 0 )
			{{ Form::model($owner, array('route' => array('owners.update', $owner->id), 'method' => 'PUT', 'class'=>'form-horizontal')) }}	
			{{ Form::hidden('id') }}
		@else
			{{ Form::open(array('url' => 'owners', 'class'=>'form-horizontal')) }}
		@endif
	
		
		@include('generic.address-edit', [
			'model' => $owner,
			'model_name' => 'owner'
			])
			<div class="col-md-6">
				<h4>Details</h4>
			@include('form_text', ['field' => 'name', 'label' => 'Voornaam'])
			@include('form_text', ['field' => 'prefix', 'label' => 'Tussenvoegsel'])
						@include('form_text', ['field' => 'surname', 'label' => 'Achternaam'])
						@include('form_text', ['field' => 'phone_number', 'label' => 'Telefoonnummer'])
						@include('form_text', ['field' => 'email_address', 'label' => 'Emailadres'])
			</div>
			<div class="form-group form_buttons">
				{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
				<a href="{{ URL::to('owner/' . $owner->id) }}" class="btn btn-default">Annuleren</a>
			</div>
		<div class="col-md-6">
			<div class="col-md-2">
			</div>
			<div class="col-md-5">
			</div>
			<div class="col-md-5">
			</div>
		</div>
{{--
		<div class="col-md-6">
			<h4>Contactpersoon opvanglocatie</h4>

			<div class="form-group">
			    {{ Form::label('contact.name', 'Naam', array('class' => 'control-label col-md-4')) }}
			    <div class="col-md-8">
			    	{{ Form::text('contact.name', $owner->contact->name, array('class' => 'form-control')) }}
			    </div>
			</div>

            @include('form_text', ['field' => 'contact.name', 'label' => 'Naam'])
            @include('form_text', ['field' => 'Contact[name]', 'label' => 'Naam']) 
            @include('form_text', ['field' => 'Contact[phone_number]', 'label' => 'Telefoonnummer'])
            @include('form_text', ['field' => 'Contact[email_address]', 'label' => 'Emailadres'])
		</div>
--}}
		{{ Form::close() }}	

	</div>    	
@stop
