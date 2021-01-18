@extends('layout')

@section('content')
	<div class="col-md-12">
		@if( $guest->id > 0 )
			<h3>Wijzigen gastgezin</h3>
		@else
			<h3>Nieuw gastgezin</h3>
		@endif
		@if (Session::has('message'))
	    	<div class="alert alert-info">{{ Session::get('message') }}</div>
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

			<input type='hidden' name='address_id' value="<?=$guest['address_id']?>" >
            @include('form_text', ['field' => 'name', 'label' => 'Naam'])
						@include('form_group_address');
            @include('form_text', ['field' => 'phone_number', 'label' => 'Telefoonnummer'])
						@include('form_text', ['field' => 'email_address', 'label' => 'Emailadres'])
						
						<!-- dirty in de turbo 👹-->
						<div class="form-group">
							<label for="max_hours_alone" class="control-label col-md-4">max uren alleen</label>
							<div class="col-md-8">
								<input min="0" class="form-control" required name="max_hours_alone" type="number" value="<?=$guest['max_hours_alone']?>" id="max_hours_alone">
							</div>
					</div>


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

								@include('form_checkbox_list', 
									['title' => 'Gedrag', 
									'list' => $behaviourList, 
									'checked' => $checked_behaviours
								])
			</div>
			<div class="col-md-5">
								@include('form_checkbox_list', ['title' => 'Wonen', 'list' => $hometypeList, 'checked' => $checked_hometypes])
								
                @include('form_checkbox_list', ['title' => 'Diervoorkeur', 'list' => $animaltypeList, 'checked' => $checked_animaltypes])
			</div>
		</div>

		{{ Form::close() }}	

	</div>    	
@stop
