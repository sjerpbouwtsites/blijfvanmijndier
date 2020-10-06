@extends('layout')

@section('content')
	<div class="col-md-12">
		@if( $vet->id > 0 )
			<h3>Wijzigen dierenarts</h3>
		@else
			<h3>Nieuwe dierenarts</h3>
		@endif
		
		{{ Html::ul($errors->all()) }}

		@if( $vet->id > 0 )
			{{ Form::model($vet, array('route' => array('vets.update', $vet->id), 'method' => 'PUT', 'class'=>'form-horizontal')) }}	
			{{ Form::hidden('id') }}
		@else
			{{ Form::open(array('url' => 'vets', 'class'=>'form-horizontal')) }}
		@endif
	
		<div class="col-md-6">
			<h4>Details</h4>

            @include('form_text', ['field' => 'name', 'label' => 'Naam'])
            @include('form_text', ['field' => 'street', 'label' => 'Straat'])
            @include('form_text', ['field' => 'house_number', 'label' => 'Huisnummer'])            
            @include('form_text', ['field' => 'postal_code', 'label' => 'Postcode'])
            @include('form_text', ['field' => 'city', 'label' => 'Woonplaats'])
            @include('form_text', ['field' => 'phone_number', 'label' => 'Telefoonnummer'])
            @include('form_text', ['field' => 'email_address', 'label' => 'Emailadres'])
            @include('form_text', ['field' => 'website', 'label' => 'Website'])
            @include('form_text', ['field' => 'contact_person', 'label' => 'Contactpersoon'])
		</div>
		
		<div class="col-md-6"></div>
			<div class="col-md-12">
				<div class="form-group">
    				{{ Form::label('text', 'Afspraken', array('class' => 'control-label col-md-2')) }}
    				<div class="col-md-4">
						{{ Form::textarea('remarks_contract', Input::old('remarks_contract'), ['size' => '80x5', 'class' => 'form-control']) }}
    				</div>
					<div class="col-md-6"></div>
				</div>
			</div>		
			<div class="col-md-12">
				<div class="form-group">
    				{{ Form::label('text', 'Opmerkingen', array('class' => 'control-label col-md-2')) }}
    				<div class="col-md-4">
						{{ Form::textarea('remarks_general', Input::old('remarks_general'), ['size' => '80x5', 'class' => 'form-control']) }}
    				</div>
					<div class="col-md-6"></div>
				</div>
			</div>		
			<div class="col-md-12">
		    	<div class="form-group form_buttons">
			    	{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
			    	<a href="{{ URL::to('vets/' . $vet->id) }}" class="btn btn-default">Annuleren</a>
			   	 </div>
			</div>

		{{ Form::close() }}	

	</div>    	
@stop
