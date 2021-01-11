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
use \Illuminate\View\View;

class OwnerController extends Controller
{

    public $menuItems = null;

    function __construct()
    {
        $this->menuItems = $this->GetMenuItems('owners');
    }

    // view func
    public function index()
    {
        return $this->get_view("owner.index", [
            'owners' => Owner::allWithAddress()->sortBy('name'),
        ]);
    }

    // view func    
    public function match($id)
    {
        $animal = Animal::find($id);
        $animal->breedDesc = $this->getDescription($animal->breed_id);

        return $this->get_view("owner.match", [
            'owners' => Owner::all(),
            'animal' => $animal,
        ]);
    }

    // view func    
    public function show($id)
    {
        $owner = $this->get_hydrated($id);
        $animals  = Animal::where('owner_id', $owner->id)->get();

        foreach ($animals as $animal) {
            $animal->animalImage = $this->getAnimalImage($animal->id);
        }

        return $this->get_view("owner.show", [
            'owner' => $owner,
            'animals' => $animals,
        ]);
    }

    // view func
    public function edit($id)
    {
        return $this->get_view('owner.edit', [
            'owner' => $this->get_hydrated($id),
        ]);
    }

    // view func
    public function create()
    {
        return $this->get_view("owner.edit", [
            'owner' => new Owner,
        ]);
    }

    /**
     * wrapper around view and -> with
     * so no every time include menuItems and shortening
     * @param string name of the view
     * @param array to be loaded into view besides menuItems
     * @return loaded views.
     */
    private function get_view(string $view_name, array $data): View
    {
        return view($view_name)->with(array_merge($data, [
            'menuItems' => $this->menuItems
        ]));
    }

    /**
     * finds owner by id and hydrates the owner.
     * @return Owner
     * @param string id
     */
    public function get_hydrated(string $id): Owner
    {
        $nude_owner = Owner::find($id);
        $owner = Owner::hydrateWithAddress($nude_owner);
        return $owner;
    }


    public function store(Request $request)
    {
        $validator = $this->validateOwner();

        if ($validator->fails()) {
            return Redirect::to('owners/create')
                ->withErrors($validator)
                ->withInput();
        }

        $ai = $this->save_or_create_address(true);
        if ($this->create_or_save_owner($request, $ai)) {
            Session::flash('message', 'Eigenaar succesvol toegevoegd!');
            return redirect()->action('OwnerController@index');
        } else {
            Session::flash('message', 'Fout bij het opslaan!');
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
        }

        $ai = $this->save_or_create_address(false);
        if ($this->create_or_save_owner($request, $ai)) {
            Session::flash('message', 'Eigenaar succesvol gewijzigd!');
            return redirect()->action('OwnerController@show', $request->id);
        } else {
            Session::flash('message', 'Dat is een fout!');
        }
    }

    /**
     * Wrapper around saving the address. 
     * calls address model methodes
     * @return string address_id
     * @param bool create: required. whether or not to save or create the address.
     */
    private function save_or_create_address($create): string
    {
        if (!is_bool($create)) {
            throw new \Exception('save or create address without craate param');
        }
        $postdata = Input::all();
        $Address = $create ? new Address() : Address::find($postdata['address_id']);
        $Address->setNewValues($postdata);
        $ai = $Address->uuid_check($postdata);
        $Address->geoIpRoundTrip($postdata);
        $Address->save();
        return $ai;
    }

    private function validateOwner()
    {
        $rules = array(
            'name'          => 'required',
            'phone_number'  => 'required',
            'email_address' => 'required',
            'city' => 'required',
            'house_number' => 'required',
            'street' => 'required',
            'postal_code' => 'required',
        );

        return Validator::make(Input::all(), $rules);
    }

    private function create_or_save_owner(Request $request, $address_id): bool
    {
        // bestaat de owner al?
        $owner = $request->id !== null
            ? Owner::find($request->id)
            : new Owner;

        foreach (['name', 'prefix', 'surname', 'phone_number', 'email_address'] as $key) {
            $owner->$key = $request->$key;
        }
        $owner->address_id = $address_id;
        $owner->save();
        return true;
    }
}
