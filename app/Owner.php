<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Address;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class Owner extends Model
{

    /**
     * Those properties not coming from the address model.
     */
    protected $own_attributes = ['name', 'prefix', 'surname', 'phone_number', 'email_address'];

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
     * Get the address record collection associated with the owner.
     */
    public function address(): Collection
    {
        $a = $this->hasOne('App\Address', 'uuid', 'address_id')->get();
        Address::validate_address_collection($a, 'owner');
        return $a;
    }

    /**
     * // @TODO naar address model zit ook in guest
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
     * hydrates model with address data. 
     */
    public static function hydrateWithAddress(Owner $nude): Owner
    {

        $addresses_collection = $nude->address();
        // write the address data onto the object
        $first_address = $addresses_collection->first();
        return $first_address->hydrate_model($nude);
    }

    /**
     * ONDUIDELIJK OF DEZE NOG GEBRUIKT WORDT!
     * Wrapper and 'hydrater' around find(). Locaties the Address, warns for arrors, combines the attributes on the guest.
     * @return Owner with Address
     * @deprecated
     * @throws exception when !== 1 addresses are found.
     */
    public static function findWithAddress(string $ownerId): Owner
    {
        $owner = Owner::find($ownerId);
        $address = $owner->address();
        $owner = Owner::combineAttributes($owner, $address);
        return $owner;
    }
}
