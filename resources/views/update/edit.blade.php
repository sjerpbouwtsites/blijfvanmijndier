@extends('layout')

@section('content')
	<div class="col-md-12">
		@if( $update->id > 0 )
			<h3>Wijzigen update van {{ $name }}</h3>
		@else
			<h3>Nieuwe update van {{ $name }}</h3>
		@endif
		@include('session_messages')
		{{ Html::ul($errors->all()) }}

		@if( $update->id > 0 )
			{{ Form::model($update, ['method'=> 'PUT', 'route'=>[$link_type . '.updates.update', $link_id, $update->id], 'class'=>'form-horizontal']) }}
			{{ Form::hidden('link_type', $link_type) }}
		@else
			{{ Form::open(['route'=>[$link_type . '.updates.store', $link_id], 'class'=>'form-horizontal']) }}
			{{ Form::hidden('link_type', $link_type) }}
		@endif
	
		<div class="col-md-6">
			<h4>Details</h4>

			@if( $update->id > 0 )
	   	        @include('form_date', ['field' => 'start_date', 'label' => 'Datum', 'value' => Input::old('start_date')])
			@else
	   	        @include('form_date', ['field' => 'start_date', 'label' => 'Datum', 'value' => $update->start_date])
			@endif

            @include('form_select', ['field' => 'updatetype', 'label' => 'Soort update', 'id' => 'updatetype_id', 'types' => $updatetypes])
            @include('form_select', ['field' => 'employee', 'label' => 'Medewerker', 'id' => 'employee_id', 'types' => $employees])
		</div>

		<div class="col-md-6"></div>
			<div class="col-md-12">
				<div class="form-group">
    				{{ Form::label('text', 'Tekst', array('class' => 'control-label col-md-2')) }}
    				<div class="col-md-8">
						{{ Form::textarea('text', Input::old('text'), ['size' => '80x15', 'class' => 'form-control']) }}
    				</div>
					<div class="col-md-2"></div>
				</div>
			</div>
			<div class="col-md-12">
			    <div class="form-group form_buttons">
			    	{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
		    		<a href="{{ URL::to($link_type . '/' . $link_id . '/updates/' . $update->id) }}" class="btn btn-default">Annuleren</a>
		    	</div>
			</div>

		{{ Form::close() }}	

	</div>    	
@stop
