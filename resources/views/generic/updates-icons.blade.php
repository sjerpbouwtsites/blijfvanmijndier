<ul class='animal-grid__icons '>
    @if ($icon_data['has_icons'])    
    @foreach ($icon_data['icons'] as $icon_row)

        <?php 
            $item_bem_a = [];
            foreach($icon_row['fa_classes'] as $icon_class) {
                $item_bem_a[] = "animal-grid__icon-item--$icon_class";
            }
            $item_bem = implode(' ', $item_bem_a);
        ?>
        <li title='{{$icon_row['title_attr']}}' class='animal-grid__icon-item <?=$item_bem?>'>
            @foreach ($icon_row['fa_classes'] as $icon_class)
                <i class='fa fa-{{$icon_class}}'></i>
            @endforeach
        </li>
    @endforeach
    @else
    <li title='Alles op orde!' class='animal-grid__icon-item animal-grid__icon-item--all-good'>
        
        <i class='fa fa-hand-peace-o'></i>
        
    </li>    
    @endif
</ul>            