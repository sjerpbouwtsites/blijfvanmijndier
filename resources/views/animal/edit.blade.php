@extends('layout')

@section('content')
	<div class="col-md-12">
		@if( $animal->id > 0 )
			<h3>Wijzigen dier</h3>
		@else
			<h3>Nieuw dier</h3>
		@endif
		
		{{ Html::ul($errors->all()) }}

		@if( $animal->id > 0 )
			{{ Form::model($animal, array('route' => array('animals.update', $animal->id), 'method' => 'PUT', 'class'=>'form-horizontal', 'files' => true)) }}	
			{{ Form::hidden('id', $animal->id) }}
		@else
			{{ Form::open(array('url' => 'animals', 'class'=>'form-horizontal', 'files' => true)) }}
		@endif

		<div class="col-md-6">
			<h4>Details</h4>

						@include('form_text', 
						['field' => 'name', 'label' => 'Naam']
						)
						@include('form_select', 
						['field' => 'animaltype',
						 'label' => 'Soort dier',
							'id' => 'animaltype_id',
							'types' => $animaltypes,
						])
						@include('form_select', 
						['field' => 'breed', 
						'label' => 'Ras', 
						'id' => 'breed_id', 
						'types' => $breeds]
						)
						@include('form_select', 
						['field' => 'gendertype', 
						'label' => 'Geslacht', 
						'id' => 'gendertype_id', 
						'types' => $gendertypes]
						)
            @include('form_date', ['field' => 'registration_date', 'label' => 'Aanmelddatum', 'value' => Input::old('start_date')])
            @include('form_date', ['field' => 'birth_date', 'label' => 'Geboortedatum', 'value' => Input::old('start_date')])
            @include('form_text', ['field' => 'chip_number', 'label' => 'Chipnummer'])
            @include('form_text', ['field' => 'passport_number', 'label' => 'Paspoortnummer'])
            @include('form_text', ['field' => 'max_hours_alone', 'label' => 'Aantal uur alleen'])
            @include('form_checkbox', ['field' => 'abused', 'label' => 'Zelf mishandeld'])
            @include('form_checkbox', ['field' => 'witnessed_abuse', 'label' => 'Getuige van mishandeling'])
          	@include('form_checkbox', ['field' => 'updates', 'label' => 'Updates'])

		    <div class="form-group">
		        {{ Form::label('animal_image', 'Afbeelding', array('class' => 'control-label col-md-4')) }}
		        <div class="col-md-8">
		        	{{ Form::file('animal_image', null, null) }}
		        </div>
		    </div>
		    <div class="form-group">
		        <div class="col-md-4"></div>
		        <div class="col-md-8">
		        	<img class="" src="{{ URL::asset($animal->animalImage) }}" alt="{{ $animal->name }}" width="150" height="150">
		        </div>
		    </div>
		    
		    <div class="form-group form_buttons">
		    	{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
		    	<a href="{{ URL::to('animals/' . $animal->id) }}" class="btn btn-default">Annuleren</a>
		    </div>
		</div>
		<div class="col-md-6">
			<div class="col-md-2">
			</div>
			<div class="col-md-5">
                @include('form_checkbox_list', ['title' => 'Gedrag', 'list' => $behaviourList, 'checked' => $checked_behaviours])
                @include('form_checkbox_list', ['title' => 'Vaccinaties', 'list' => $vaccinationList, 'checked' => $checked_vaccinations])
			</div>
			<div class="col-md-5">
                @include('form_checkbox_list', ['title' => 'Wonen', 'list' => $hometypeList, 'checked' => $checked_hometypes])
			</div>
		</div>

		{{ Form::close() }}	

	</div>    	
@stop
