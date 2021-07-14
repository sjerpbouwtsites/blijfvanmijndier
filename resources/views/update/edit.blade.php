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
			@else
			{{ Form::open(['route'=>[$link_type . '.updates.store', $link_id], 'class'=>'form-horizontal']) }}
			@endif
			{{ Form::hidden('link_type', $link_type) }}
			{{ Form::hidden('link_id', $link_id) }}

		
		<input id='secret-animal-distribution-id-list' name='secret_animal_distribution_id_list' type='hidden'>
	
		<div class="col-md-6">
			<h4>Details</h4>

			@if( $update->id > 0 )
	   	        @include('form_date', ['field' => 'start_date', 'label' => 'Datum', 'value' => Input::old('start_date')])
			@else
	   	        @include('form_date', ['field' => 'start_date', 'label' => 'Datum', 'value' => $update->start_date])
			@endif

            @include('form_select', ['field' => 'updatetype', 'label' => 'Soort update', 'id' => 'updatetype_id', 'types' => $updatetypes])
            @include('form_select', ['field' => 'employee', 'label' => 'Medewerker', 'id' => 'employee_id', 'types' => $employees])
		<br>
		@if ($has_animal_multiselects)
		<div id='multiselect-wrapper' >
			<h4>Update dupliceren</h4>
			<p>Je kan deze update ook verbinden aan dieren die ofwel via de eigenaar, pension of gastgezin gerelateerd zijn aan dit dier.</p>

			@if ($animal_multiselects['owner']['qualifies_for_multiselect'])
			<div class="form-group">
				<label for='multiselect-owner' class="control-label col-md-4">Eigenaar <?=$animal_multiselects['owner']['model']->name?></label>
				<div class="col-md-8">
					<select id='multiselect-owner' class="animal-multiselects form-control" name="owners_distribution" multiple>
						@foreach($animal_multiselects['owner']['animals'] as $animal) 
							<option value='{{$animal->id}}'>{{$animal->name}}</option> 
						@endforeach
					</select>
				</div>
			</div>
			@endif

			@if ($animal_multiselects['guest']['qualifies_for_multiselect'])
			<div class="form-group">
				<label for='multiselect-guest' class="control-label col-md-4">Gastgezin <?=$animal_multiselects['guest']['model']->name?></label>
				<div class="col-md-8">
					<select id='multiselect-guest' class="form-control animal-multiselects" name="guests_distribution" multiple>
							@foreach($animal_multiselects['guest']['animals'] as $animal) 
								<option value='{{$animal->id}}'>{{$animal->name}}</option>
							@endforeach
					</select>
				</div>
			</div>
			@endif

			@if ($animal_multiselects['shelter']['qualifies_for_multiselect'])
			<div class="form-group">
				<label for='multiselect-shelter' class="control-label col-md-4">Pension <?=$animal_multiselects['shelter']['model']->name?></label>
				<div class="col-md-8">
					<select id='multiselect-shelter' class="form-control animal-multiselects" name="shelter_distribution" multiple>
							@foreach($animal_multiselects['shelter']['animals'] as $animal) 
								<option value='{{$animal->id}}'>{{$animal->name}}</option>
							@endforeach
					</select>
				</div>
			</div>
			@endif

		</div>
	
		@endif
		</div>

		<div class="col-md-6">
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
	<script>
		document.getElementById('multiselect-wrapper').addEventListener('change', (e) =>{
			const selected = Array.from(document.querySelectorAll('.animal-multiselects'))
				.map(multiSelect =>{
					return Array.from(multiSelect.selectedOptions)
						.map(option => option.value)
				});
				document.getElementById('secret-animal-distribution-id-list').value = selected.flat().join(',')
		});
	</script>
@stop
