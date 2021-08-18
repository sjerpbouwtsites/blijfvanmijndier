@extends('layout')

@section('content')
	<div class="col-md-9">
		<h3 class='titel-letter'>Wijzigen tabel</h3>

		{{ Html::ul($errors->all()) }}

		{{ Form::model($table, array('route' => array('tables.update', $table->id), 'method' => 'PUT', 'class'=>'form-horizontal')) }}
		{{ Form::hidden('id') }}
		
		<div class="col-md-6">
			<h4 class='titel-letter'>Details</h4>
           
            @include('form_select', ['field' => 'tablegroup_id', 'label' => 'Groep', 'id' => 'tablegroup_id', 'types' => $types])
            @include('form_text', ['field' => 'description', 'label' => 'Omschrijving'])

		    <div class="form-group form_buttons">
		    	{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
		    	<a href="{{ URL::to('tables') }}" class="btn btn-default">Annuleren</a>
		    </div>
		</div>
		<div class="col-md-6">
			
		</div>

		{{ Form::close() }}	

	</div>    	

	
@stop
