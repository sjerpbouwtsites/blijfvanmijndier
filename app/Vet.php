<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

// @TODO create abstract class to extend from
// that implements own_attributes and address()
// also use in owner etc. 
class Vet extends Model
{
    protected $own_attributes = [
        'name', 'phone_number', 'email_address', 'website', 'contact_person', 'remarks_contract', 'remarks_general'
    ];

    public function tables()
    {
        return $this->belongsToMany(Table::class);
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
}
