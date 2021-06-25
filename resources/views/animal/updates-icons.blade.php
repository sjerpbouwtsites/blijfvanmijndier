@if ($updates_checked['has_icons'])
<ul class='animal-grid__icons'>
    @foreach ($updates_checked['icons'] as $icon_row)
        <li title='{{$icon_row['title_attr']}}' class='animal-grid__icon-item'>
            @foreach ($icon_row['fa_classes'] as $icon_class)
                <i class='fa fa-{{$icon_class}}'></i>
            @endforeach
        </li>
    @endforeach
</ul>            
@endif