<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Location extends Model
{

    /**
     * Those properties not coming from the address model.
     */
    protected $own_attributes = [
        'name',
        'phone_number',
        'email_address'
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
