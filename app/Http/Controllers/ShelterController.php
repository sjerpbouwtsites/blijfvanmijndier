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

class ShelterController extends Controller
{

    function __construct()
    {
        parent::__construct('shelters');
    }

    /**
     * plenary view & root endpoint
     */
    public function index()
    {
        return $this->get_view("shelter.index", [
            'shelters' => Address::allWithAddress('App\Shelter')->sortBy('name'),
        ]);
    }

    /**
     * single match & endpoint
     */
    public function match($shelter_id)
    {
        $animal = Animal::find($shelter_id);
        $animal->breedDesc = $this->getDescription($animal->breed_id);

        return $this->get_view("shelters.match", [
            'shelters' => Shelter::all(),
            'animal' => $animal,
        ]);
    }

    /**
     * single show view & endpoint
     */
    public function show($shelter_id)
    {
        $shelter = $this->get_hydrated($shelter_id);
        $animals  = Animal::where('shelter_id', $shelter->id)->get();

        foreach ($animals as $animal) {
            $animal->animalImage = $this->getAnimalImage($animal->id);
        }

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
     * single create view & endpoint
     */
    public function create($shelter_id)
    {
        return $this->get_view('shelter.edit', [
            'shelter' => new Shelter,
        ]);
    }

    /**
     * where is posted to on create
     */
    public function store(Request $request)
    {
        $validator = $this->validateshelter();

        if ($validator->fails()) {
            return Redirect::to('shelters/create')
                ->withErrors($validator)
                ->withInput();
        }
        $ai = Address::save_or_create_address(true);
        if ($this->create_or_save_shelter($request, $ai)) {
            Session::flash('message', 'Opvang succesvol toegevoegd!');
            return redirect()->action('ShelterController@index');
        } else {
            Session::flash('message', 'Fout bij het opslaan!');
            return redirect()->action('ShelterController@index');
        }
    }

    /**
     * where is posted to on update
     */
    public function update(Request $request)
    {
        $validator = $this->validateShelter();

        if ($validator->fails()) {
            return redirect()->action('ShelterController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        }
        $ai = Address::save_or_create_address(false);
        if ($this->create_or_save_shelter($request, $ai)) {
            Session::flash('message', 'Opvang succesvol gewijzigd!');
            return redirect()->action('ShelterController@show', $request->id);
        } else {
            Session::flash('message', 'Dat is een fout!');
            return redirect()->action('ShelterController@show', $request->id);
        }
    }

    private function validateShelter()
    {
        $rules = array(
            'name'     => 'required',
            'email_address' => 'email'
        );

        return Validator::make(Input::all(), $rules);
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
    private function create_or_save_shelter(Request $request, string $address_id): bool
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
