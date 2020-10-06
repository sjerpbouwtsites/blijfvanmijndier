@extends('layout')

@section('content')
	<div class="col-md-9">
		<h3>Overzicht pension</h3>
		<h5><a href="{{ URL::to('shelters/' . $shelter->id . '/edit') }}" class="btn btn-primary">Wijzigen</a> <a href="{{ URL::to('shelters') }}" class="btn btn-default">Terug naar overzicht</a></h5> 

        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <div class="col-md-6  form-horizontal">
            <h4>Details</h4>

            @include('show_row', ['label' => 'Naam', 'value' => $shelter->name])
            @include('show_row', ['label' => 'Straat', 'value' => $shelter->street])
            @include('show_row', ['label' => 'Huisnummer', 'value' => $shelter->house_number])
            @include('show_row', ['label' => 'Postcode', 'value' => $shelter->postal_code])
            @include('show_row', ['label' => 'Woonplaats', 'value' => $shelter->city])
            @include('show_row', ['label' => 'Telefoonnummer', 'value' => $shelter->phone_number])
            @include('show_row', ['label' => 'Emailadres', 'value' => $shelter->email_address])
            @include('show_row', ['label' => 'Website', 'value' => $shelter->website])
            @include('show_row', ['label' => 'Contactpersoon', 'value' => $shelter->contact_person])

            <div class="form-group row">
                <label for="text" class="control-label col-md-4">Afspraken</label>
                <div class="col-md-4 form-control-static">
                {!! nl2br(e($shelter->remarks_contract)) !!}
                </div>
                <div class="col-md-4"></div>
            </div>

            <div class="form-group row">
                <label for="text" class="control-label col-md-4">Opmerkingen</label>
                <div class="col-md-4 form-control-static">
                {!! nl2br(e($shelter->remarks_general)) !!}
                </div>
                <div class="col-md-4"></div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="col-md-12">
                @include('show_link_list', ['title' => 'Dieren', 'list' => $animals, 'link' => 'unconnectshelter'])
            </div>
        </div>

	</div>    	

    @include('shelter.sidemenu') 
@stop
