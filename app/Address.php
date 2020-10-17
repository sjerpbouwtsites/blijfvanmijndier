<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Address extends Model
{

    protected $table = 'addresses';

    public $timestamps = false;


    protected $fillable = [
        'street', 'house_number', 'postal_code', 'city'
    ];


    protected $visible = [
        'uuid', 'latitue', 'longtitude'
    ];

    public function guest()
    {
        return $this->belongsTo('App\Guest', 'address_id', 'uuid');
    }
}
