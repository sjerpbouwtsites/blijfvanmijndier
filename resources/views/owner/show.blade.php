@extends('layout')

@section('content')
	<div class="col-md-9">
		<h3>Overzicht eigenaar</h3>
		<h5><a href="{{ URL::to('owners/' . $owner->id . '/edit') }}" class="btn btn-primary">Wijzigen</a> <a href="{{ URL::to('owners') }}" class="btn btn-default">Terug naar overzicht</a></h5> 

        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <div class="col-md-6  form-horizontal">
            <h4>Details</h4>

            @include('show_row', ['label' => 'Voornaam', 'value' => $owner->name])
            @include('show_row', ['label' => 'Tussenvoegsel', 'value' => $owner->prefix])
            @include('show_row', ['label' => 'Achternaam', 'value' => $owner->surname])
            @include('show_row', ['label' => 'Straat', 'value' => $owner->street])
            @include('show_row', ['label' => 'Huisnummer', 'value' => $owner->house_number])
            @include('show_row', ['label' => 'Postcode', 'value' => $owner->postal_code])
            @include('show_row', ['label' => 'Woonplaats', 'value' => $owner->city])
            @include('show_row', ['label' => 'Telefoonnummer', 'value' => $owner->phone_number])
            @include('show_row', ['label' => 'Emailadres', 'value' => $owner->email_address])

        </div>
        <div class="col-md-6">
            <div class="col-md-12">
                @include('show_link_list', ['title' => 'Dieren', 'list' => $animals, 'link' => 'unconnectowner'])
            </div>
        </div>
	</div>    	

    @include('owner.sidemenu')
@stop
