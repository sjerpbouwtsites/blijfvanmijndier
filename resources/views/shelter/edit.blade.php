@extends('layout')

@section('content')
	<div class="col-md-12">
		@if( $shelter->id > 0 )
			<h3>Wijzigen pension</h3>
		@else
			<h3>Nieuw pension</h3>
		@endif
		@include('session_messages')
		{{ Html::ul($errors->all()) }}

		@if( $shelter->id > 0 )
			{{ Form::model($shelter, array('route' => array('shelters.update', $shelter->id), 'method' => 'PUT', 'class'=>'form-horizontal')) }}	
			{{ Form::hidden('id') }}
		@else
			{{ Form::open(array('url' => 'shelters', 'class'=>'form-horizontal')) }}
		@endif
	
		<div class="col-md-6">
			<h4>Details</h4>

			<input type='hidden' name='address_id' value="<?=$shelter['address_id']?>" >
            @include('form_text', ['field' => 'name', 'label' => 'Naam'])
            @include('form_group_address')
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
		    		<a href="{{ URL::to('shelters/' . $shelter->id) }}" class="btn btn-default">Annuleren</a>
			   	 </div>
			</div>

		{{ Form::close() }}	

	</div>    	
@stop
