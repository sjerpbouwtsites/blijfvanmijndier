<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Shelter;
use App\Animal;
use App\Address;

class ShelterController extends AbstractController
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
        parent::__construct('shelters');
    }

    /**
     * single match & endpoint
     */
    public function match($shelter_id)
    {
        $animal = Animal::find($shelter_id);
        $animal->breedDesc = $this->getDescription($animal->breed_id);
        $shelters = Address::allWithAddress("App\Shelter")->sortBy('name');

        return $this->get_view("shelter.match", [
            'shelters' => $shelters,
            'animal' => $animal,
        ]);
    }

    /**
     * overridden because laravel cant find Shelter in AbstractController.
     * single create view & endpoint
     */
    public function create()
    {
        return $this->get_view("shelter.edit", [
            'shelter' => new Shelter,
        ]);
    }

    /**
     * single show view & endpoint
     */
    public function show($shelter_id)
    {
        $shelter = $this->get_hydrated($shelter_id);
        $animals  = Animal::setAnimalArrayImages(Animal::where('shelter_id', $shelter->id)->get());
        $updates = UpdateController::getUpdatesByLinkType('shelters', $shelter->id, 2);

        return $this->get_view("shelter.show", [
            'shelter' => $shelter,
            'animals' => $animals,
            'updates' => $updates,
        ]);
    }

    /**
     * single edit view & endpoint
     */
    public function edit($shelter_id)
    {
        return $this->get_view('shelter.edit', [
            'shelter' => $this->get_hydrated($shelter_id),
        ]);
    }





    /**
     * finds shelter by id and hydrates the shelter.
     * @return Shelter width addresses.
     * @param string id
     */
    public function get_hydrated(string $shelter_id): Shelter
    {
        $nude_shelter = Shelter::find($shelter_id);
        return Address::hydrateWithAddress($nude_shelter);
    }


    /**
     * creates new shelter if request does not non-null id prop
     * references Model's own attributes to set request values to self
     * @return bool for success
     * @param Request request the incoming post according to laravel
     * @param string address_id the uuid of the related Address
     */
    public function create_or_save(Request $request, string $address_id): bool
    {
        $shelter = $this->get_model_instance($request, Shelter::class);
        foreach ($shelter['own_attributes'] as $key) {
            $shelter->$key = $request->$key;
        }
        $shelter->address_id = $address_id;
        $shelter->save();
        return true;
    }
}
