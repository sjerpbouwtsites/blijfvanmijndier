<div class="animal-grid animal-grid--animal-fix  <?=!empty($animal_grid_modifier)?$animal_grid_modifier:''?>" id='animal-grid'>
    @foreach ($guests as $guest)
    
    <a class='animal-grid__block animal-grid__block--animal-fix <?=isset($guest->checkerboard_css) ? $guest->checkerboard_css : ''?>' href="{{ URL::to('guests/' . $guest->id) }}">
        
        {{-- <div class='animal-grid__image-outer animal-grid__image-outer--animal-fix'>          
            
        </div> --}}

        <div class="animal-grid__text animal-grid__text--animal-fix">
            <span class="animal-grid__animal-name animal-grid__animal-name--animal-fix">{{ $guest->name }}</span>
            <span class="animal-grid__animal-description animal-grid__animal-description--animal-fix" title='Woonplaats en diervoorkeuren'>{{$guest->get_animal_preference_string()}}</span>
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
                    <span class='fake-anchor-part'>
                        <button class='fake-anchor fake-mailto' title='Stuur mail naar <?=$guest->name?>' data-href='mailto:<?=$guest->email_address?>'><i class='fa fa-envelope'></i></button> 
                        <button class='fake-anchor fake-tel' title='Bel <?=$guest->name?>. Vereist mogelijk configureren belsoftware zoals skype of een integratie met een mobiele telefoon.' data-href='tel:<?=$guest->phone_number?>'><i class='fa fa-phone'></i></button>
                    </span>
                    <span class="fake-anchor-part">
                        <button class='fake-anchor fake-mailto clipboard' title='Kopieer emailadres <?=$guest->name?> naar clipboard' data-href='<?=$guest->email_address?>'><i class="fa fa-save" aria-hidden="true"></i>
                            <i class='fa fa-envelope'></i></button> 
                    <button class='fake-anchor fake-tel clipboard' title='Kopieer telefoonnummer <?=$guest->name?> naar clipboard.' data-href='<?=$guest->phone_number?>'><i class="fa fa-save" aria-hidden="true"></i>
                        <i class='fa fa-phone'></i></button>
                    </span>
                    </span>
                    

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
        padding: 0;
    }
    .animal-grid__block-footer-left {
        width: 60%;
padding: .5em;
padding-top: 0;
    }
    .animal-grid__prompt-item {
        margin-bottom: .33em;
    }
    .animal-grid__prompt-item:last-child {
margin-bottom: 0;

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
    background-color: #56234c;
    color: #f5f5f5;
    

}
.animal-grid__icon-item.animal-grid__icon-item--clock-o.animal-grid__icon-item--heart-o {
    background-color: #f15a40;
    color: #f2f2f2;
}
.animal-grid__icon-item.animal-grid__icon-item--chain-broken {
    background-color: #322929c9;
color: #f2f2f2;
}


    .animal-grid__contact-data.animal-grid__prompts {
        list-style-type: none;
        margin-bottom: 0;
        margin-top: 1.25em;
    }
    .animal-grid__contact-data-item--spaced {
        margin: .50em 0 .50em -.25em;
        padding: .25em;
    }
    .fake-anchor-part {
        margin-right: 1em;
    }
    .fake-anchor {
        background-color: transparent;
border: 0;
padding: 0;
text-decoration: none;
font-weight: bold;
font-size: 1.5em;
vertical-align: bottom;

}
.fake-anchor:first-child {  
    margin-right: .5rem;
    }
    a:hover .fake-anchor {
        text-decoration: none;
    }
    .fake-anchor:hover {
        color: #f15a40;
        
    }
    .fake-anchor.enable-button-blink {
        transition: 0.2s ease-in-out 0.2s;
filter: saturate(100%) brightness(100%);        
    }
    .fake-anchor.button-blink {
filter:        saturate(200%) brightness(200%)
    }
    .fake-anchor.fake-tel {
        position: relative;
        top: 1px;
    }
    .fake-anchor.clipboard{
        position: relative;
    }
    
    .fake-anchor.clipboard .fa-save{
        position: absolute;
left: 8px;
top: -3px;
font-size: .75em;
color:#8a7575;
    }
    .fake-anchor.clipboard .fa-save + .fa {
        font-size: .75em;
    }
</style>
    <script>
        document.getElementById('animal-grid').addEventListener('click', (e)=>{

            if (e.target.className.includes('fa')) {

                e.preventDefault();
                e.stopPropagation();
            
            }

            let knop;
            if (e.target.classList.contains('fake-anchor')) {
                knop = e.target;
            } else if (e.target.parentNode.classList.contains('fake-anchor')) {
                knop = e.target.parentNode;
            } else {
                return
            }

           const r = knop.getAttribute('data-href');

           if (knop.classList.contains('clipboard')) {
            copyToClipboard(r)
            knop.classList.add('enable-button-blink');
            setTimeout(()=>{
                knop.classList.add('button-blink');
            }, 50)            
            setTimeout(()=>{
                knop.classList.remove('button-blink');
            }, 1000)    

           } else {
               location.href = r;
           }
           return false;
           
        });
    
        function copyToClipboard(text) {
  var input = document.body.appendChild(document.createElement("input"));
  input.value = text;
  input.focus();
  input.select();
  document.execCommand('copy');
  input.parentNode.removeChild(input);
}
    
    </script>