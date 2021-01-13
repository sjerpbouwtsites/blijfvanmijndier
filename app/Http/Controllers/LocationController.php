<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Location;
use App\MenuItem;

class LocationController extends Controller
{
    function __construct()
    {
        parent::__construct('locations');
    }

    public function index()
    {
        $locations = Location::all();
        $locations = $locations->sortBy('name');

        $menuItems = $this->GetMenuItems('locations');

        $data = array(
            'locations' => $locations,
            'menuItems' => $menuItems
        );

        return view("location.index")->with($data);
    }

    public function show($id)
    {
        $location = Location::find($id);
        $menuItems = $this->GetMenuItems('`locations');

        $data = array(
            'location' => $location,
            'menuItems' => $menuItems
        );

        return view("location.show")->with($data);
    }

    public function edit($id)
    {
        $location = Location::find($id);
        $data = $this->GetLocationData($location);

        return view("location.edit")->with($data);
    }

    public function create()
    {
        $location = new Location;
        $data = $this->GetLocationData($location);

        return view("location.edit")->with($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validatelocation();

        if ($validator->fails()) {
            return Redirect::to('locations/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveLocation($request);
            Session::flash('message', 'Opvanglocatie succesvol toegevoegd!');
            return redirect()->action('LocationController@index');
        }
    }

    public function update(Request $request)
    {
        $validator = $this->validateLocation();

        if ($validator->fails()) {
            return redirect()->action('LocationController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveLocation($request);
            Session::flash('message', 'Opvanglocatie succesvol gewijzigd!');
            return redirect()->action('LocationController@show', $request->id);
        }
    }

    private function GetLocationData($location)
    {
        $menuItems = $this->GetMenuItems('locations');

        $data = array(
            'location' => $location,
            'menuItems' => $menuItems
        );

        return $data;
    }

    private function validateLocation()
    {
        $rules = array(
            'name'     => 'required',
            'email_address' => 'email'
        );

        return Validator::make(Input::all(), $rules);
    }

    private function saveLocation(Request $request)
    {
        if ($request->id !== null) {
            $location = Location::find($request->id);
        } else {
            $location = new Location;
        }

        $location->name = $request->name;
        $location->street = $request->street;
        $location->house_number = $request->house_number;
        $location->postal_code = $request->postal_code;
        $location->city = $request->city;
        $location->phone_number = $request->phone_number;
        $location->email_address = $request->email_address;

        $location->save();
    }
}
