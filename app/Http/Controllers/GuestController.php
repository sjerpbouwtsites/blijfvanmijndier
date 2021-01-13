<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Guest;
use App\Animal;
use App\Table;
use App\Address;

use App\MenuItem;


class GuestController extends Controller
{

    function __construct()
    {
        parent::__construct('guests');
    }

    public function index()
    {
        $guests = Guest::allWithAddress();
        $guests = $guests->sortBy('name');

        $menuItems = $this->GetMenuItems('guests');

        $data = array(
            'guests' => $guests,
            'menuItems' => $menuItems
        );

        return view("guest.index")->with($data);
    }

    public function show($id)
    {
        $guest = Guest::findWithAddress($id);

        $animals = Animal::where('guest_id', $guest->id)->get();

        foreach ($animals as $animal) {
            $animal->animalImage = $this->getAnimalImage($animal->id);
        }

        $behaviourList = $guest->tables->where('tablegroup_id', $this->behaviourId);
        $hometypeList = $guest->tables->where('tablegroup_id', $this->hometypeId);
        $animaltypeList = $guest->tables->where('tablegroup_id', $this->animaltypeId);

        $menuItems = $this->GetMenuItems('guests');

        $updates = UpdateController::getUpdatesByLinkType('guests', $guest->id, 2);

        $data = array(
            'guest' => $guest,
            'animals' => $animals,
            'updates' => $updates,
            'menuItems' => $menuItems,
            'behaviourList' => $behaviourList,
            'hometypeList' => $hometypeList,
            'animaltypeList' => $animaltypeList
        );

        return view("guest.show")->with($data);
    }

    public function edit($id)
    {
        $guest = Guest::findWithAddress($id);
        $data = $this->GetGuestData($guest);

        return view("guest.edit")->with($data);
    }

    public function create()
    {
        $guest = new Guest;
        $data = $this->GetGuestData($guest);

        return view("guest.edit")->with($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateGuest();

        if ($validator->fails()) {
            return Redirect::to('guests/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveGuest($request);
            Session::flash('message', 'Gastgezin succesvol toegevoegd!');
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
        } else {
            $this->saveGuest($request);
            Session::flash('message', 'Gastgezin succesvol gewijzigd!');
            return redirect()->action('GuestController@show', $request->id);
        }
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
        return Validator::make(Input::all(), Guest::$required_to_save);
    }

    private function saveGuest(Request $request)
    {
        // create new guest or use existing.

        if ($request->id !== null) {
            $guest = Guest::findWithAddress($request->id);
        } else {
            $guest = new Guest;
        }

        // the form.
        $inputs = Input::all();

        $Address = new Address();

        if ($Address->address_new_or_changed($inputs, $guest['attributes'])) {
            $Address->setNewValues($inputs);
            $guest->address_id = $Address->uuid_check($inputs);
            $Address->geoIpRoundTrip($inputs);
            $Address->save();
        }

        $guest->name = $request->name;
        $guest->phone_number = $request->phone_number;
        $guest->email_address = $request->email_address;
        $guest->max_hours_alone = $request->max_hours_alone > 0 ? $request->max_hours_alone : 0;
        $guest->text = $request->text;

        if (isset($inputs['tables'])) {
            $tables = $inputs['tables'];
        } else {
            $tables = [];
        }

        // extra save to get id
        if ($request->id === null) {
            $guest->save();
        }

        dd($guest->tables());
        // $guest->tables()->sync($tables);
        // $guest->save();
    }
}
