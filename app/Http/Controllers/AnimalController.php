<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Animal;
use App\Breed;
use App\Table;
use App\TableGroup;
use App\MenuItem;
use App\Guest;
use App\History;

class AnimalController extends Controller
{

    function __construct()
    {
        parent::__construct('animals');
    }

    public function index()
    {
        $animals = Animal::all();

        foreach ($animals as $animal) {
            $animal->breedDesc = $this->getDescription($animal->breed_id);
            $animal->animalImage = $this->getAnimalImage($animal->id);
            $animal->animaltypeDesc = $this->getDescription($animal->animaltype_id);
            $animal->needUpdate = $this->animalNeedUpdate($animal->id);
        }

        $animals = $animals->sortBy('name');
        $animalsOld = array();
        $animalsNew = array();

        foreach ($animals as $animal) {
            if ($animal->end_date != null) {
                $animalsOld[] = $animal;
            } else {
                $animalsNew[] = $animal;
            }
        }

        return $this->get_view("animal.index", [
            'animalsNew' => $animalsNew,
            'animalsOld' => $animalsOld,
        ]);
    }

    public function shelter($id)
    {
        return $this->match_or_show($id, 'shelter');
    }

    public function owner($id)
    {
        return $this->match_or_show($id, 'owner');
    }

    /**
     * wrapper function to wrap $this->shelter and $this->owner
     */
    private function match_or_show($animal_id, $model_name)
    {
        $animal = Animal::find($animal_id);
        $reference_key = $model_name . '_id';
        $reference_key_value = $animal->$reference_key;
        $controller_name = ucfirst($model_name) . "Controller";
        // like owner_id or shelter_id;
        // direct toward OwnerController@match, etc.
        return $reference_key_value == 0
            ? redirect()->action($controller_name . "@match", $animal->id)
            : redirect()->action($controller_name . "@show", $reference_key_value);
    }

    // START MATCHING
    public function matchshelter($id, $shelter_id)
    {
        return $this->match_generic($id, 'shelter', $shelter_id, 'Pensioen');
    }

    public function matchowner($id, $owner_id)
    {
        return $this->match_generic($id, 'owner', $owner_id, 'Eigenaar');
    }

    public function matchguest($id, $guest_id)
    {
        return $this->match_generic($id, 'guest', $guest_id, 'Gastgezin');
    }

    /**
     * Generic matching function. 
     */
    private function match_generic($animal_id, $controller_name, $model_id, $nl_name)
    {
        $animal = Animal::find($animal_id);
        $reference_key = $controller_name . "_id";
        $animal->$reference_key = $model_id; // $animal->owner_id = $model_id.
        $animal->save();

        HistoryController::saveHistory('animals', $animal->id, $controller_name, $animal->$reference_key, 'connect');
        Session::flash('message', $nl_name . ' succesvol gekoppeld!');
        return redirect()->action('AnimalController@show', $animal->id);
    }
    // END MATCHING

    // START UNCONNECTING
    public function unconnectshelter($id)
    {
        return $this->generic_unconnect($id, 'shelter', 'Pension');
    }

    public function unconnectowner($id)
    {
        return $this->generic_unconnect($id, 'owner', 'Eigenaar');
    }

    public function unconnectguest($id)
    {
        return $this->generic_unconnect($id, 'guest', 'Gastgezin');
    }

    /**
     * generic unconnecting wrapper
     */
    private function generic_unconnect($animal_id, $model_name_singular, $nl_name)
    {
        $animal = Animal::find($animal_id);
        $reference_key = $model_name_singular . "_id";

        HistoryController::saveHistory('animals', $animal->id, $model_name_singular . "s", $animal->$reference_key, 'unconnect');

        $animal->$reference_key = null;
        $animal->save();

        Session::flash('message', $nl_name . ' succesvol ontkoppeld!');
        return redirect()->action('AnimalController@show', $animal->id);
    }
    // END UNCONNECTING



    public function outofproject($id)
    {
        $animal = Animal::find($id);
        $animal->end_date = date('Y-m-d');

        $endtypes = $this->GetTableList($this->endtypeId);
        $endtypes->prepend('Selecteer afmeldreden', '0');


        return $this->get_view("animal.outofproject", [
            'animal' => $animal,
            'endtypes' => $endtypes
        ]);
    }

    public function outofprojectstore(Request $request)
    {
        $validator = $this->validateOutOfProject();

        if ($validator->fails()) {
            return Redirect::to('animals/' . $request->id . '/outofproject')
                ->withErrors($validator)
                ->withInput();
        } else {
            $animal = Animal::find($request->id);
            $animal->endtype_id = $request->endtype_id;
            $animal->end_date = $request->end_date;
            $animal->end_description = $request->end_description;
            $animal->save();

            Session::flash('message', 'Dier succesvol afgemeld!');
            return redirect()->action('AnimalController@show', $request->id);
        }
    }

    public function match($id)
    {
        $animal = Animal::find($id);
        $guestList = array();
        $tmpGuestList = array();

        $behaviourList = Table::All()->where('tablegroup_id', $this->behaviourId);
        $hometypeList = Table::All()->where('tablegroup_id', $this->hometypeId);

        if (Input::has('isSearchAction') && Input::get('isSearchAction') == "true") {
            $checked_hometypes = Input::has('hometypeList') ? Input::get('hometypeList') : [];
            $checked_behaviours = Input::has('behaviourList') ? Input::get('behaviourList') : [];
        } else {
            $checked_hometypes = $animal->tables()->where('tablegroup_id', $this->hometypeId)->pluck('tables.id')->toArray();
            $checked_behaviours = $animal->tables()->where('tablegroup_id', $this->behaviourId)->pluck('tables.id')->toArray();
        }

        foreach ($behaviourList as $table) {
            if (in_array($table->id, $checked_behaviours)) {
                foreach ($table->guests as $guest) {
                    $tmpGuestList[] = $guest;
                }
            }
        }

        foreach ($hometypeList as $table) {
            if (in_array($table->id, $checked_hometypes)) {
                foreach ($table->guests as $guest) {
                    $tmpGuestList[] = $guest;
                }
            }
        }

        if ($tmpGuestList != null) {
            sort($tmpGuestList);
        }

        $oldId = 0;
        foreach ($tmpGuestList as $guest) {
            if ($oldId != $guest->id) {
                $guestList[] = $guest;
                $oldId = $guest->id;
            }
        }

        return $this->get_view("animal.match", [
            'behaviourList' => $behaviourList,
            'checked_behaviours' => $checked_behaviours,
            'hometypeList' => $hometypeList,
            'checked_hometypes' => $checked_hometypes,
            'guests' => $guestList,
            'tables' => $animal->tables,
            'animal' => $animal
        ]);
    }



    public function show($id)
    {

        $animal = Animal::find($id);

        $animal->breedDesc = $this->getDescription($animal->breed_id);
        $animal->animaltypeDesc = $this->getDescription($animal->animaltype_id);
        $animal->gendertypeDesc = $this->getDescription($animal->gendertype_id);
        $animal->endtypeDesc = $this->getDescription($animal->endtype_id);
        $animal->needUpdate = $this->animalNeedUpdate($animal->id);

        $behaviourList = $animal->tables->where('tablegroup_id', $this->behaviourId);
        $vaccinationList = $animal->tables->where('tablegroup_id', $this->vaccinationId);
        $hometypeList = $animal->tables->where('tablegroup_id', $this->hometypeId);

        $animal->abused = $animal->abused ? 'Ja' : 'Nee';
        $animal->witnessed_abuse = $animal->witnessed_abuse ? 'Ja' : 'Nee';
        $animal->updates = $animal->updates ? 'Ja' : 'Nee';
        $animal->registration_date = $this->FormatDate($animal->registration_date);
        $animal->birth_date = $this->FormatDate($animal->birth_date);
        $animal->animalImage = $this->getAnimalImage($animal->id);

        $updates = UpdateController::getUpdatesByLinkType('animals', $animal->id, 2);

        if ($animal->end_date != null) {
            $animal->end_date = $this->FormatDate($animal->end_date);
        }

        return view("animal.show", [
            'animal' => $animal,
            'updates' => $updates,
            'behaviourList' => $behaviourList,
            'vaccinationList' => $vaccinationList,
            'hometypeList' => $hometypeList
        ]);
    }

    public function edit($id)
    {
        $animal = Animal::find($id);
        return view("animal.edit")->with($this->GetAnimalData($animal));
    }

    public function create()
    {
        $animal = new Animal;
        return view("animal.edit")->with($this->GetAnimalData($animal));
    }

    public function store(Request $request)
    {
        $validator = $this->validateAnimal();

        if ($validator->fails()) {
            return Redirect::to('animals/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveAnimal($request);
            Session::flash('message', 'Dier succesvol toegevoegd!');
            return redirect()->action('AnimalController@index');
        }
    }

    public function update(Request $request)
    {
        $validator = $this->validateAnimal();

        if ($validator->fails()) {
            return redirect()->action('AnimalController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveAnimal($request);
            Session::flash('message', 'Dier succesvol gewijzigd!');
            return redirect()->action('AnimalController@show', $request->id);
        }
    }

    public static function getAnimalName($animal_id)
    {
        return Animal::find($animal_id)->name;
    }

    private function GetAnimalData($animal)
    {
        $breeds = $this->GetTableList($this->breedId);
        $animaltypes = $this->GetTableList($this->animaltypeId);
        $gendertypes = $this->GetTableList($this->gendertypeId);

        $behaviourList = Table::All()->where('tablegroup_id', $this->behaviourId);
        $vaccinationList = Table::All()->where('tablegroup_id', $this->vaccinationId);
        $hometypeList = Table::All()->where('tablegroup_id', $this->hometypeId);

        $checked_behaviours = $animal->tables()->where('tablegroup_id', $this->behaviourId)->pluck('tables.id')->toArray();
        $checked_vaccinations = $animal->tables()->where('tablegroup_id', $this->vaccinationId)->pluck('tables.id')->toArray();
        $checked_hometypes = $animal->tables()->where('tablegroup_id', $this->hometypeId)->pluck('tables.id')->toArray();

        $breeds->prepend('Selecteer ras', '0');
        $animaltypes->prepend('Selecteer soort dier', '0');
        $gendertypes->prepend('Selecteer geslacht', '0');

        $animal->animalImage = $this->getAnimalImage($animal->id);

        $data = array(
            'animal' => $animal,
            'breeds' => $breeds,
            'animaltypes' => $animaltypes,
            'gendertypes' => $gendertypes,
            'behaviourList' => $behaviourList,
            'checked_behaviours' => $checked_behaviours,
            'vaccinationList' => $vaccinationList,
            'checked_vaccinations' => $checked_vaccinations,
            'hometypeList' => $hometypeList,
            'checked_hometypes' => $checked_hometypes
        );

        return $data;
    }

    private function validateOutOfProject()
    {
        $rules = array(
            'end_date'        => 'required',
            'endtype_id'      => 'required|numeric|min:1',
            'end_description' => 'required',
        );

        return Validator::make(Input::all(), $rules);
    }

    private function validateAnimal()
    {
        $rules = array(
            'name'              => 'required',
            'animaltype_id'     => 'required|numeric|min:1',
            'breed_id'          => 'required|numeric|min:1',
            'gendertype_id'     => 'required|numeric|min:1',
            'registration_date' => 'required|date',
            'animal_image'      => 'image|mimes:jpg,jpeg|max:1024',
        );

        return Validator::make(Input::all(), $rules);
    }

    private function saveAnimal(Request $request)
    {
        if ($request->id !== null) {
            $animal = Animal::find($request->id);
        } else {
            $animal = new Animal;
        }

        $inputs = Input::all();

        $animal->breed_id = $request->breed_id;
        $animal->animaltype_id = $request->animaltype_id;
        $animal->gendertype_id = $request->gendertype_id;
        $animal->name = $request->name;
        $animal->chip_number = $request->chip_number;
        $animal->passport_number = $request->passport_number;
        $animal->witnessed_abuse = $request->witnessed_abuse ? 1 : 0;
        $animal->abused = $request->abused ? 1 : 0;
        $animal->updates = $request->updates ? 1 : 0;
        $animal->max_hours_alone = $request->max_hours_alone;

        if (isset($request->registration_date) && $request->registration_date != '') {
            $animal->registration_date = $request->registration_date;
        }

        if (isset($request->birth_date) && $request->birth_date != '') {
            $animal->birth_date = $request->birth_date;
        }

        // extra save to get id
        if ($request->id === null) {
            $animal->save();
        }

        if (isset($inputs['tables'])) {
            $tables = $inputs['tables'];
        } else {
            $tables = [];
        }

        $animal->tables()->sync($tables);
        $animal->save();

        if ($request->hasFile('animal_image')) {
            $imageName = 'animal_' . $animal->id . '.' . $request->file('animal_image')->getClientOriginalExtension();
            $imageName = strtolower($imageName);
            $request->file('animal_image')->move(base_path() . '/public/img/', $imageName);
        }
    }
}
