<div class="animal-grid <?=!empty($animal_grid_modifier)?$animal_grid_modifier:''?>">
    @foreach ($animals as $animal)
    <a class='animal-grid__block <?=isset($animal->checkerboard_css) ? $animal->checkerboard_css : ''?>' href="{{ URL::to('animals/' . $animal->id) }}">
        
        <div class='animal-grid__image-outer'>
            <div class='animal-grid__image-weetikveelhoedatinhetengelsheetpotvolkoffie' data-sigh='jeez' data-lang-pref='FRL'>
                @if ($animal->updates_checked['is_with_owner'])
                    <div class='animal-grid__image-heart-ribbon'>
                        <i class="fa fa-heart fa-heart-icon"></i>
                    </div>
                @endif
                <img loading='lazy' class="animal-grid__image" src="{{ $animal->animalImage }}" alt="{{ $animal->name }}" width="180" height="180">
            </div>
          
            @include('generic.updates-icons', ['icon_data' => $animal->updates_checked])

        </div>
        <div class="animal-grid__text">
            <span class="animal-grid__animal-name titel-letter">{{ $animal->name }}</span>
            <span class="animal-grid__animal-description">{{ $animal->breedDesc }}</span>
        </div>
        <div class='animal-grid__block-footer'>
            
            @if ($animal->updates_checked['has_prompts'])

                <ol class='animal-grid__prompts' title='weken achter op planning: {{\round($animal->updates_checked['days_behind'] / 7)}}'>
                @foreach ($animal->updates_checked['update_prompts'] as $prompt)
                    <li class='animal-grid__prompt-item'>{{$prompt}}</li>
                @endforeach
                </ol>
            @endif

        </div>
        
    </a>
    @endforeach
</div>
