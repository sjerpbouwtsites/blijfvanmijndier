<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    private $guestStatusDesc;
    private $tableGroupDesc;
    private $tableDesc;


    public static $accept_attributes_from_relations = [
        'street', 'postal_code', 'house_number', 'city'
    ];



    public function tables()
    {
        return $this->belongsToMany(Table::class);
    }

    /**
     * This is rather messy. It smashed the Address relation onto the Guest.
     */
    public static function allWithAddress()
    {
        $nakedGuests = Guest::all();

        return  $nakedGuests->map(function ($guest) {

            $addressCollection = $guest->address();
            if ($addressCollection->count() > 1) {
                throw new \Exception('Meedere adressen voor guest gevonden', E_USER_NOTICE);
            }
            if ($addressCollection->count() < 0) {
                throw new \Exception('Geen adres voor guest', E_USER_NOTICE);
                return null;
            }

            foreach (Guest::$accept_attributes_from_relations as $accepted) {
                $guest->attributes[$accepted] = $addressCollection->all()[0]->attributes[$accepted];
            }

            return $guest;
        });
    }


    /**
     * Get the address record associated with the guest.
     */
    public function address()
    {
        return $this->hasOne('App\Address', 'uuid', 'address_id')->get();
    }
}
