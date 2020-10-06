<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Vet;
use App\MenuItem;

class VetController extends Controller
{
    public function index(){
    	$vets = Vet::all();
        $vets = $vets->sortBy('name');          
        
        $menuItems = $this->GetMenuItems('vets');

        $data = array(
            'vets' => $vets,
            'menuItems' => $menuItems
        );

    	return view("vet.index")->with($data);
    }

    public function show($id){
    	$vet = Vet::find($id);
        $menuItems = $this->GetMenuItems('`vets');

        $data = array(
            'vet' => $vet,
            'menuItems' => $menuItems
        );

    	return view("vet.show")->with($data);
    }

    public function edit($id){
        $vet = Vet::find($id);
        $data = $this->GetVetData($vet);

        return view("vet.edit")->with($data);
    }

    public function create(){
        $vet = new Vet;
        $data = $this->GetVetData($vet);

        return view("vet.edit")->with($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateVet();

        if ($validator->fails()) {
            return Redirect::to('vets/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveVet($request);
            Session::flash('message', 'Dierenarts succesvol toegevoegd!');
            return redirect()->action('VetController@index');
        }
    }

    public function update(Request $request)
    {
        $validator = $this->validateVet();

        if ($validator->fails()) {
            return redirect()->action('VetController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveVet($request);
            Session::flash('message', 'Dierenarts succesvol gewijzigd!');
            return redirect()->action('VetController@show', $request->id);
        }
    }

    private function GetVetData($vet){
        $menuItems = $this->GetMenuItems('vets');

        $data = array(
            'vet' => $vet, 
            'menuItems' => $menuItems
        );

        return $data;
    }

    private function validateVet(){
        $rules = array(
            'name'     => 'required'
        );
        
        return Validator::make(Input::all(), $rules);
    }

    private function saveVet(Request $request){
        if($request->id !== null){
            $vet = Vet::find($request->id);
        }else{
            $vet = new Vet;
        }

        $vet->name = $request->name;
        $vet->street = $request->street;
        $vet->house_number = $request->house_number;
        $vet->postal_code = $request->postal_code;
        $vet->city = $request->city;
        $vet->phone_number = $request->phone_number;
        $vet->email_address = $request->email_address;
        $vet->website = $request->website;
        $vet->contact_person = $request->contact_person;
        $vet->remarks_contract = $request->remarks_contract;
        $vet->remarks_general = $request->remarks_general;


        $vet->save();
    }
}