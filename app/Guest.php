<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Guest extends Model
{
    private $guestStatusDesc;
    private $tableGroupDesc;
    private $tableDesc;


    public static $accept_attributes_from_relations = [
        'street', 'postal_code', 'house_number', 'city', 'lattitude', 'longitude'
    ];

    public static $required_to_save = ['street', 'postal_code', 'house_number', 'name'];



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
        $no_address_found_ids = [];
        $no_address_found_names = [];

        $rg = $nakedGuests->map(function ($guest) {

            $address = $guest->address();
            $validatie = Address::validate_address_collection($address, 'guest');
            if ($validatie instanceof \Exception) {
                $no_address_found_ids[] = $guest->id;
                $no_address_found_names[] = $guest->name;
                return null;
            } else {
                $guest = Guest::combineAttributes($guest, $address);
            }
            return $guest;
        });
        if (count($no_address_found_ids) > 0) {
            echo $no_address_found_ids;
            //            Guest::destroy($no_address_found_ids);
            throw new \Exception('Er zijn gastgezinnen gevonden zonder bijpassen adres. Ze zijn hierom uit de database gehaald. Het betreft: ' . implode("; ", $no_address_found_names)) . ".";
        }
    }

    /**
     * Wrapper and 'hydrater' around find(). Locaties the Address, warns for arrors, combines the attributes on the guest.
     * @return guest with Address
     * @throws exception when !== 1 addresses are found.
     */
    public static function findWithAddress(string $guestId): Guest
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
