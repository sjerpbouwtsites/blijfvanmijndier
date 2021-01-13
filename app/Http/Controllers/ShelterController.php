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
use App\MenuItem;

class ShelterController extends Controller
{

    function __construct()
    {
        parent::__construct('shelters');
    }

    public function index()
    {
        $shelters = Shelter::all();
        $shelters = $shelters->sortBy('name');

        $menuItems = $this->GetMenuItems('shelters');

        $data = array(
            'shelters' => $shelters,
            'menuItems' => $menuItems
        );

        return view("shelter.index")->with($data);
    }

    public function match($id)
    {
        $animal = Animal::find($id);
        $shelters = Shelter::all();
        $animal->breedDesc = $this->getDescription($animal->breed_id);

        $menuItems = $this->GetMenuItems('shelters');

        $data = array(
            'shelters' => $shelters,
            'animal' => $animal,
            'menuItems' => $menuItems
        );

        return view("shelter.match")->with($data);
    }

    public function show($id)
    {
        $shelter = Shelter::find($id);
        $animals  = Animal::where('shelter_id', $shelter->id)->get();
        $menuItems = $this->GetMenuItems('`shelters');

        foreach ($animals as $animal) {
            $animal->animalImage = $this->getAnimalImage($animal->id);
        }

        $updates = UpdateController::getUpdatesByLinkType('shelters', $shelter->id, 2);

        $data = array(
            'shelter' => $shelter,
            'animals' => $animals,
            'updates' => $updates,
            'menuItems' => $menuItems
        );

        return view("shelter.show")->with($data);
    }

    public function edit($id)
    {
        $shelter = Shelter::find($id);
        $data = $this->GetShelterData($shelter);

        return view("shelter.edit")->with($data);
    }

    public function create()
    {
        $shelter = new Shelter;
        $data = $this->GetShelterData($shelter);

        return view("shelter.edit")->with($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateshelter();

        if ($validator->fails()) {
            return Redirect::to('shelters/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveShelter($request);
            Session::flash('message', 'Pension succesvol toegevoegd!');
            return redirect()->action('ShelterController@index');
        }
    }

    public function update(Request $request)
    {
        $validator = $this->validateShelter();

        if ($validator->fails()) {
            return redirect()->action('ShelterController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveShelter($request);
            Session::flash('message', 'Pension succesvol gewijzigd!');
            return redirect()->action('ShelterController@show', $request->id);
        }
    }

    private function GetShelterData($shelter)
    {
        $menuItems = $this->GetMenuItems('shelters');

        $data = array(
            'shelter' => $shelter,
            'menuItems' => $menuItems
        );

        return $data;
    }

    private function validateShelter()
    {
        $rules = array(
            'name'     => 'required',
            'email_address' => 'email'
        );

        return Validator::make(Input::all(), $rules);
    }

    private function saveShelter(Request $request)
    {
        if ($request->id !== null) {
            $shelter = Shelter::find($request->id);
        } else {
            $shelter = new Shelter;
        }

        $shelter->name = $request->name;
        $shelter->street = $request->street;
        $shelter->house_number = $request->house_number;
        $shelter->postal_code = $request->postal_code;
        $shelter->city = $request->city;
        $shelter->phone_number = $request->phone_number;
        $shelter->email_address = $request->email_address;
        $shelter->website = $request->website;
        $shelter->contact_person = $request->contact_person;
        $shelter->remarks_contract = $request->remarks_contract;
        $shelter->remarks_general = $request->remarks_general;

        $shelter->save();
    }
}
