<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\TryCatch;

class Owner extends Model
{

    public $fillable = [
        'street', 'house_number', 'postal_code', 'city', 'lattitude', 'longitude', 'address_id'
    ];

    public function tables()
    {
        return $this->belongsToMany(Table::class);
    }

    public function contact()
    {
        return $this->hasOne(Contact::class);
    }

    /**
     * Get the address record associated with the owner.
     */
    public function address()
    {
        return $this->hasOne('App\Address', 'uuid', 'address_id')->get();
    }


    static function dump($v, $die = false)
    {
        echo "<pre>";
        var_dump($v);
        echo "</pre>";
        if ($die) {
            die();
        }
    }

    /**
     * This is rather messy. It smashed the Address relation onto the Owner.
     */
    public static function allWithAddress()
    {
        $naked = Owner::all();

        return $naked->map(function ($nude) {
            return Owner::hydrateWithAddress($nude);
        });
    }

    /**
     * Wrapper and 'hydrater' around find(). Locaties the Address, warns for arrors, combines the attributes on the guest.
     * @return Owner with Address
     * @throws exception when !== 1 addresses are found.
     */
    public static function findWithAddress(string $ownerId): Owner
    {
        $owner = Owner::find($ownerId);
        $address = $owner->address();
        if ($address->count() !== 1) {
            throw new \Exception($address->count() . ' adressen gevonden voor owner ' . $ownerId, E_USER_NOTICE);
            return $owner;
        }

        $owner = Owner::combineAttributes($owner, $address);

        return $owner;
    }

    // mapper of allWithAddress and single access
    public static function hydrateWithAddress($nude)
    {

        $addresses_query = $nude->address();
        if ($addresses_query->count() > 1) {
            throw new \Exception('Meerdere adressen voor owner gevonden', E_USER_NOTICE);
        }
        if ($addresses_query->count() < 0) {
            throw new \Exception('Geen bijpassend adres voor owner', E_USER_NOTICE);
            return null;
        }

        $keys = [
            'street', 'house_number', 'postal_code', 'city', 'lattitude', 'longitude'
        ];
        if (empty($addresses_query->first())) {
            foreach ($keys as $address_key) {
                $nude->$address_key = 'Vergeten adres veld';
            }
            return $nude;
        }

        try {
            $transplant_address = $addresses_query->first()['attributes'];
            foreach ($keys as $address_key) {
                $nude->$address_key = $transplant_address[$address_key];
            }
        } catch (\Error $error) {

            echo "fout bij overzetten adresgegevens naar owner " . $nude->id . "<br>";
            echo $error . "<br>";
        }

        return $nude;
    }
}
