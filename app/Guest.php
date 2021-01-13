<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Guest extends Model
{


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

    /**
     * Get the address record associated with the guest.
     */
    public function address()
    {
        return $this->hasOne('App\Address', 'uuid', 'address_id')->get();
    }
}
