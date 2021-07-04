<div class="animal-grid">
    @foreach ($guests as $guest)
    
    <a class='animal-grid__block' href="{{ URL::to('guests/' . $guest->id) }}">
        
        <div class='animal-grid__image-outer'>          
            
        </div>
        <div class="animal-grid__text">
            <span class="animal-grid__animal-name">{{ $guest->name }}</span>
            <span class="animal-grid__animal-description">{{ $guest->city }}</span>
        </div>
        <div class='animal-grid__block-footer'>
            
            <?php if ($guest->has_prompts) : ?>

                <ol class='animal-grid__prompts'>
                @foreach ($guest->prompts as $prompt)
                    <li class='animal-grid__prompt-item'>{{$prompt}}</li>
                @endforeach
                </ol>
           <?php endif; ?>

        </div>
        
    </a>
    @endforeach
</div>