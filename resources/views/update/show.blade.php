@extends('layout')

@section('content')
	<div class="col-md-9">
		<h3 class='titel-letter'>Overzicht update van {{$name}}</h3>
		<h5><a href="{{ URL::to($type . '/' . $link_id . '/updates/' . $update->id . '/edit') }}" class="btn btn-primary">Wijzigen</a> <a href="{{ URL::to($type . '/' . $link_id . '/updates') }}" class="btn btn-default">Terug naar updates {{$name}}</a> <a href="{{ URL::to('updates/selection') }}" class="btn btn-default">Terug naar alle updates</a></h5> 

        @include('session_messages')

        <div class="col-md-6  form-horizontal">
            <h4>Details</h4>

            @include('show_row', ['label' => 'Datum', 'value' => $update->start_date])
            @include('show_row', ['label' => 'Soort update', 'value' => $update->updatetypeDesc])
            @include('show_row', ['label' => 'Medewerker', 'value' => $employeeName])
            @include('show_row', ['label' => $name_label, 'value' => $name])

        </div>
        <div class="col-md-6">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
            </div>
        </div>

        <div class="col-md-12 form-horizontal">
            <div class="form-group row">
                <label for="text" class="control-label col-md-2">Tekst</label>
                <div class="col-md-8 form-control-static">
                {!! nl2br(e($update->text)) !!}
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
	</div>    	
@stop
