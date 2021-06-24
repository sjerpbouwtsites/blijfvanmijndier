<nav class='col-md-12 bvmd-tabbladen'>
    <ul class="bvmd-tabbladen__lijst">
        @foreach ($tabs_data as $tab)
            <li class="bvmd-tabbladen__lijst-item">
                @if ($tab['active'])
                    <span class='bvmd-tabbladen__link bvmd-tabbladen__link--actief'>
                        <span class='bvmd-tabbladen__link-binnen bvmd-tabbladen__link-binnen--actief'> <?=$tab['text']?>
                        </span>
                    </span>
                @else
                    <a class='bvmd-tabbladen__link bvmd-tabbladen__link--inactief' href="<?=$tab['url']?>">
                        <span class='bvmd-tabbladen__link-binnen bvmd-tabbladen__link-binnen--inactief'>
                            <?=$tab['text']?>
                        </span>
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</nav>

<style>
    #app-body {
        overflow-x: hidden;
    }
    .bvmd-tabbladen {
        font-size: 1rem;
        height: 12em;
        margin-top: -2em;
        margin-bottom: 2em;
    }
    .bvmd-tabbladen__lijst {
        display: flex;
        flex-direction: row;
        margin: 0;
        padding: 0;
        list-style-type: none;
        height: 100%;
        position: relative;
    }
    .bvmd-tabbladen__lijst::after {
        content: '';
        display: block;
        position: absolute;
        left: -50vw;
        top: 0;
        height: 100%;
        width: 200vw;
        background-color: #fff;
    }
    .bvmd-tabbladen__lijst-item {
        z-index: 3;
        position:relative;
        top: 2em;

    }
    .bvmd-tabbladen__link {
        font-size: 2em;
        font-weight: 900;
        display: block;
        padding: .75em 0;
    }
    .bvmd-tabbladen__link--inactief:hover {
        /* color: #ce1d1dbb; */
    }
    .bvmd-tabbladen__link--actief {
        /* background-color: #ce1d1d44;
        color: white; */
    }
    .bvmd-tabbladen__link,
    .bvmd-tabbladen__link--inactief:hover {
        color: #212121;
    }
    .bvmd-tabbladen__link-binnen {
        padding: 0.25em 1.5em;
        border-bottom: 5px solid #212121;   
    }
    .bvmd-tabbladen__link-binnen--actief,
    .bvmd-tabbladen__link:hover .bvmd-tabbladen__link-binnen {
        border-bottom-color: #ce1d1d;   
    }
    .bvmd-tabbladen:hover .bvmd-tabbladen__link-binnen--actief {
        border-bottom-color: #212121;
    }
    .bvmd-tabbladen:hover .bvmd-tabbladen__link:hover .bvmd-tabbladen__link-binnen--actief {
        border-bottom-color: #212121;
        cursor: initial;
    }

    .bvmd-tabbladen__lijst-item:first-child .bvmd-tabbladen__link-binnen {
        padding-left: .75em;
    }
    .bvmd-tabbladen__lijst-item:last-child .bvmd-tabbladen__link-binnen {
        padding-right: .75em;
    }
</style>