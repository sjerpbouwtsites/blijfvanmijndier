<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    private $guestStatusDesc;
    private $tableGroupDesc;
    private $tableDesc;


    public static $accept_attributes_from_relations = [
        'street', 'postal_code', 'house_number', 'city', 'latitude', 'longitude'
    ];



    public function tables()
    {
        return $this->belongsToMany(Table::class);
    }

    public static function combineAttributes($guest, $newAttributesHolder)
    {
        foreach (Guest::$accept_attributes_from_relations as $accepted) {
            $guest->attributes[$accepted] = $newAttributesHolder->all()[0]->attributes[$accepted];
        }
        return $guest;
    }

    /**
     * This is rather messy. It smashed the Address relation onto the Guest.
     */
    public static function allWithAddress()
    {
        $nakedGuests = Guest::all();

        return  $nakedGuests->map(function ($guest) {

            $address = $guest->address();
            if ($address->count() > 1) {
                throw new \Exception('Meedere adressen voor guest gevonden', E_USER_NOTICE);
            }
            if ($address->count() < 0) {
                throw new \Exception('Geen adres voor guest', E_USER_NOTICE);
                return null;
            }

            $guest = Guest::combineAttributes($guest, $address);

            return $guest;
        });
    }

    public static function findWithAddress(string $guestId)
    {
        $guest = Guest::find($guestId);
        $address = $guest->address();
        if ($address->count() !== 1) {
            throw new \Exception($address->count() . ' adressen gevonden voor guest ' . $guestId, E_USER_NOTICE);
            return $guest;
        }

        $guest = Guest::combineAttributes($guest, $address);

        return $guest;
    }


    /**
     * Get the address record associated with the guest.
     */
    public function address()
    {
        return $this->hasOne('App\Address', 'uuid', 'address_id')->get();
    }
}
