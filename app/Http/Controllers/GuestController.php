<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Guest;
use App\Animal;
use App\Table;
use App\Address;

class GuestController extends Controller
{

    function __construct()
    {
        parent::__construct('guests');
    }

    /**
     * plenary view & root endpoint
     */
    public function index()
    {
        return $this->get_view("guest.index", [
            'guests' => Address::allWithAddress('App\Guest')->sortBy('name'),
        ]);
    }

    /**
     * single show view & endpoint
     */
    public function show($guest_id)
    {
        $guest = $this->get_hydrated($guest_id);
        $animals = Animal::setAnimalArrayImages(
            Animal::where('guest_id', $guest->id)->get()
        );

        return $this->get_view("guest.show", [
            'guest' => $guest,
            'animals' => $animals,
            'updates' => UpdateController::getUpdatesByLinkType('guests', $guest->id, 2),
            'behaviourList' => $guest->tables->where('tablegroup_id', $this->behaviourId),
            'hometypeList' => $guest->tables->where('tablegroup_id', $this->hometypeId),
            'animaltypeList' => $guest->tables->where('tablegroup_id', $this->animaltypeId)
        ]);
    }

    /**
     * single edit view & endpoint
     */
    public function edit($guest_id)
    {
        $d = $this->get_hydrated($guest_id);
        $dd = $this->GetGuestData($d);
        return $this->get_view('guest.edit', $dd);
    }
    /**
     * new single edit view & endpoint
     */
    public function create()
    {
        return $this->get_view(
            "guest.edit",
            $this->GetGuestData(new Guest),
        );
    }

    /**
     * where is posted to on create
     */
    public function store(Request $request)
    {
        $validator = $this->validateGuest();

        if ($validator->fails()) {
            return Redirect::to('guests/create')
                ->withErrors($validator)
                ->withInput();
        }
        $ai = Address::save_or_create_address(true);
        if ($this->create_or_save_guest($request, $ai)) {
            Session::flash('message', 'gastgezin succesvol toegevoegd!');
            return redirect()->action('GuestController@index');
        } else {
            Session::flash('message', 'Fout bij het opslaan!');
            return redirect()->action('GuestController@index');
        }
    }

    public function update(Request $request)
    {
        $validator = $this->validateGuest();

        if ($validator->fails()) {
            return redirect()->action('GuestController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        }
        $ai = Address::save_or_create_address(false);
        if ($this->create_or_save_guest($request, $ai)) {
            Session::flash('message', 'gastgezin succesvol opgeslagen!');
            return redirect()->action('GuestController@index');
        } else {
            Session::flash('message', 'Fout bij het opslaan!');
            return redirect()->action('GuestController@index');
        }
    }

    /**
     * finds guest by id and hydrates the guest.
     * @return Guest
     * @param string id
     */
    public function get_hydrated(string $id): Guest
    {
        $nude_guest = Guest::find($id);
        return Address::hydrateWithAddress($nude_guest);
    }

    /**
     * Helper van edit en create
     */
    private function GetGuestData($guest)
    {
        $behaviourList = Table::All()->where('tablegroup_id', $this->behaviourId);
        $hometypeList = Table::All()->where('tablegroup_id', $this->hometypeId);
        $animaltypeList = Table::All()->where('tablegroup_id', $this->animaltypeId);

        $checked_behaviours = $guest->tables()->where('tablegroup_id', $this->behaviourId)->pluck('tables.id')->toArray();
        $checked_hometypes = $guest->tables()->where('tablegroup_id', $this->hometypeId)->pluck('tables.id')->toArray();
        $checked_animaltypes = $guest->tables()->where('tablegroup_id', $this->animaltypeId)->pluck('tables.id')->toArray();

        $menuItems = $this->GetMenuItems('guests');

        $data = array(
            'guest' => $guest,
            'menuItems' => $menuItems,
            'behaviourList' => $behaviourList,
            'checked_behaviours' => $checked_behaviours,
            'hometypeList' => $hometypeList,
            'checked_hometypes' => $checked_hometypes,
            'animaltypeList' => $animaltypeList,
            'checked_animaltypes' => $checked_animaltypes
        );

        return $data;
    }

    private function validateGuest()
    {
        // @TODO HIER CONTROLE OP ADRES SCHRIJVEN.
        return Validator::make(Input::all(), [
            'name',
            'phone_number',
            'email_address',
            'city',
            'house_number',
            'street',
            'postal_code'
        ]);
    }

    /**
     * creates new guest if request does not non-null id prop
     * references Model's own attributes to set request values to self
     * @return bool for success
     * @param Request request the incoming post according to laravel
     * @param string address_id the uuid of the related Address
     */
    private function create_or_save_guest(Request $request, string $address_id): bool
    {
        // create new guest or use existing.
        $guest = $this->get_model_instance($request, Guest::class);
        foreach ($guest['own_attributes'] as $key) {
            $guest->$key = $request->$key;
        }
        $guest->address_id = $address_id;

        if (isset($inputs['tables'])) {
            $tables = $inputs['tables'];
        } else {
            $tables = [];
        }

        // // extra save to get id
        // if ($request->id === null) {
        //     $guest->save();
        // }

        $guest->tables()->sync($tables);
        $guest->save();
        return true;
    }
}
