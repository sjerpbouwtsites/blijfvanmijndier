<div class="animal-grid" id='animal-grid'>
    @foreach ($guests as $guest)
    
    <a class='animal-grid__block' href="{{ URL::to('guests/' . $guest->id) }}">
        
        <div class='animal-grid__image-outer'>          
            
        </div>
        <div class="animal-grid__text">
            <span class="animal-grid__animal-name">{{ $guest->name }}</span>
            <span class="animal-grid__animal-description" title='Woonplaats en diervoorkeuren'>{{ $guest->city }}{{$guest->get_animal_preference_string()}}</span>
        </div>
        <div class='animal-grid__block-footer'>
            
            <?php if ($guest->has_prompts) : ?>

                <ol class='animal-grid__prompts'>
                @foreach ($guest->prompts as $prompt)
                    <li class='animal-grid__prompt-item'><?=$prompt?></li>
                @endforeach
                </ol>
           <?php endif; ?>

           <?php $first_name = $guest->get_first_name();?>
            <ul class='animal-grid__contact-data animal-grid__prompts'>
                <li class='animal-grid__contact-data-item animal-grid__contact-data-item--spaced animal-grid__prompts-item'>
                    <button class='fake-anchor fake-mailto' data-href='mailto:<?=$guest->email_address?>'>Mail <?=$first_name?></button> | <button class='fake-anchor fake-tel' data-href='tel:<?=$guest->phone_number?>'>Bel <?=$first_name?></button>
                </li>   
                <li class='animal-grid__contact-data-item animal-grid__prompts-item'>
                    <span><?=$guest->street?> <?=$guest->house_number?></span>
                </li>   
                <li class='animal-grid__contact-data-item animal-grid__prompts-item'>
                    <span><?=$guest->city?> </span>
                </li>   
            </ul>

        </div>
        
    </a>
    @endforeach
</div>

<style>
    .animal-grid__contact-data.animal-grid__prompts {
        list-style-type: none;
    }
    .animal-grid__contact-data-item--spaced {
        margin-bottom: .5em;
    }
    .fake-anchor {
        background-color: transparent;
        border: 0;
        padding: 0;
        text-decoration: underline;
        font-weight: bold;
    }
    .fake-anchor:hover {
        color: #ce1d1d;
    }
</style>
    <script>
        document.getElementById('animal-grid').addEventListener('click', (e)=>{
           if (e.target.classList.contains('fake-anchor')) {
               e.preventDefault();
               e.stopPropagation();
           }

               location.href = e.target.getAttribute('data-href');
           
        });
    </script>