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
        'street', 'house_number', 'postal_code', 'city', 'lattitude', 'longitude', 'faulty_address'
    ];

    /**
     * to be written onto the 'primary' objects like Owner, Guest
     */
    public array $exported_keys = [
        'street', 'house_number', 'postal_code', 'city', 'lattitude', 'longitude', 'faulty_address'
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
     * used this' fillable array to push form input values as both attributes' keys and direct properties onto this. 
     */
    public function setNewValues(array $formInput): void
    {
        for ($i = 0; $i < count($this->fillable); $i++) {
            $key = $this->fillable[$i];
            if (!array_key_exists($key, $formInput)) {
                $this->$key = '';
                $this->attributes[$key] = '';                
                continue;
            }
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

        // if ($collection->count() > 1) {
        //     throw new \Exception('Meerdere adressen voor ' . $className . ' gevonden', E_USER_NOTICE);
        // }
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
    public static function save_or_create_address()
    {
        $postdata = Input::all();

        $Address = $postdata['address_id'] === '' ? new Address() : Address::find($postdata['address_id']);


        $Address->setNewValues($postdata);
        $Address->faulty_address = array_key_exists('faulty_address', $postdata)
            ?  $postdata['faulty_address'] === 'on' 
            ? 1 
            : 0 : 0;
        $ai = $Address->uuid_check($postdata);
     
        $Address->save();

        return [
            'address_id' => $ai
        ];

    }

    /**
     * This is rather messy. It smashed the Address relation onto the Owner.
     * Filter is no good. But hey. Sure beats having to read Laravels docs
     * @param string $model_name for PHP, models are also strings 😕
     * @param null|array array keys as property and accepted_value, assuming '=' operator
     */
    public static function allWithAddress($model_name, $where_clauses = null)
    {

        $naked = \is_null($where_clauses)
            ? $model_name::all()
            : $model_name::where($where_clauses['property'], $where_clauses['accepted_value'])->get();

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
