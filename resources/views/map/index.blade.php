@extends('layout')

@section('content')
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
          Enkele popups zijn gemaakt met dummy data. Dit is niet verbonden met de database. <br>
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
          <li class='map__list-item'>Paars: dier</li>
        </ol>
      </section>      
    </aside>
  </div>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link rel="stylesheet" href="css/app.css" />  

  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script src="js/app.js">




  </script>
@endsection;
