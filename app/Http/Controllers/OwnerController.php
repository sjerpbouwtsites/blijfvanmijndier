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


class OwnerController extends Controller
{

    protected $required = [
        'name',
        'phone_number',
        'email_address',
        'city',
        'house_number',
        'street',
        'postal_code'
    ];

    private $validator_rules = [];

    function __construct()
    {
        parent::__construct('owners');
        $this->set_validator_rules();
    }

    // view func
    public function index()
    {
        return $this->get_view("owner.index", [
            'owners' => Address::allWithAddress('App\Owner')->sortBy('name'),
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
     * finds owner by id and hydrates the owner.
     * @return Owner
     * @param string id
     */
    public function get_hydrated(string $id): Owner
    {
        $nude_owner = Owner::find($id);
        $owner = Address::hydrateWithAddress($nude_owner);
        return $owner;
    }


    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(), $this->validator_rules);

        if ($validator->fails()) {
            return Redirect::to('owners/create')
                ->withErrors($validator)
                ->withInput();
        }

        $ai = Address::save_or_create_address(true);
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
        $validator = Validator::make(Input::all(), $this->validator_rules);

        if ($validator->fails()) {
            return redirect()->action('OwnerController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        }

        $ai = Address::save_or_create_address(false);
        if ($this->create_or_save_owner($request, $ai)) {
            Session::flash('message', 'Eigenaar succesvol gewijzigd!');
            return redirect()->action('OwnerController@show', $request->id);
        } else {
            Session::flash('message', 'Dat is een fout!');
        }
    }




    /**
     * on init creates validator rules based on $this->required;
     */
    private function set_validator_rules(): void
    {
        foreach ($this->required as $r) {
            $this->validator_rules[$r] = 'required';
        }
    }

    /**
     * creates new owner if request does not non-null id prop
     * references Model's own attributes to set request values to self
     * @return bool for success
     * @param Request request the incoming post according to laravel
     * @param string address_id the uuid of the related Address
     */
    private function create_or_save_owner(Request $request, string $address_id): bool
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
