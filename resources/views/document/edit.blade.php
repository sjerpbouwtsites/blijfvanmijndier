@extends('layout')

@section('content')
	<div class="col-md-12">
		@if( $document->id > 0 )
			<h3 class='titel-letter'>Wijzigen document van {{ $animal->name }}</h3>
		@else
			<h3 class='titel-letter'>Nieuw document van {{ $animal->name }}</h3>
		@endif
		
		{{ Html::ul($errors->all()) }}

		@if( $document->id > 0 )
			{{ Form::model($document, ['method'=> 'PUT', 'route'=>['animals.documents.update', $animal->id, $document->id], 'class'=>'form-horizontal', 'files' => true]) }}
		@else
			{{ Form::open(['route'=>['animals.documents.store', $animal->id], 'class'=>'form-horizontal', 'files' => true]) }}
		@endif
	
		<div class="col-md-6">
			<h4 class='titel-letter'>Details</h4>

			@if( $document->id > 0 )
	   	        @include('form_date', ['field' => 'date', 'label' => 'Datum', 'value' => Input::old('date')])
			@else
	   	        @include('form_date', ['field' => 'date', 'label' => 'Datum', 'value' => $document->date])
			@endif

            @include('form_select', ['field' => 'doctype', 'label' => 'Documentsoort', 'id' => 'doctype_id', 'types' => $doctypes])

		    <div class="form-group">
    			{{ Form::label('text', 'Toelichting', array('class' => 'control-label col-md-4')) }}
    			<div class="col-md-8">
					{{ Form::textarea('text', Input::old('text'), ['size' => '80x5', 'class' => 'form-control']) }}
    			</div>
			</div>

			@if( !$document->id > 0 )
			    <div class="form-group">
			        {{ Form::label('document', 'Document', array('class' => 'control-label col-md-4')) }}
			        <div class="col-md-8">
			        	{{ Form::file('document', null, null) }}
			        </div>
			    </div>
			@endif

		</div>
		<div class="col-md-6"></div>

		<div class="col-md-12">
		    <div class="form-group form_buttons">
		    	{{ Form::submit('Opslaan', array('class' => 'btn btn-primary')) }}
		    	<a href="{{ URL::to('animals/' . $animal->id . '/documents/' . $document->id) }}" class="btn btn-default">Annuleren</a>
		    </div>
		</div>

		{{ Form::close() }}	

	</div>    	
@stop
