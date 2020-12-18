@extends('layout')

@section('content')
	<div class="col-md-12">
		@if( $guest->id > 0 )
			<h3>Wijzigen gastgezin</h3>
		@else
			<h3>Nieuw gastgezin</h3>
		@endif
		
		{{ Html::ul($errors->all()) }}

		@if( $guest->id > 0 )
			{{ Form::model($guest, array('route' => array('guests.update', $guest->id), 'method' => 'PUT', 'class'=>'form-horizontal')) }}	
			{{ Form::hidden('id') }}
		@else
			{{ Form::open(array('url' => 'guests', 'class'=>'form-horizontal')) }}
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
            @include('form_text', ['field' => 'max_hours_alone', 'label' => 'Aantal uur alleen'])

		    <div class="form-group">
    			{{ Form::label('text', 'Opmerking', array('class' => 'control-label col-md-4')) }}
    			<div class="col-md-8">
					{{ Form::textarea('text', Input::old('text'), ['size' => '80x5', 'class' => 'form-control']) }}
    			</div>
			</div>

		    <div class="form-group form_buttons">
		    	{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
		    	<a href="{{ URL::to('guests/' . $guest->id) }}" class="btn btn-default">Annuleren</a>
		    </div>
		</div>
		<div class="col-md-6">
			<div class="col-md-2">
			</div>
			<div class="col-md-5">
                @include('form_checkbox_list', ['title' => 'Gedrag', 'list' => $behaviourList, 'checked' => $checked_behaviours])
			</div>
			<div class="col-md-5">
                @include('form_checkbox_list', ['title' => 'Wonen', 'list' => $hometypeList, 'checked' => $checked_hometypes])
                @include('form_checkbox_list', ['title' => 'Diervoorkeur', 'list' => $animaltypeList, 'checked' => $checked_animaltypes])
			</div>
		</div>

		{{ Form::close() }}	

	</div>    	
@stop
