
<!-- Load Leaflet from CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.js"></script>

<!-- Load geocoding plugin after Leaflet -->
<link rel="stylesheet" href="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.6/leaflet-geocoder-locationiq.min.css">
<script src="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.6/leaflet-geocoder-locationiq.min.js"></script>

  <style>
    #map {
      width: 100%;
      height: 500px;
      z-index: 10;
      margin: 20px 0;
    }
    .leaflet-control-zoom {
      display: none;
    }
  </style>

    <div class='row'>
      <div class='col-md-6'>
        @include('form_text', ['field' => 'house_number', 'label' => 'H.nummer'])
      </div>
      <div class='col-md-6'>
        @include('form_text', ['field' => 'street', 'label' => 'Straat'])
      </div>      
    </div>
    
    <div class='row'>
      <div class='col-md-6'>
        @include('form_text', ['field' => 'postal_code', 'label' => 'Postcode'])
      </div>
      <div class='col-md-6'>
        @include('form_text', ['field' => 'city', 'label' => 'Plaats'])
      </div>      
    </div>

    <div class='row hidden'>
      <div class='col-md-6'>
        @include('form_text', ['field' => 'longitude', 'label' => 'Lengtegraad'])
      </div>
      <div class='col-md-6'>
        @include('form_text', ['field' => 'lattitude', 'label' => 'Breedtegraad'])
      </div>      
    </div>
    
    
  <!-- For the invisible map -->
  <div id="map"></div>
  <!-- For the search box -->
  <div id="search-box"></div>
  <!-- To display the result -->
  <div id="result"></div>
  
  <script>
    // This is an example of Leaflet usage; you should modify this for your needs.
  var map = L.map('map').setView([52.195, 4.036], 7);
  L.tileLayer('https://{s}-tiles.locationiq.com/v2/obk/r/{z}/{x}/{y}.png?key=b7a32fa378c135').addTo(map);

  const marker = L.marker(
        [document.getElementById('lattitude').value, document.getElementById('longitude').value]
      ).addTo(map);        

  var geocoder=L.control.geocoder('b7a32fa378c135').addTo(map);

    // open location IQ control.

    geocoder.on('select', function (e) {
      marker._icon.classList.add('hidden')
      marker._shadow.classList.add('hidden')
      document.getElementById('lattitude').value = e.latlng.lat;
      document.getElementById('longitude').value = e.latlng.lng;
      
    });

    function expandGeocoderAlsNietExpanded(){
      if (!document.querySelector('.leaflet-locationiq-control').classList.contains('leaflet-locationiq-expanded')) {
        document.querySelector('.leaflet-locationiq-control').classList.add('locationiq-expanded')
      document.querySelector('.leaflet-locationiq-control').classList.add('leaflet-locationiq-expanded')      
      }
    }


    var wachtEvenDanZoeken = null;

    function clearClickTimeout(){
      document.querySelector('.leaflet-locationiq-input').addEventListener('click', ()=>{
        clearTimeout(wachtEvenDanZoeken)
      })
    }

    function schrijfAdresNaarGeocoder(){

      expandGeocoderAlsNietExpanded()
      const city = document.getElementById('city').value
      const postalCode = document.getElementById('postal_code').value
      const street = document.getElementById('street').value
      const houseNumber = document.getElementById('house_number').value


      document.querySelector('.leaflet-locationiq-input').value = `${city} ${postalCode} ${street} ${houseNumber}`;

      clearTimeout(wachtEvenDanZoeken)
      wachtEvenDanZoeken = setTimeout(()=>{
        location.hash = '#street';
        document.querySelector('.leaflet-locationiq-input').click();
      }, 1500)
      
    }


    function initGeocoderVoorAdres(){
      expandGeocoderAlsNietExpanded();
  
      document.getElementById('city').addEventListener('keyup', schrijfAdresNaarGeocoder)
      document.getElementById('postal_code').addEventListener('keyup', schrijfAdresNaarGeocoder)
      document.getElementById('street').addEventListener('keyup', schrijfAdresNaarGeocoder)
      document.getElementById('house_number').addEventListener('keyup', schrijfAdresNaarGeocoder)
    }

    window.addEventListener('load', initGeocoderVoorAdres)

  </script>  

