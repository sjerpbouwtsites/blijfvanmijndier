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
use App\MenuItem;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::all();
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
        $owner = Owner::find($id);
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
        } else {
            $this->saveOwner($request);
            Session::flash('message', 'Eigenaar succesvol toegevoegd!');
            return redirect()->action('OwnerController@index');
        }
    }

    public function update(Request $request)
    {
        $validator = $this->validateOwner();

        if ($validator->fails()) {
            return redirect()->action('OwnerController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveOwner($request);
            Session::flash('message', 'Eigenaar succesvol gewijzigd!');
            return redirect()->action('OwnerController@show', $request->id);
        }
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

    private function saveOwner(Request $request)
    {
        if ($request->id !== null) {
            $owner = Owner::find($request->id);
        } else {
            $owner = new Owner;
        }

        $owner->name = $request->name;
        $owner->prefix = $request->prefix;
        $owner->surname = $request->surname;
        // $owner->street = $request->street;
        // $owner->house_number = $request->house_number;
        // $owner->postal_code = $request->postal_code;
        // $owner->city = $request->city;
        $owner->phone_number = $request->phone_number;
        $owner->email_address = $request->email_address;

        $owner->save();
    }
}
