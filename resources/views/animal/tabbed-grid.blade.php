<div class="animal-grid">
    @foreach ($animals as $animal)
    <a class='animal-grid__block' href="{{ URL::to('animals/' . $animal->id) }}">
        
        <div class='animal-grid__image-outer'>
            <img loading='lazy' class="animal-grid__image" src="{{ $animal->animalImage }}" alt="{{ $animal->name }}" width="180" height="180">
            
            
            @if ($animal->updates_checked['has_icons'])
            <ul class='animal-grid__icons'>
                @foreach ($animal->updates_checked['icons'] as $icon)
                    <li class='animal-grid__icon-item'>
                        <i class='fa fa-{{$icon}}'></i>
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

            {{-- @if ($animal->updates_checked['in_todo_list']) 
                <span class='animal-grid__days-to-late'>dagen te laat:{{$animal->updates_checked['days_behind']}}</span>
            @endif --}}
        </div>
        
    </a>
    @endforeach
</div>