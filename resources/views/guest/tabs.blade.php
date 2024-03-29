<nav id='tabbladen' class='col-md-12 bvmd-tabbladen'>
    <ul class="bvmd-tabbladen__lijst">
        @foreach ($tabs_data as $tab)
            <li class="bvmd-tabbladen__lijst-item">
                @if ($tab['active'])
                    <span class='bvmd-tabbladen__link bvmd-tabbladen__link--actief  titel-letter'>
                        <span class='bvmd-tabbladen__link-binnen bvmd-tabbladen__link-binnen--actief'> <?=$tab['text']?>
                        </span>
                    </span>
                @else
                    <a class='bvmd-tabbladen__link bvmd-tabbladen__link--inactief' href="<?=$tab['url']?>#tabbladen">
                        <span class='bvmd-tabbladen__link-binnen bvmd-tabbladen__link-binnen--inactief  titel-letter'>
                            <?=$tab['text']?>
                        </span>
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</nav>

<style>

</style>