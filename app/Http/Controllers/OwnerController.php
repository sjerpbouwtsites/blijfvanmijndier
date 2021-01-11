<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Owner;
use App\Animal;
use App\Address;
use App\MenuItem;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::allWithAddress();
        $owners = $owners->sortBy('name');

        $menuItems = $this->GetMenuItems('owners');

        $data = array(
            'owners' => $owners,
            'menuItems' => $menuItems
        );

        return view("owner.index")->with($data);
    }

    public function match($id)
    {
        $animal = Animal::find($id);
        $owners = Owner::all();
        $animal->breedDesc = $this->getDescription($animal->breed_id);

        $menuItems = $this->GetMenuItems('owners');

        $data = array(
            'owners' => $owners,
            'animal' => $animal,
            'menuItems' => $menuItems
        );

        return view("owner.match")->with($data);
    }

    public function show($id)
    {
        $owner = Owner::find($id);
        $animals  = Animal::where('owner_id', $owner->id)->get();
        $menuItems = $this->GetMenuItems('owners');

        foreach ($animals as $animal) {
            $animal->animalImage = $this->getAnimalImage($animal->id);
        }

        $data = array(
            'owner' => $owner,
            'animals' => $animals,
            'menuItems' => $menuItems
        );

        return view("owner.show")->with($data);
    }

    public function edit($id)
    {
        $nude_owner = Owner::find($id);
        $owner = Owner::hydrateWithAddress($nude_owner);
        $data = $this->GetOwnerData($owner);

        return view("owner.edit")->with($data);
    }

    public function create()
    {
        $owner = new Owner;
        $data = $this->GetOwnerData($owner);

        return view("owner.edit")->with($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateOwner();

        if ($validator->fails()) {
            return Redirect::to('owners/create')
                ->withErrors($validator)
                ->withInput();
        }

        $postdata = Input::all();
        $Address = new Address();
        $Address->setNewValues($postdata);
        $ai = $Address->uuid_check($postdata);
        $Address->geoIpRoundTrip($postdata);
        $Address->save();
        $this->saveOwner($request, $ai);
        Session::flash('message', 'Eigenaar succesvol toegevoegd!');
        return redirect()->action('OwnerController@index');
    }

    public function update(Request $request)
    {
        $validator = $this->validateOwner();

        if ($validator->fails()) {
            return redirect()->action('OwnerController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        }

        try {

            $postdata = Input::all();
            $Address = Address::find($postdata['address_id']);
            $Address->setNewValues($postdata);
            $ai = $Address->uuid_check($postdata); // dit is een beetje rommelig
            $Address->geoIpRoundTrip($postdata);
            $Address->save();
            $this->saveOwner($request, $ai);
            Session::flash('message', 'Eigenaar succesvol gewijzigd!');
        } catch (\Exception $error) {
            echo "error met ...";
            dd($postdata);
            throw $error;
        }
        return redirect()->action('OwnerController@show', $request->id);
    }

    private function GetOwnerData($owner)
    {
        $menuItems = $this->GetMenuItems('owners');

        $data = array(
            'owner' => $owner,
            'menuItems' => $menuItems
        );

        return $data;
    }

    private function validateOwner()
    {
        $rules = array(
            'name'          => 'required',
            'phone_number'  => 'required',
            'email_address' => 'required',
        );

        return Validator::make(Input::all(), $rules);
    }

    private function saveOwner(Request $request, $address_id)
    {
        if ($request->id !== null) {
            $owner = Owner::find($request->id);
        } else {
            $owner = new Owner;
        }

        $owner->name = $request->name;
        $owner->prefix = $request->prefix;
        $owner->surname = $request->surname;
        $owner->address_id = $address_id;
        // $owner->street = $request->street;
        // $owner->house_number = $request->house_number;
        // $owner->postal_code = $request->postal_code;
        // $owner->city = $request->city;
        $owner->phone_number = $request->phone_number;
        $owner->email_address = $request->email_address;

        $owner->save();
    }
}
