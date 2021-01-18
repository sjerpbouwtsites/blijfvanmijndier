@if (
  (Session::has('geolocation_success') 
  && Session::get('geolocation_success') === false) 
  || Input::old('manual_geolocation') === 'on'
)

<div class="form-group">
  <label for="manual_geolocation" class="control-label col-md-4">handmatige geolocatie</label>
  <div class="col-md-8">
    <input name="manual_geolocation" type="checkbox" id="manual_geolocation" checked='checked'>
  </div>
</div>
<div class="alert alert-danger">
  Het is niet goed gedaan met de geolocatie.<br> <br>
  <a target='_blank' class='btn btn-primary' href='https://www.latlong.net/convert-address-to-lat-long.html'>Zoek handmatig de lengte en breedte graad op.</a>
</div>

  @include('form_text', ['field' => 'longitude', 'label' => 'lengtegraad'])
  @include('form_text', ['field' => 'lattitude', 'label' => 'lengtegraad'])
@endif
  @include('form_text', ['field' => 'street', 'label' => 'Straat'])
  @include('form_text', ['field' => 'house_number', 'label' => 'Huisnummer'])
  @include('form_text', ['field' => 'postal_code', 'label' => 'Postcode'])
  @include('form_text', ['field' => 'city', 'label' => 'Woonplaats'])

