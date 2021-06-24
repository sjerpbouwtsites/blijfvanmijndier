@extends('layout')

@section('content')

<div class="col-md-12">

    <h3>Overzicht dieren
        <a class='btn btn-large btn-primary btn-lg' style='float: right' href="{{ URL::to('animals/create') }}">
            Nieuw dier toevoegen
        </a>
    </h3>

</div>

<div class="col-md-12" style='margin-top: 1.5em'>

    @include('session_messages')

    <div class="animal-grid">
        @foreach ($animals as $animal)
        <a class='animal-grid__block' href="{{ URL::to('animals/' . $animal->id) }}">
            
            <img class="animal-grid__image" src="{{ $animal->animalImage }}" alt="{{ $animal->name }}" width="180" height="180">
            <div class="animal-grid__text">
                <span class="animal-grid__animal-name">{{ $animal->name }}</span>
                <span class="animal-grid__animal-description">{{ $animal->breedDesc }}</span>
            </div>
            <p class='animal-grid__block-footer'>
                @if ($animal->needUpdate == 1)
                heeft update nodig
                @endif
            </p>
            
        </a>
        @endforeach
    </div>
</div>

<?php echo $animalsOldView; ?>

@stop

