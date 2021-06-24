@extends('layout')

@section('content')
	<div class="col-md-9">
		<h3>Nieuwe tabelregel</h3>
		<hr>
		<h4>Details</h4>
		
		{{ Html::ul($errors->all()) }}

		{{ Form::open(array('url' => 'tables', 'class'=>'form-horizontal')) }}

		<div class="col-md-6">
		    <div class="form-group">
		        {{ Form::label('tablegroup_id', 'Groep', array('class' => 'control-label col-md-4')) }}
		        <div class="col-sm-8">
			        {{ Form::select('tablegroup_id', $types, Input::old('tablegroup_id'), ['class' => 'form-control']) }}
		        </div>
		    </div>

		    <div class="form-group">
		        {{ Form::label('description', 'Omschrijving', array('class' => 'control-label col-md-4')) }}
		        <div class="col-sm-8">
		        	{{ Form::text('description', Input::old('description'), array('class' => 'form-control')) }}
		        </div>
		    </div>

		    <div class="form-group">
		    	{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
		    	<a href="{{ URL::to('tables') }}" class="btn btn-default">Annuleren</a>
		    </div>
		</div>
		<div class="col-md-6">
			
		</div>

		{{ Form::close() }}	

	</div>    	
@stop
