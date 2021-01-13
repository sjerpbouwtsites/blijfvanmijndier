<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Location;
use App\Address;

class LocationController extends Controller
{
    function __construct()
    {
        parent::__construct('locations');
    }

    /**
     * plenary view & root endpoint
     */
    public function index()
    {
        return $this->get_view("location.index", [
            'locations' => Address::allWithAddress('App\Location')->sortBy('name'),
        ]);
    }

    /**
     * single match & endpoint
     */
    public function show($location_id)
    {
        $location = $this->get_hydrated($location_id);

        return $this->get_view("location.show", [
            'location' => $location,
        ]);
    }

    /**
     * single show view & endpoint
     */
    public function edit($location_id)
    {
        return $this->get_view('location.edit', [
            'location' => $this->get_hydrated($location_id),
        ]);
    }

    /**
     * new single edit view & endpoint
     */
    // @TODO verplaats de index, show, edit, create functies naar utilClass.
    public function create()
    {
        return $this->get_view("location.edit", [
            'location' => new Location,
        ]);
    }

    /**
     * where is posted to on create
     */
    public function store(Request $request)
    {
        $validator = $this->validatelocation();

        if ($validator->fails()) {
            return Redirect::to('locations/create')
                ->withErrors($validator)
                ->withInput();
        }
        $ai = Address::save_or_create_address(true);
        $this->create_or_save_location($request, $ai);
        Session::flash('message', 'Opvanglocatie succesvol toegevoegd!');
        return redirect()->action('LocationController@index');
    }

    /**
     * where is posted to on update
     */
    public function update(Request $request)
    {
        $validator = $this->validateLocation();

        if ($validator->fails()) {
            return redirect()->action('LocationController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        }
        $ai = Address::save_or_create_address(true);
        $this->create_or_save_location($request, $ai);
        Session::flash('message', 'Opvanglocatie succesvol gewijzigd!');
        return redirect()->action('LocationController@show', $request->id);
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

    private function validateLocation()
    {
        $rules = array(
            'name'     => 'required',
            'email_address' => 'email'
        );

        return Validator::make(Input::all(), $rules);
    }

    /**
     * creates new owner if request does not non-null id prop
     * references Model's own attributes to set request values to self
     * @return bool for success
     * @param Request request the incoming post according to laravel
     * @param string address_id the uuid of the related Address
     */
    private function create_or_save_location(Request $request, string $address_id)
    {
        $location = $this->get_model_instance($request, Location::class);
        foreach ($location['own_attributes'] as $key) {
            $location->$key = $request->$key;
        }
        $location->address_id = $address_id;
        $location->save();
    }
}
