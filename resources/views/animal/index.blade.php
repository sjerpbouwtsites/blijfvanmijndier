@extends('layout')
 <?php // LEGACY bestand ?>
@section('content')

<?=$tabs?>

<div class="col-md-12" >

    @include('session_messages')

    <div class="animal-grid">
        @foreach ($animals as $animal)
        <a class='animal-grid__block' href="{{ URL::to('animals/' . $animal->id) }}">
            
            <div class='animal-grid__image-outer'>
                <img loading='lazy' class="animal-grid__image" src="{{ $animal->animalImage }}" alt="{{ $animal->name }}" width="180" height="180">
                
                
                @if ($animal->updates_checked['has_icons'])
                <ul class='animal-grid__icons'>
                    @foreach ($animal->updates_checked['icons'] as $icon_row)
                        <li title='{{$icon_row['title_attr']}}' class='animal-grid__icon-item'>
                            @foreach ($icon_row['fa_classes'] as $icon_class)
                                <i class='fa fa-{{$icon_class}}'></i>
                            @endforeach;
                        </li>
                    @endforeach
                </ul>                
                @endif

            </div>
            <div class="animal-grid__text">
                <span class="animal-grid__animal-name">{{ $animal->name }}</span>
                <span class="animal-grid__animal-description">{{ $animal->breedDesc }}</span>
            </div>
            <div class='animal-grid__block-footer'>
                
                @if ($animal->updates_checked['has_prompts'])
                    <ol class='animal-grid__prompts'>
                    @foreach ($animal->updates_checked['update_prompts'] as $prompt)
                        <li class='animal-grid__prompt-item'>{{$prompt}}</li>
                    @endforeach
                    </ol>
                @endif
            </div>
            
        </a>
        @endforeach
    </div>
</div>

<?php echo $animalsOldView; ?>

@stop

