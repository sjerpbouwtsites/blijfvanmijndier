@extends('layout')

@section('content')
  <div class='map__outer'>
    <div class='map__inner' id='leaflet-map'>

    </div>
  </div>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <style>
    .map__inner {
      height: 100vh;
      width: 100vw;
      position: absolute;
      top: 0;
      left: 0;
    }
  </style>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script src="js/app.js">




  </script>
@endsection;
