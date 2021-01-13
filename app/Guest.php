<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Guest extends Model
{
    private $guestStatusDesc;
    private $tableGroupDesc;
    private $tableDesc;

    /**
     * Those properties not coming from the address model.
     */
    protected $own_attributes = [
        'name',
        'phone_number',
        'email_address',
        'max_hours_alone',
        'text'
    ];

    public function tables()
    {
        return $this->belongsToMany(Table::class);
    }

    // public static function combineAttributes($guest, $newAttributesHolder)
    // {

    //     foreach (Guest::$accept_attributes_from_relations as $accepted) {
    //         $guest->attributes[$accepted] = $newAttributesHolder->all()[0]->attributes[$accepted];
    //     }
    //     return $guest;
    // }

    /**
     * This is rather messy. It smashed the Address relation onto the Guest.
     */

    // /**
    //  * Wrapper and 'hydrater' around find(). Locaties the Address, warns for arrors, combines the attributes on the guest.
    //  * @return guest with Address
    //  * @throws exception when !== 1 addresses are found.
    //  */
    // public static function findWithAddress(string $guestId): Guest
    // {
    //     $guest = Guest::find($guestId);
    //     $address = $guest->address();
    //     if ($address->count() !== 1) {
    //         throw new \Exception($address->count() . ' adressen gevonden voor guest ' . $guestId, E_USER_NOTICE);
    //         return $guest;
    //     }

    //     $guest = Guest::combineAttributes($guest, $address);

    //     return $guest;
    // }


    /**
     * Get the address record associated with the guest.
     */
    public function address()
    {
        return $this->hasOne('App\Address', 'uuid', 'address_id')->get();
    }
}
