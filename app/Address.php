<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class Address extends Model
{

    public string $street;
    public string $house_number;
    public string $postal_code;
    public string $city;
    public string $longitude;
    public string $lattitude;
    public string $uuid;

    protected $table = 'addresses';

    public $timestamps = false;

    public $required = [
        'street', 'house_number', 'postal_code', 'city'
    ];

    public $fillable = [
        'street', 'house_number', 'postal_code', 'city', 'uuid'
    ];


    protected $visible = [
        'uuid', 'latitude', 'longtitude'
    ];

    public function guest()
    {
        return $this->belongsTo('App\Guest', 'address_id', 'uuid');
    }

    /**
     * stores required values from form directly on class in orde to be saved.
     */
    public function setNewValues($formInput)
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
     * goes by geoip service.
     */
    public function geoIpRoundTrip()
    {
        echo 'TO DO GEO IP ROUNDTRIP';
        return true;
    }

    /**
     * Checks if in sending a form, changes have been made to
     * inputs relevant to the Address.
     * @throws error if not all requireds are presents as keys in formInput.
     * @return bool. true if change or new.
     */
    public function address_new_or_changed($formInputs, $attributeList)
    {

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
