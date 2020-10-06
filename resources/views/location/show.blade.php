@extends('layout')

@section('content')
	<div class="col-md-9">
		<h3>Overzicht opvanglocatie</h3>
		<h5><a href="{{ URL::to('locations/' . $location->id . '/edit') }}" class="btn btn-primary">Wijzigen</a> <a href="{{ URL::to('locations') }}" class="btn btn-default">Terug naar overzicht</a></h5> 

        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <div class="col-md-6  form-horizontal">
            <h4>Details</h4>

            @include('show_row', ['label' => 'Naam', 'value' => $location->name])
            @include('show_row', ['label' => 'Straat', 'value' => $location->street])
            @include('show_row', ['label' => 'Huisnummer', 'value' => $location->house_number])
            @include('show_row', ['label' => 'Postcode', 'value' => $location->postal_code])
            @include('show_row', ['label' => 'Woonplaats', 'value' => $location->city])
            @include('show_row', ['label' => 'Telefoonnummer', 'value' => $location->phone_number])
            @include('show_row', ['label' => 'Emailadres', 'value' => $location->email_address])

        </div>
        <div class="col-md-6">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
            </div>
        </div>
	</div>    	

<!--     @include('location.sidemenu') -->
@stop
