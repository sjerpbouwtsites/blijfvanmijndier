@extends('layout')

@section('content')
<div class='map__dialog' id='map-own-dialog' >
  <div  class='map__dialog-inner'>
    <a id='map-dialog-close' class='map__dialog-close'>x</a>
    <div class='map__dialog-print' id='dialog-print-target'></div>
  </div>
</div>
<div class='map__outer'>
    <div class='map__inner' id='leaflet-map'>
    </div>
    <aside class='map-aside' id='map-aside'>

    </aside>

  </div>

  <link rel="stylesheet" href="css/app.css" />  
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
@endsection
