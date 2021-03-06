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
}
