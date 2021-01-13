<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vet;
use App\Address;
use App\Http\Controllers\AbstractController;

class VetController extends AbstractController
{

    public array $validator_rules = ['name'     => 'required'];

    function __construct()
    {
        parent::__construct('vets');
    }

    /**
     * override of abstract placeholder
     * finds instance by id and hydrates it with addrress!.
     * @return Model what currently on offer.
     * @param string id
     */
    public function get_hydrated(string $id): Vet
    {
        $nudy = Vet::find($id);
        $hydrated = Address::hydrateWithAddress($nudy);
        return $hydrated;
    }

    /**
     * override but dont know why. didnt find Vet in abstracts class.
     * single create view & endpoint
     */
    public function create()
    {
        return $this->get_view("vet.edit", [
            'vet' => new Vet,
        ]);
    }

    /**
     * override of abstract placeholder
     * creates new model instance if request does not non-null id prop
     * references Model's own attributes to set request values to self
     * @return bool for success
     * @param Request request the incoming post according to laravel
     * @param string address_id the uuid of the related Address
     */
    public function create_or_save(Request $request, string $address_id)
    {
        $model = $this->get_model_instance($request, Vet::class);
        foreach ($model['own_attributes'] as $key) {
            $model->$key = $request->$key;
        }

        $model->address_id = $address_id;
        $model->save();
        return true;
    }
}
