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
        $menuItems = $this->GetMenuItems('animals');
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
            'menuItems' => $menuItems
        ]);
    }

    public function shelter($id)
    {
        $animal = Animal::find($id);
        if ($animal->shelter_id == 0) {
            return redirect()->action('ShelterController@match', $animal->id);
        } else {
            return redirect()->action('ShelterController@show', $animal->shelter_id);
        }
    }

    public function matchshelter($id, $shelter_id)
    {
        $animal = Animal::find($id);
        $animal->shelter_id = $shelter_id;
        $animal->save();

        HistoryController::saveHistory('animals', $animal->id, 'shelters', $animal->shelter_id, 'connect');

        Session::flash('message', 'Pension succesvol gekoppeld!');
        return redirect()->action('AnimalController@show', $animal->id);
    }

    public function unconnectshelter($id)
    {
        $animal = Animal::find($id);

        HistoryController::saveHistory('animals', $animal->id, 'shelters', $animal->shelter_id, 'unconnect');

        $animal->shelter_id = null;
        $animal->save();

        Session::flash('message', 'Pension succesvol ontkoppeld!');
        return redirect()->action('AnimalController@show', $animal->id);
    }

    public function owner($id)
    {

        $animal = Animal::find($id);
        if ($animal->owner_id == 0) {
            return redirect()->action('OwnerController@match', $animal->id);
        } else {
            return redirect()->action('OwnerController@show', $animal->owner_id);
        }
    }

    public function matchowner($id, $owner_id)
    {
        $animal = Animal::find($id);
        $animal->owner_id = $owner_id;
        $animal->save();

        HistoryController::saveHistory('animals', $animal->id, 'owners', $animal->owner_id, 'connect');

        Session::flash('message', 'Eigenaar succesvol gekoppeld!');
        return redirect()->action('AnimalController@show', $animal->id);
    }

    public function unconnectowner($id)
    {
        $animal = Animal::find($id);

        HistoryController::saveHistory('animals', $animal->id, 'owners', $animal->owner_id, 'unconnect');

        $animal->owner_id = null;
        $animal->save();

        Session::flash('message', 'Eigenaar succesvol ontkoppeld!');
        return redirect()->action('AnimalController@show', $animal->id);
    }

    public function matchguest($id, $guest_id)
    {
        $animal = Animal::find($id);
        $animal->guest_id = $guest_id;
        $animal->save();

        HistoryController::saveHistory('animals', $animal->id, 'guests', $animal->guest_id, 'connect');

        Session::flash('message', 'Gastgezin succesvol gekoppeld!');
        return redirect()->action('AnimalController@show', $animal->id);
    }

    public function unconnectguest($id)
    {
        $animal = Animal::find($id);

        HistoryController::saveHistory('animals', $animal->id, 'guests', $animal->guest_id, 'unconnect');

        $animal->guest_id = null;
        $animal->save();

        Session::flash('message', 'Gastgezin succesvol ontkoppeld!');
        return redirect()->action('AnimalController@show', $animal->id);
    }

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

    public function match2($id)
    {
        $animal = Animal::find($id);
        $animal->breedDesc = $this->getDescription($animal->breed_id);

        $animaltypeList = Table::All()->where('tablegroup_id', $this->animaltypeId);
        $checked_animaltypes = Input::has('animaltypeList') ? Input::get('animaltypeList') : [];

        if (Input::has('isSearchAction')) {
            $guestList = collect();
            foreach ($animaltypeList as $table) {
                if (in_array($table->id, $checked_animaltypes)) {
                    foreach ($table->guests as $guest) {
                        if (!$guestList->contains('id', $guest->id)) {
                            $guestList->push($guest);
                        }
                    }
                }
            }
        } else {
            $guestList = Guest::all();
        }

        return $this->get_view("animal.match", [
            'guests' => $guestList->sortBy('name'),
            'animal' => $animal,
            'animaltypeList' => $animaltypeList,
            'checked_animaltypes' => $checked_animaltypes
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
