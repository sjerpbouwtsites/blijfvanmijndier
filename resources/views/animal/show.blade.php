@extends('layout')

@section('content')
	<div class="col-md-9">
		<h3 class='titel-letter'>Overzicht dier</h3>
		<h5><a href="{{ URL::to('animals/' . $animal->id . '/edit') }}" class="btn btn-primary">Wijzigen</a> <a href="{{ URL::to('animals') }}" class="btn btn-default">Terug naar overzicht</a></h5> 

        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        @if($animal->end_date != null)
            <div class="alert alert-danger">
                <p>Afgemeld sinds {{ $animal->end_date }} ({{$animal->endtypeDesc}})</p>
                <p><b>Toelichting:</b></p>
                <p>{{$animal->end_description}}</p>
            </div>
        @endif

        <div class="col-md-6  form-horizontal">
            <h4>Details</h4>

            @include('show_row', ['label' => 'Naam', 'value' => $animal->name])
            @include('show_row', ['label' => 'Status', 'value' => $animal->animalstatusDesc])
            @include('show_row', ['label' => 'Soort dier', 'value' => $animal->animaltypeDesc])
            @include('show_row', ['label' => 'Ras', 'value' => $animal->breedDesc])
            @include('show_row', ['label' => 'Geslacht', 'value' => $animal->gendertypeDesc])
            @include('show_row', ['label' => 'Aanmelddatum', 'value' => $animal->registration_date])
            @include('show_row', ['label' => 'Geboortedatum', 'value' => $animal->birth_date])
            @include('show_row', ['label' => 'Chipnummer', 'value' => $animal->chip_number])
            @include('show_row', ['label' => 'Paspoortnummer', 'value' => $animal->passport_number])
            @include('show_row', ['label' => 'Aantal uur alleen', 'value' => $animal->max_hours_alone])
            <?php if ($animal->abused) {?>
                @include('show_row', ['label' => 'Zelf mishandeld', 'value' => "ja"])
            <?php } ?>
            <?php if ($animal->witnessed_abuse) {?>
                @include('show_row', ['label' => 'Getuige van mishandeling', 'value' => "ja"])
            <?php } ?>
            
            
            @include('show_row', ['label' => 'Updates', 'value' => "Ja"])
            <p title='Dit aanvinkvakje betreft de twee-wekelijkse berichten naar de eigenaar over diens dier. Iemand in de opvang kan het dier bij hebben en heeft dan geen updates nodig. Dit vinkje is deel 1. Deel 2 is: indien een dier tegelijk niet gekoppeld is aan een opvang of pension, dan wordt niet gecontroleerd op het tijdig verzenden van deze berichten aan de eigenaar. Deze data synchroniseert niet met elkaar en moet je apart uit/aanvinken.'><strong><span style='color: rgb(81, 81, 212);
                font-size: 2em;
                line-height: 1em;
                position: relative;
                top: 4px;
                right: 3px;'>ℹ</span> duiding van 'updates'</strong> </P>


        </div>
        <div class="col-md-6">
                <div class="col-md-12">
                    <img class="img_margin_bottom" src="{{ URL::asset($animal->animalImage) }}" alt="{{ $animal->name }}" width="175" height="175">
                </div>
            <div class="col-md-6">
                @include('show_checkbox_list', ['title' => 'Gedrag', 'list' => $behaviourListChecked])
                @include('show_checkbox_list', ['title' => 'Vaccinaties', 'list' => $vaccinationListChecked])
            </div>
            <div class="col-md-6">
                @include('show_checkbox_list', ['title' => 'Wonen', 'list' => $home_typeListChecked])
            </div>
        
        </div>
	</div>    	

	@include('animal.sidemenu', ['animal_id' => $animal->id, 'updates_checked' => $animal->updates_checked, 'owner_id' => $animal->owner_id, 'guest_id' => $animal->guest_id, 'shelter_id' => $animal->shelter_id, 'updates' => $updates])

@stop
