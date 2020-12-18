<?php



namespace App;

require __DIR__ . '/../vendor/autoload.php';

use Curl\Curl;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class Address extends Model
{

    private $street;
    private $house_number;
    private $postal_code;
    private $city;
    private $longitude;
    private $lattitude;
    private $uuid;

    /**
     * mag niet getyped zijn van eloquent 😣
     */
    protected $table = 'addresses';

    public $timestamps = false;

    public $required = [
        'street', 'house_number', 'postal_code', 'city'
    ];

    public $fillable = [
        'street', 'house_number', 'postal_code', 'city', 'uuid', 'lattitude', 'longitude'
    ];


    protected $visible = [
        'uuid', 'lattitude', 'longtitude'
    ];

    public function guest()
    {
        return $this->belongsTo('App\Guest', 'address_id', 'uuid');
    }

    /**
     * stores required values from form directly on class in orde to be saved.
     */
    public function setNewValues($formInput): void
    {
        for ($i = 0; $i < count($this->required); $i++) {
            $key = $this->required[$i];
            $this->$key = $formInput[$key];
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
            return $attributesList['address_id'];
        }

        $uuid = preg_replace('/\W/', '-', uniqid('', true)) . '-' . preg_replace('/\W/', '-', uniqid('', true));
        $this->uuid = $uuid;
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
            throw new \Exception(count($curl->response) . " geolocatie registraties gevonden voor dit adres. Klopt het adres? Zo ja, contacteer de developer");
        } else {
            $this->longitude = $curl->response[0]->lon;
            $this->lattitude = $curl->response[0]->lat;
        }
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
}
