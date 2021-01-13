<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Address;

class LocationController extends AbstractController
{

    public $required = [
        'name',
        'phone_number',
        'email_address',
        'city',
        'house_number',
        'street',
        'postal_code'
    ];

    function __construct()
    {
        parent::__construct('locations');
    }

    /**
     * new single edit view & endpoint
     */
    public function create()
    {
        return $this->get_view("location.edit", [
            'location' => new Location,
        ]);
    }

    /**
     * finds location by id and hydrates the location.
     * @return Location
     * @param string id
     */
    public function get_hydrated(string $id): Location
    {
        $nude_location = Location::find($id);
        return Address::hydrateWithAddress($nude_location);
    }

    /**
     * creates new owner if request does not non-null id prop
     * references Model's own attributes to set request values to self
     * @return bool for success
     * @param Request request the incoming post according to laravel
     * @param string address_id the uuid of the related Address
     */
    public function create_or_save(Request $request, string $address_id): bool
    {
        $location = $this->get_model_instance($request, Location::class);
        foreach ($location['own_attributes'] as $key) {
            $location->$key = $request->$key;
        }
        $location->address_id = $address_id;
        $location->save();
        return true;
    }
}
