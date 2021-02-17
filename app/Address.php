<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use Curl\Curl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Input;

class Address extends Model
{

    protected $table = 'addresses';

    protected $primaryKey = 'uuid';

    public $timestamps = false;

    public $required = [
        'street', 'house_number', 'postal_code', 'city'
    ];

    public $fillable = [
        'street', 'house_number', 'postal_code', 'city', 'uuid', 'lattitude', 'longitude'
    ];

    /**
     * to be written onto the 'primary' objects like Owner, Guest
     */
    public array $exported_keys = [
        'street', 'house_number', 'postal_code', 'city', 'lattitude', 'longitude'
    ];


    protected $visible = [
        'uuid', 'lattitude', 'longtitude'
    ];

    public function owner()
    {
        return $this->belongsTo('App\Owner', 'address_id', 'uuid');
    }

    public function guest()
    {
        return $this->belongsTo('App\Guest', 'address_id', 'uuid');
    }

    /**
     * used this' required array to push form input values as both attributes' keys and direct properties onto this. 
     */
    public function setNewValues(array $formInput): void
    {
        for ($i = 0; $i < count($this->required); $i++) {
            $key = $this->required[$i];
            $this->$key = $formInput[$key];
            $this->attributes[$key] = $formInput[$key];
        }
    }

    /**
     * if an uuid is present, return it, otherwise, create it. 
     * @return uuid
     */
    public function uuid_check($attributesList)
    {
        if (
            array_key_exists('address_id', $attributesList)
            && !empty($attributesList['address_id'])
        ) {
            $this->uuid = $attributesList['address_id'];
            $this->attributes['uuid'] = $attributesList['address_id'];
            return $attributesList['address_id'];
        }

        $uuid = preg_replace('/\W/', '-', uniqid('', true)) . '-' . preg_replace('/\W/', '-', uniqid('', true));
        $this->uuid = $uuid;
        $this->attributes['uuid'] = $uuid;
        return $uuid;
    }

    /**
     * goes by geoip service. writes to class properties.
     * @throws when curl error, geo ip registrations found !== 1. 
     */
    public function geoIpRoundTrip(array $attributeList): array
    {

        if (array_key_exists('manual_geolocation', $attributeList)  && $attributeList['manual_geolocation']) {
            $this->longitude = $this->attributes['longitude'];
            $this->lattitude = $this->attributes['lattitude'];
            return [
                'status' => 'success'
            ];
        }


        $city = str_replace(' ', '', $attributeList['city']);
        $street = str_replace(' ', '', $attributeList['street']);
        $house_number = str_replace(' ', '', $attributeList['house_number']);

        $geo_query = "Netherlands $city $street $house_number";

        $url = "https://eu1.locationiq.com/v1/search.php?key=b7a32fa378c135&q=" . urlencode($geo_query) . "&limit=1";

        $adres_back_html = $this->address_error_back_HTML($geo_query);

        $curl = new Curl();
        $curl->get($url, [
            "format" => "json"
        ]);

        $curl_console = `
            <script>console.dir(` . $curl->rawResponse . `);</script>
            `;

        $basis_ret = [
            'status' => null,
            'console' => $curl_console,
            'reason' => null,
            'return_html' => $adres_back_html
        ];

        $status = 'success'; // assume eh;

        // error in curl zelf.
        if ($curl->error !== FALSE) {
            $basis_ret['reason'] = 'curl error: ' . $curl->errorMessage;
            $status = 'fail';
        }
        $curl->close();

        // no response.
        if ($status !== 'fail') {
            if (!property_exists($curl, 'response') || is_null($curl->response)) {
                $basis_ret['reason'] = "curl response does not exist or is null";
                $status = 'fail';
            }
        }

        // geoIq error: response has error. response error is protected btw
        if ($status !== 'fail' && is_array($curl->response)) {
            if (!empty($curl->response['error'])) {
                $basis_ret['reason'] = "error in geoIq system: $curl->response['error']";
                $status = 'fail';
            }
        }


        // de reactie is geen array?
        if ($status !== 'fail') {
            if (!is_array($curl->response)) {
                $basis_ret['reason'] = "geo iq response is not an array.";
                $status = 'fail';
            }
        }

        // alweer niets gevonden door geo iq?

        $response = $curl->response;
        if ($status !== 'fail') {
            if (empty($response)) {
                $basis_ret['reason'] = "geo iq did not find anything.";
                $status = 'fail';
            }
        }

        if ($status !== 'fail') {
            if (!property_exists($response[0], 'lat') || !property_exists($response[0], 'lon')) {
                $basis_ret['reason'] = 'lat and or lon not set on response[0].';
                $status = 'fail';
            }
        }

        // we laten het hierbij!
        $basis_ret['status'] = $status;

        if ($status === 'fail') {
            return $basis_ret;
        }

        // what the whole sham was about
        $this->longitude = $this->attributes['longitude'] = $response[0]->lon;
        $this->lattitude = $this->attributes['lattitude'] = $response[0]->lat;

        return [
            'status' => 'success'
        ];
    }

    private function address_error_back_HTML($geo_query)
    {
        return `
            <h1>Er heeft zich een fout in de adresopzoeking voorgedaan.</h1>
            <p>We zochten met deze info: $geo_query</p>
            <p>Als dit niet klopt <button onclick="history.back()">klik dan hier</button></p>
        `;
    }

    /**
     * Checks if in sending a form, changes have been made to
     * inputs relevant to the Address.
     * @throws error if not all requireds are presents as keys in formInput.
     * @return bool. true if change or new.
     */
    public function address_new_or_changed($formInputs, $attributeList): bool
    {

        if (!$attributeList || count($attributeList) === 0) {
            return true;
        }

        $verandering = false;
        for ($i = 0; $i < count($this->required); $i++) {
            $key = $this->required[$i];
            if (!array_key_exists($key, $formInputs)) {
                throw new \Exception($key . ' not present in form inputs', E_USER_ERROR);
            }
            if ($formInputs[$key] !== $attributeList[$key]) {
                $verandering = true;
            }
        }
        return $verandering;
    }

    public static function validate_address_collection(Collection $collection, string $className): bool
    {

        if ($collection->count() > 1) {
            throw new \Exception('Meerdere adressen voor ' . $className . ' gevonden', E_USER_NOTICE);
        }
        if ($collection->count() < 1) {
            throw new \Exception('Geen bijpassend adres voor ' . $className, E_USER_NOTICE);
        }

        return true;
    }

    /**
     * adds current address attributes to the given model and returns.
     * uses address->exported_keys for this list.
     * @throws Error when model empty, does not have attributes
     * @return Model 
     * @param Model
     * @param string modelName
     */
    public function hydrate_model(Model $model, string $modelName = null): Model
    {

        $mn = empty($modelName) ? 'geen modelnaam meegegeven' : $modelName;

        if (empty($model) || !property_exists($model, 'attributes')) {
            echo "<pre>";
            var_dump($model);
            echo "<pre>";
            throw new \Exception('model ' . $mn . ' leeg of heeft geen attributes');
        }

        try {
            $transplant_address = $this['attributes'];
            foreach ($this->exported_keys as $address_key) {
                $model->$address_key = $transplant_address[$address_key];
            }
        } catch (\Error $error) {
            echo "fout bij overzetten adresgegevens naar $mn " . $model->id . "<br>";
            echo $error . "<br>";
            throw $error;
        }

        return $model;
    }

    /**
     * Wrapper around saving the address. 
     * calls address model methodes
     * @return array save address array with address_id and geo iq res
     * @param bool create: required. whether or not to save or create the address.
     */
    public static function save_or_create_address($create): array
    {
        if (!is_bool($create)) {
            throw new \Exception('save or create address without craate param');
        }
        $postdata = Input::all();
        $Address = $create ? new Address() : Address::find($postdata['address_id']);
        $Address->setNewValues($postdata);
        $ai = $Address->uuid_check($postdata);
        $geo_res = $Address->geoIpRoundTrip($postdata);
        if ($geo_res['status'] === 'success') {
            $Address->save();
        }
        return [
            'geo_res' => $geo_res,
            'address_id' => $ai
        ];
    }

    /**
     * This is rather messy. It smashed the Address relation onto the Owner.
     * @param string $model_name for PHP, models are also strings 😕
     */
    public static function allWithAddress($model_name)
    {

        $naked = $model_name::all();
        return $naked->map(function ($nude) {
            return Address::hydrateWithAddress($nude);
        });
    }

    /**
     * hydrates model with address data. 
     */
    public static function hydrateWithAddress(Model $nude): Model
    {
        $addresses_collection = $nude->address();
        // write the address data onto the object
        $first_address = $addresses_collection->first();
        return $first_address->hydrate_model($nude);
    }
}
