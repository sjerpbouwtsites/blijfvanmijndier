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
    public function geoIpRoundTrip(array $attributeList): void
    {

        $urldata = [
            str_replace(' ', '', $attributeList['postal_code']),
            $attributeList['street'],
            str_replace(' ', '', $attributeList['house_number'])
        ];


        $url = "https://eu1.locationiq.com/v1/search.php?key=b7a32fa378c135&q=" . urlencode(implode(' ', $urldata));


        $curl = new Curl();
        $curl->get($url, [
            "format" => "json"
        ]);

        if ($curl->error) {
            throw $curl->error;
        } elseif (count($curl->response) === 0) {
            throw new \Exception("Geen geolocatie gevonden voor dit adres");
        } elseif (count($curl->response) > 1) {
            // kan nog zijn dat hij ook een weg heeft gevonden.
            $iets_gevonden = array_filter($curl->response, function ($adres) {
                return in_array($adres->osm_type, ['way']);
            });
            if (count($iets_gevonden) !== 1) {
                dd($curl->response, false);
                throw new \Exception(count($curl->response) . " geolocatie registraties gevonden voor dit adres. Shit! \nKlopt het adres? Zo ja, contacteer de developer.\n Ik had al gekeken of er niet een weg meekwam, daarna waren er nog " . count($iets_gevonden) . " locaties gevonden.");
            } else {
                $adres = $iets_gevonden[0];
            }
        } else {
            $adres = $curl->response[0];
        }
        $this->attributes['longitude'] = $adres->lon;
        $this->attributes['lattitude'] = $adres->lat;
        $this->longitude = $adres->lon;
        $this->lattitude = $adres->lat;
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
     * @return string address_id
     * @param bool create: required. whether or not to save or create the address.
     */
    public static function save_or_create_address($create): string
    {
        if (!is_bool($create)) {
            throw new \Exception('save or create address without craate param');
        }
        $postdata = Input::all();
        $Address = $create ? new Address() : Address::find($postdata['address_id']);
        $Address->setNewValues($postdata);
        $ai = $Address->uuid_check($postdata);
        $Address->geoIpRoundTrip($postdata);
        $Address->save();
        return $ai;
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
