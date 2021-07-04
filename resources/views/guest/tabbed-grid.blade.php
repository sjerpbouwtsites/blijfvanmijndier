<div class="animal-grid animal-grid--animal-fix" id='animal-grid'>
    @foreach ($guests as $guest)
    
    <a class='animal-grid__block animal-grid__block--animal-fix' href="{{ URL::to('guests/' . $guest->id) }}">
        
        {{-- <div class='animal-grid__image-outer animal-grid__image-outer--animal-fix'>          
            
        </div> --}}



        <div class="animal-grid__text animal-grid__text--animal-fix">
            <span class="animal-grid__animal-name animal-grid__animal-name--animal-fix">{{ $guest->name }}</span>
            <span class="animal-grid__animal-description animal-grid__animal-description--animal-fix" title='Woonplaats en diervoorkeuren'>{{ $guest->city }}{{$guest->get_animal_preference_string()}}</span>
        </div>
        
        <div class='animal-grid__block-footer animal-grid__block-footer--animal-fix'>

            <div class='animal-grid__block-footer-left'>
            
            <?php if ($guest->has_prompts) : ?>

                <ol class='animal-grid__prompts animal-grid__prompts--animal-fix'>
                @foreach ($guest->prompts as $prompt)
                    <li class='animal-grid__prompt-item animal-grid__prompt-item'><?=$prompt?></li>
                @endforeach
                </ol>
           <?php endif; ?>

           <?php $first_name = $guest->get_first_name();?>
            <ul class='animal-grid__contact-data animal-grid__prompts animal-grid__prompts--animal-fix'>
                <li class='animal-grid__contact-data-item animal-grid__contact-data-item--spaced animal-grid__prompts-item--animal-fix'>
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

        <div class="animal-grid__block-footer-right <?=$guest->has_icons ? '' : 'animal-grid__block-footer-right--no-content'?>">
            @include('generic.updates-icons', ['icon_data' => [
                'has_icons' => $guest->has_icons,
                'icons' => $guest->icons,
            ]]) 
        </div>

        </div>
        
    </a>
    @endforeach
</div>

<style>
    
    .animal-grid__block.animal-grid__block--animal-fix {
justify-content: space-between;
display: flex;
flex-direction: column;
min-height: 180px;
}
.animal-grid__block-footer.animal-grid__block-footer--animal-fix {
        display: flex;
        flex-direction: row;
        padding-right: 0;
    }
    .animal-grid__block-footer-left {
        width: 140px;
        margin-right: .5em;
    }
    .animal-grid__block-footer-right {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-around;
        align-items: center;
    }
    .animal-grid__block-footer-right--no-content {
        visibility: hidden;
    }
    .animal-grid__block-footer-right .animal-grid__icons {
    width: initial;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-around;
    align-items: center;
    width: 100%;
}
    .animal-grid__block-footer--animal-fix .animal-grid__icon-item {
    flex-basis: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-grow: 1;
    min-width: 100%;
}

.animal-grid__icon-item.animal-grid__icon-item--hourglass.animal-grid__icon-item--heart-o {
    background-color: #ce1d1d;
    color: #f5f5f5;

}
.animal-grid__icon-item.animal-grid__icon-item--clock-o.animal-grid__icon-item--heart-o {
    background-color: #ce1d1d77 ;
    color: #f2f2f2;
}
.animal-grid__icon-item.animal-grid__icon-item--chain-broken {
    background-color: #322929c9;
color: #f2f2f2;
}

    .animal-grid__contact-data.animal-grid__prompts {
        list-style-type: none;
    }
    .animal-grid__contact-data-item--spaced {
        margin: .50em 0 .50em -.25em;
        padding: .25em;
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
           } else {
               return;
           }
           const r = e.target.getAttribute('data-href');
           if (r) {
                location.href = e.target.getAttribute('data-href');
           } else {
               e.target.textContent = "data ontbreekt";
           }
               
           
        });
    </script>