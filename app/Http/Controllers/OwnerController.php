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
    // @TODO doorvoeren in alle relevante models. required combo met validator
    // @TODO required attr op inputs zetten als relevant
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

    /**
     * plenary view & root endpoint
     */
    public function index()
    {
        return $this->get_view("owner.index", [
            'owners' => Address::allWithAddress('App\Owner')->sortBy('name'),
        ]);
    }

    /**
     * single match & endpoint
     */
    public function match($owner_id)
    {
        $animal = Animal::find($owner_id);
        $animal->breedDesc = $this->getDescription($animal->breed_id);

        return $this->get_view("owner.match", [
            'owners' => Owner::all(),
            'animal' => $animal,
        ]);
    }

    /**
     * single show view & endpoint
     */
    public function show($owner_id)
    {
        $owner = $this->get_hydrated($owner_id);
        $animals  = Animal::where('owner_id', $owner->id)->get();

        foreach ($animals as $animal) {
            $animal->animalImage = $this->getAnimalImage($animal->id);
        }

        return $this->get_view("owner.show", [
            'owner' => $owner,
            'animals' => $animals,
        ]);
    }

    /**
     * single edit view & endpoint
     */
    public function edit($owner_id)
    {
        return $this->get_view('owner.edit', [
            'owner' => $this->get_hydrated($owner_id),
        ]);
    }

    /**
     * new single edit view & endpoint
     */
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
    public function get_hydrated(string $owner_id): Owner
    {
        $nude_owner = Owner::find($owner_id);
        return Address::hydrateWithAddress($nude_owner);
    }

    /**
     * where is posted to on create
     */
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

    /**
     * where is posted to on update
     */
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
