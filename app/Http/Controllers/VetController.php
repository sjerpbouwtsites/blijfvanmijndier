<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Vet;
use App\Address;

class VetController extends Controller
{
    function __construct()
    {
        parent::__construct('vets');
    }

    /**
     * plenary view & root endpoint
     */
    public function index()
    {
        return $this->get_view("vet.index", [
            'vets' => Address::allWithAddress('App\Vet')->sortBy('name'),
        ]);
    }

    /**
     * single view & endpoint
     */
    public function show($id)
    {
        return $this->get_view("vet.show", [
            'vet' => $this->get_hydrated($id),
        ]);
    }

    /**
     * single edit view & endpoint
     */
    public function edit($id)
    {
        return $this->get_view("vet.edit", [
            'vet' => $this->get_hydrated($id),
        ]);
    }

    /**
     * single create view & endpoint
     */
    public function create()
    {
        return $this->get_view("vet.edit", [
            'vet' => new Vet,
        ]);
    }

    /**
     * where is posted to on create.
     */
    public function store(Request $request)
    {
        $validator = $this->validateVet();

        if ($validator->fails()) {
            return Redirect::to('vets/create')
                ->withErrors($validator)
                ->withInput();
        }
        $ai = Address::save_or_create_address(true);
        $this->create_or_save_vet($request, $ai);
        Session::flash('message', 'Dierenarts succesvol toegevoegd!');
        return redirect()->action('VetController@index');
    }

    /**
     * where is posted to on update
     */
    public function update(Request $request)
    {
        $validator = $this->validateVet();

        if ($validator->fails()) {
            return redirect()->action('VetController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        }
        $ai = Address::save_or_create_address(false);
        $this->create_or_save_vet($request, $ai);
        Session::flash('message', 'Dierenarts succesvol gewijzigd!');
        return redirect()->action('VetController@show', $request->id);
    }

    /**
     * finds vet by id and hydrates the vet.
     * @return Vet
     * @param string id
     */
    public function get_hydrated(string $id): Vet
    {
        $nude_vet = Vet::find($id);
        $vet = Address::hydrateWithAddress($nude_vet);
        return $vet;
    }

    private function validateVet()
    {
        $rules = array(
            'name'     => 'required'
        );

        return Validator::make(Input::all(), $rules);
    }

    private function create_or_save_vet(Request $request, string $address_id)
    {
        $vet = $this->get_model_instance($request, Vet::class);
        foreach ($vet['own_attributes'] as $key) {
            $vet->$key = $request->$key;
        }

        $vet->address_id = $address_id;
        $vet->save();
        return true;
    }
}
