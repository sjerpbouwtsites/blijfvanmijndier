@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3 class='titel-letter'>Afmelden {{ $animal->name }}</h3>
		
		{{ Html::ul($errors->all()) }}

		{{ Form::model($animal, ['method' => 'POST', 'action' => ['AnimalController@outofprojectstore',  $animal->id]]) }}
		{{ Form::hidden('id', $animal->id) }}
	
		<div class="col-md-6">
			<h4>Details</h4>

			@include('form_date', ['field' => 'end_date', 'label' => 'Afmelddatum', 'value' => $animal->end_date])
            @include('form_select', ['field' => 'endtype', 'label' => 'Afmeldreden', 'id' => 'endtype_id', 'types' => $endtypes])

			<div class="form-group">
    			{{ Form::label('text', 'Toelichting', array('class' => 'control-label col-md-4')) }}
    			<div class="col-md-8">
					{{ Form::textarea('end_description', null, ['size' => '80x5', 'class' => 'form-control']) }}
    			</div>
			</div>
		</div>
		<div class="col-md-6"></div>

		<div class="col-md-12">
		    <div class="form-group form_buttons">
		    	{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
		    	<a href="{{ URL::to('animals/' . $animal->id) }}" class="btn btn-default">Annuleren</a>
		    </div>
		</div>

		{{ Form::close() }}	

	</div>    	
@stop
