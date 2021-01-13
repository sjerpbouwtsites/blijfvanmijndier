<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Owner;
use App\Animal;
use App\Address;


class OwnerController extends AbstractController
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
        parent::__construct('owners');
    }

    /**
     * single match & endpoint
     */
    public function match($owner_id)
    {
        $animal = Animal::find($owner_id);
        $animal->breedDesc = $this->getDescription($animal->breed_id);

        return $this->get_view("owner.match", [
            'owners' => Address::allWithAddress("App\Owner")->sortBy('name'),
            'animal' => $animal,
        ]);
    }

    /**
     * single show view & endpoint
     */
    public function show($owner_id)
    {
        $owner = $this->get_hydrated($owner_id);
        $animals = Animal::setAnimalArrayImages(Animal::where('owner_id', $owner->id)->get());

        return $this->get_view("owner.show", [
            'owner' => $owner,
            'animals' => $animals,
        ]);
    }

    /**
     * new single edit view & endpoint
     */
    public function create()
    {
        return $this->get_view("owner.edit", [
            'owner' => new Owner,
        ]);
    }

    /**
     * finds owner by id and hydrates the owner.
     * @return Owner
     * @param string id
     */
    public function get_hydrated(string $owner_id): Owner
    {
        $nude_owner = Owner::find($owner_id);
        return Address::hydrateWithAddress($nude_owner);
    }

    /**
     * where is posted to on create
     */
    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(), $this->validator_rules);

        if ($validator->fails()) {
            return Redirect::to('owners/create')
                ->withErrors($validator)
                ->withInput();
        }

        $add_res = Address::save_or_create_address(true);
        if ($add_res['geo_res']['status'] !== 'success') {
            // error in curl / geo iq
            Session::flash('message', 'geolocatie faal: ' . $add_res['geo_res']['reason']);
            echo $add_res['geo_res']['return_html'];
            echo $add_res['geo_res']['console'];
            return $this->create();
        }

        $this->create_or_save($request, $add_res['address_id']);
        Session::flash('message', 'Succesvol toegevoegd!');
        return redirect()->action($this->model_name . 'Controller@index');
    }

    /**
     * where is posted to on update
     */
    public function update(Request $request)
    {
        $validator = Validator::make(Input::all(), $this->validator_rules);

        if ($validator->fails()) {
            return redirect()->action('OwnerController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        }

        $add_res = Address::save_or_create_address(false);
        if ($add_res['geo_res']['status'] !== 'success') {
            // error in curl / geo iq
            Session::flash('message', 'geolocatie faal: ' . $add_res['geo_res']['reason']);
            echo $add_res['geo_res']['return_html'];
            echo $add_res['geo_res']['console'];
            return $this->edit($request->id);
        }
        $this->create_or_save($request, $add_res['address_id']);
        Session::flash('message', 'Succesvol gewijzigd!');
        return redirect()->action($this->model_name . 'Controller@show', $request->id);
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
        $owner = $this->get_model_instance($request, Owner::class);
        foreach ($owner['own_attributes'] as $key) {
            $owner->$key = $request->$key;
        }
        $owner->address_id = $address_id;
        $owner->save();
        return true;
    }
}
