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


class OwnerController extends AbstractController
{
    public $required = [
        'name',
        'phone_number',
        'email_address',
        'city',
        'house_number',
        'street',
        'postal_code'
    ];
    
    public $uses_generic_index = true;
    public $index_columns = ['Naam', 'Adres', 'Telefoonnummer'];
    

    function __construct()
    {
        parent::__construct('owners');
    }

    public function create_index_rows($owners){
        $index_rows = '';
        foreach ($owners as $owner) {
            $index_rows .= "<tr>";

            $index_rows .= $this->wrap_in_show_link($owner->id, "$owner->name $owner->prefix $owner->surname");
            $index_rows .= $this->wrap_in_show_link($owner->id, "$owner->street $owner->house_number $owner->city");
            $index_rows .= $this->wrap_in_show_link($owner->id, "$owner->phone_number");

            $index_rows .= "<td><a href='/".$this->plural."/".$owner->id."/edit'>🖊</a></td>";
            $index_rows .= $this->focus_in_maya_cell($owner->id);            
            $index_rows .= "</tr>";
        }
        return $index_rows;
    }


    /**
     * single match & endpoint
     */
    public function match($owner_id)
    {
        $animal = Animal::find($owner_id);
        $animal->breedDesc = $this->getDescription($animal->breed_id);

        return $this->get_view("owner.match", [
            'owners' => Address::allWithAddress("App\Owner")->sortBy('name'),
            'animal' => $animal,
        ]);
    }

    /**
     * single show view & endpoint
     */
    public function show($owner_id)
    {
        $owner = $this->get_hydrated($owner_id);
        $animals = Animal::setAnimalArrayImages(Animal::where('owner_id', $owner->id)->get());

        return $this->get_view("owner.show", [
            'owner' => $owner,
            'updates' => UpdateController::getUpdatesByLinkType('owner', $owner->id, 2),
            'animals' => $animals,
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
     * creates new owner if request does not non-null id prop
     * references Model's own attributes to set request values to self
     * @return bool for success
     * @param Request request the incoming post according to laravel
     * @param string address_id the uuid of the related Address
     */
    public function create_or_save(Request $request, string $address_id): bool
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
