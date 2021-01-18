@extends('layout')

@section('content')
<div class='map__dialog' id='map-own-dialog' >
  <div  class='map__dialog-inner'>
    <button id='map-dialog-close' class='map__dialog-close'>X</button>
    <div class='map__dialog-print' id='dialog-print-target'></div>
  </div>
</div>
<div class='map__outer'>
    <div class='map__inner' id='leaflet-map'>
    </div>
    <aside class='map__aside' id='map-aside'>
      <section class='map__section'>
        <header class='map__header'>
          <h2 class='map__heading map__heading--2'>
            Stand van zaken
          </h2>
        </header>
        <p class='map__text'>
          Een indicatie van functionaliteiten, ontwerp is louter functioneel. <br>
        </p>
      </section>
      <section class='map__section'>
        <header class='map__header'>
          <h2 class='map__heading map__heading--2'>
            Kleuren
          </h2>
        </header>
        <ol class='map__list'>
          <li class='map__list-item'>Rood: dierenarts</li>
          <li class='map__list-item'>Paars: Pension</li>
          <li class='map__list-item'>Groen: Gastgezin</li>
          <li class='map__list-item'>Blauw: Eigenaar</li>
        </ol>
      </section>      
      <section class='map__section'>
        <header class='map__header'>
          <h2 class='map__heading map__heading--2'>
            Strepen
          </h2>
        </header>
        <ul class='map__list'>
          <li class='map__list-item'>Geen: geen aanwezige dieren</li>
          <li class='map__list-item'>Dun: E&eacute;n: 1 dier</li>
          <li class='map__list-item'>Dik: Meerdere dieren</li>
        </ul>
      </section>      
      <section class='map__section'>
        <header class='map__header'>
          <h2 class='map__heading map__heading--2'>
            Dieren
          </h2>
        </header>        
        <ul class='map__list' id='animal-list'>
        </ul>          
      </section>
      <section class='map__section'>
        <header class='map__header'>
          <h2 class='map__heading map__heading--2'>
            Filters
          </h2>          
        </header>
        <form id='map-filters' class='map__filter'>
          
        </form>
      </section>
    </aside>

  </div>

  <link rel="stylesheet" href="css/app.css" />  
  <script>
    const baseData = <?=$json?>;

    console.dir(baseData);
  </script>

  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
@endsection;
