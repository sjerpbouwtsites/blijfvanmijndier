<div class="animal-grid">
    @foreach ($animals as $animal)
    <a class='animal-grid__block' href="{{ URL::to('animals/' . $animal->id) }}">
        
        <div class='animal-grid__image-outer'>
            <img loading='lazy' class="animal-grid__image" src="{{ $animal->animalImage }}" alt="{{ $animal->name }}" width="180" height="180">
            
          
            @include('animal.updates-icons', ['updates_checked' => $animal->updates_checked])

        </div>
        <div class="animal-grid__text">
            <span class="animal-grid__animal-name">{{ $animal->name }}</span>
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