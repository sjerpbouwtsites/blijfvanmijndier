<div class="animal-grid   <?=!empty($animal_grid_modifier)?"animal-grid--$animal_grid_modifier":''?>" id='animal-grid'>
    @foreach ($guests as $guest)
    
    <a class='animal-grid__block  <?=isset($guest->checkerboard_css) ? $guest->checkerboard_css : ''?>' href="{{ URL::to('guests/' . $guest->id) }}">
        
        {{-- <div class='animal-grid__image-outer animal-grid__image-outer--animal-fix'>          
            
        </div> --}}

        <div class="animal-grid__text ">
            <span class="animal-grid__animal-name titel-letter">{{ $guest->name }}</span>
            <span class="animal-grid__animal-description " title='Woonplaats en diervoorkeuren'>{{$guest->get_animal_preference_string()}}</span>
        </div>
        
        <div class='animal-grid__block-footer '>

            <div class='animal-grid__block-footer-left'>
            
            <?php if ($guest->has_prompts) : ?>

                <ol class='animal-grid__prompts '>
                @foreach ($guest->prompts as $prompt)
                    <li class='animal-grid__prompt-item animal-grid__prompt-item'><?=$prompt?></li>
                @endforeach
                </ol>
           <?php endif; ?>

           <?php $first_name = $guest->get_first_name();?>
            <ul class='animal-grid__contact-data animal-grid__prompts'>
                <li class='animal-grid__contact-data-item animal-grid__contact-data-item--spaced '>
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
                    <?=$copy_address_buttons_html[$guest->id]?>

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

</style>