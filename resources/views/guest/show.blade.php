@extends('layout')

@section('content')



	<div class="col-md-9 guest-wrapper <?=$guest->is_deregistered() ? "guest-wrapper--deregistered" : ''?>">
		<h3 class='titel-letter'>Overzicht gastgezin</h3>
		<h5><a href="{{ URL::to('guests/' . $guest->id . '/edit') }}" class="btn btn-primary">Wijzigen</a> <a href="{{ URL::to('guests') }}" class="btn btn-default">Terug naar overzicht</a></h5> 


        @if($guest->is_deregistered())
        <div class="alert alert-info">Dit gastgezin is uitgeschreven. Het doet niet meer mee.</div>    
        @else 

        @if ($guest->disabled)
            @if ($guest->today_disabled())
            <div class="alert alert-danger">Dit gastgezin is niet beschikbaar van <?=$guest->disabled_from_friendly()?> tot <?=$guest->disabled_untill_friendly()?></div>
            @else
            <div class="alert alert-info">Dit gastgezin is niet beschikbaar van <?=$guest->disabled_from_friendly()?> tot <?=$guest->disabled_untill_friendly()?></div>    
            @endif
        @endif
        @endif        

        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif        
            

        <div class="col-md-6  form-horizontal">
            <h4 class='titel-letter'>Details</h4>

            @include('show_row', ['label' => 'Naam', 'value' => $guest->name])
            @include('show_row', ['label' => 'Status', 'value' => $guest->gueststatusDesc])
            @include('show_row', ['label' => 'Straat', 'value' => $guest->street])
            @include('show_row', ['label' => 'Huisnummer', 'value' => $guest->house_number])
            @include('show_row', ['label' => 'Postcode', 'value' => $guest->postal_code])
            @include('show_row', ['label' => 'Woonplaats', 'value' => $guest->city])
            @include('show_row', ['label' => 'Telefoonnummer', 'value' => $guest->phone_number])
            @include('show_row', ['label' => 'Emailadres', 'value' => $guest->email_address])
            @include('show_row', ['label' => 'Aantal uur alleen', 'value' => $guest->max_hours_alone])
            @include('show_row', ['label' => 'Opmerking', 'value' => $guest->text])

        </div>
        <div class="col-md-6">
            <div class="col-md-6">
                @include('show_checkbox_list', ['title' => 'Wonen', 'list' => $hometypeList])
                @include('show_checkbox_list', ['title' => 'Diervoorkeur', 'list' => $animaltypeList])
            </div>
            <div class="col-md-6">
                @include('show_checkbox_list', ['title' => 'Gedrag', 'list' => $behaviourList])
                @include('show_checkbox_list', ['title' => 'Eigen dieren', 'list' => $ownAnimaltypeList])
            </div>
            <div class="col-md-12">
                @include('show_link_list', ['title' => 'Opvangdieren', 'list' => $animals, 'link' => 'unconnectguest'])
            </div>
        </div>
	</div>    	
     
    @include('guest.sidemenu') 
@stop
