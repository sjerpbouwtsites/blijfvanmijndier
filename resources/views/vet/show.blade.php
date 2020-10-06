@extends('layout')

@section('content')
	<div class="col-md-9">
		<h3>Overzicht dierenarts</h3>
		<h5><a href="{{ URL::to('vets/' . $vet->id . '/edit') }}" class="btn btn-primary">Wijzigen</a> <a href="{{ URL::to('vets') }}" class="btn btn-default">Terug naar overzicht</a></h5> 

        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <div class="col-md-6  form-horizontal">
            <h4>Details</h4>

            @include('show_row', ['label' => 'Naam', 'value' => $vet->name])
            @include('show_row', ['label' => 'Straat', 'value' => $vet->street])
            @include('show_row', ['label' => 'Huisnummer', 'value' => $vet->house_number])
            @include('show_row', ['label' => 'Postcode', 'value' => $vet->postal_code])
            @include('show_row', ['label' => 'Woonplaats', 'value' => $vet->city])
            @include('show_row', ['label' => 'Telefoonnummer', 'value' => $vet->phone_number])
            @include('show_row', ['label' => 'Emailadres', 'value' => $vet->email_address])
            @include('show_row', ['label' => 'Website', 'value' => $vet->website])
            @include('show_row', ['label' => 'Contactpersoon', 'value' => $vet->contact_person])

            <div class="form-group row">
                <label for="text" class="control-label col-md-4">Afspraken</label>
                <div class="col-md-4 form-control-static">
                {!! nl2br(e($vet->remarks_contract)) !!}
                </div>
                <div class="col-md-4"></div>
            </div>

            <div class="form-group row">
                <label for="text" class="control-label col-md-4">Opmerkingen</label>
                <div class="col-md-4 form-control-static">
                {!! nl2br(e($vet->remarks_general)) !!}
                </div>
                <div class="col-md-4"></div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
            </div>
        </div>
	</div>    	

<!--     @include('vet.sidemenu') -->
@stop
