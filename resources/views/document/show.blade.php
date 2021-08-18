@extends('layout')

@section('content')
	<div class="col-md-9">
		<h3 class='titel-letter'>Overzicht document van {{$animal->name}}</h3>
		<h5><a href="{{ URL::to('animals/' . $animal->id . '/documents/' . $document->id . '/edit') }}" class="btn btn-primary">Wijzigen</a> <a href="{{ URL::to('animals/' . $animal->id . '/documents') }}" class="btn btn-default">Terug naar overzicht</a></h5> 

        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <div class="col-md-6  form-horizontal">
            <h4>Details</h4>

            @include('show_row', ['label' => 'Datum', 'value' => $document->date])
            @include('show_row', ['label' => 'Documentsoort', 'value' => $doctypeName])
            @include('show_row', ['label' => 'Dier', 'value' => $animal->name])
            @include('show_row', ['label' => 'Tekst', 'value' => $document->text])

            <div class="form-group row">
                <div class="col-md-4"></div>
                <div class="col-md-8 form-control-static">
                    <a href="{{ URL::asset($document->documentLink) }}" class="btn btn-success" target="_blank">Bekijk document</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
            </div>
        </div>
	</div>    	
@stop
