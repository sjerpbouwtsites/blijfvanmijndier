<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Animal;
use App\Table;
use App\Tablegroup;

/**
 * Watch out this controller is an animal! 
 * it's the core but also the most 'outlier' of the controllers
 * logic generally a little more legacy here.
 */

class AnimalController extends Controller
{

    function __construct()
    {
        parent::__construct('animals');
    }

    // START GENERAL VIEWS
    public function index()
    {
        $animals = Animal::all();
        foreach ($animals as $animal) {
            $animal = $this->index_show_hydrate_animal($animal);
        }
        $animals = $animals->sortBy('name');
        // animals old apparaently from 'project'?
        $animalsOld = array();
        $animalsNew = array();
        foreach ($animals as $animal) {
            if ($animal->end_date != null) {
                $animalsOld[] = $animal;
            } else {
                $animalsNew[] = $animal;
            }
        }
        $animals_old_view = count($animalsOld) > 0
            ? $this->get_view('animal.old', ['old_animals' => $animalsOld])
            : '';
        return $this->get_view("animal.index", [
            'animals' => $animalsNew,
            'animalsOldView' => $animals_old_view,
        ]);
    }

    /**
     * single view.
     */
    public function show($id)
    {
        $animal = $this->index_show_hydrate_animal(Animal::find($id));

        $updates = UpdateController::getUpdatesByLinkType('animals', $animal->id, 2);

        return $this->get_view("animal.show", array_merge(
            [
                'updates' => $updates
            ],
            $this->animal_meta($animal)
        ));
    }

    /**
     * helper to show / index views. 
     */
    private function index_show_hydrate_animal(Animal $animal): Animal
    {
        $animal->breedDesc = $this->getDescription($animal->breed_id);
        $animal->animaltypeDesc = $this->getDescription($animal->animaltype_id);
        $animal->gendertypeDesc = $this->getDescription($animal->gendertype_id);
        $animal->endtypeDesc = $this->getDescription($animal->endtype_id);
        $animal->needUpdate = $this->animalNeedUpdate($animal->id);
        $animal->setAnimalImage();
        return $animal;
    }


    /**
     * single edit view.
     */
    public function edit($id)
    {
        $animal = Animal::find($id);
        return $this->create_or_edit($animal);
    }

    /**
     * single create new animal view.
     */
    public function create()
    {
        return $this->create_or_edit(new Animal);
    }

    /**
     * helper to create and edit
     * @param Animal create gives a new Animal, edit passes a found animal
     */
    private function create_or_edit(Animal $animal)
    {
        return $this->get_view("animal.edit", array_merge(
            $this->animal_meta($animal),
            $this->get_table_lists()
        ));
    }

    // END GENERAL VIEWS

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



    /**
     * single animal remove from project... 
     * end_date property is effectively a boolean!
     */
    public function outofproject($id)
    {
        $animal = Animal::find($id);
        $animal->end_date = date('Y-m-d');

        $endtypes = $this->GetTableList(Tablegroup::type_to_id('end_type'));
        $endtypes->prepend('Selecteer afmeldreden', '0');

        return $this->get_view("animal.outofproject", [
            'animal' => $animal,
            'endtypes' => $endtypes
        ]);
    }

    /**
     * single animal store remove from project... 
     * end_date property is effectively a boolean!
     */
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

    /**
     * Looking at data from the animal and the table-table 
     * litterally matches from beast to man are made
     */
    public function match($id)
    {
        $animal = Animal::find($id);
        $guestList = array();
        $tmpGuestList = array();

        $animal_meta = $this->animal_meta($animal, ['vaccination']);

        if (Input::has('isSearchAction') && Input::get('isSearchAction') == "true") {
            $checked_hometypes = Input::has('hometypeList') ? Input::get('hometypeList') : [];
            $checked_behaviours = Input::has('behaviourList') ? Input::get('behaviourList') : [];
        } else {
            $checked_hometypes = $animal->tables()->where('tablegroup_id', Tablegroup::type_to_id('home_type'))->pluck('tables.id')->toArray();
            $checked_behaviours = $animal->tables()->where('tablegroup_id', Tablegroup::type_to_id('behaviour'))->pluck('tables.id')->toArray();
        }

        foreach ($animal_meta['behaviourList'] as $table) {
            if (in_array($table->id, $checked_behaviours)) {
                foreach ($table->guests as $guest) {
                    $tmpGuestList[] = $guest;
                }
            }
        }

        foreach ($animal_meta['hometypeList'] as $table) {
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

        return $this->get_view("animal.match", array_merge(
            $animal_meta,
            [
                'checked_behaviours' => $checked_behaviours,
                'checked_hometypes' => $checked_hometypes,
                'guests' => $guestList,
                'tables' => $animal->tables,
                'animal' => $animal
            ]
        ));
    }

    /**
     * single animal create endpoint
     */
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

    /**
     * single existing animal updates endpoint
     */
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

    /**
     * generic function for getting behaviourList, vaccinationList, hometypeList and their checked ones.
     * @param Animal animal instance
     * @param Array skip. which of behaviour, vaccination or hometype to skip.
     * @return Array with behaviourList, vaccinationList, hometypeList and their id/description collections; also behaviourListChecked, etc. 
     */
    private function animal_meta(Animal $animal, $skip = array()): array
    {
        $to_return = [];
        foreach (['behaviour', 'vaccination', 'home_type'] as $group) {
            if (in_array($group, $skip)) continue;
            $list_name = $group . "List";
            $table_group_id = Tablegroup::type_to_id($group);
            // all in this group.
            $all_in_group = Table::All()->where(
                'tablegroup_id',
                $table_group_id
            );
            $to_return[$list_name] = $all_in_group;

            // all in this group checked, complete objects
            $all_checked_ids = $animal->tables->where('tablegroup_id', $table_group_id)->pluck('id')->toArray();
            $complete_and_checked = [];
            foreach ($all_in_group as $one_of_all) {
                if (in_array($one_of_all['attributes']['id'], $all_checked_ids)) {
                    $complete_and_checked[] = $one_of_all;
                }
            }
            $to_return[$list_name . 'Checked'] = collect($complete_and_checked);

            // all checked in this group, id list.
            $to_return['checked_' . $group . 's'] = $all_checked_ids;
        }
        $to_return['animal'] = $animal;
        return $to_return;
    }

    /**
     * creates lists of all possible breeds, animalTypes, gendertypes
     * @return Array see above k
     */
    private function get_table_lists()
    {
        // lijsten uit tables tabel met id => description data.
        // prepend lege optie zodat geen optie ook kan. 
        $breeds = $this->GetTableList(Tablegroup::type_to_id('breed'));
        $animaltypes = $this->GetTableList(Tablegroup::type_to_id('animal_type'));
        $gendertypes = $this->GetTableList(Tablegroup::type_to_id('gender_type'));
        $breeds->prepend('Selecteer ras', '0');
        $animaltypes->prepend('Selecteer soort dier', '0');
        $gendertypes->prepend('Selecteer geslacht', '0');
        return [
            'breeds' => $breeds,
            'animaltypes' => $animaltypes,
            'gendertypes' => $gendertypes,
        ];
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

    /**
     * checkboxes and databases no love lost.
     * @return int;
     */
    private function checkbox_fix($checkbox_val): int
    {
        if (empty($checkbox_val)) return 0;
        if ($checkbox_val === 1 || $checkbox_val === 0) return $checkbox_val;
        if ($checkbox_val === '1' || $checkbox_val == 'on') return 1;
        return 0;
    }

    private function saveAnimal(Request $request)
    {
        $animal = $this->get_model_instance($request, Animal::class);
        // if checkbox, correct; if not allowed empty... set null
        $chkbox_keys = ['witnessed_abuse', 'abused', 'updates'];
        $not_null_keys = ['birth_date', 'registration_date'];
        // set vals from request to animal.
        foreach (['breed_id', 'animaltype_id', 'gendertype_id', 'name', 'chip_number', 'passport_number', 'max_hours_alone', 'witnessed_abuse', 'abused', 'updates', 'registration_date', 'birth_date'] as $key) {
            $animal[$key] = in_array($key, $chkbox_keys)
                ? $this->checkbox_fix($request->$key)
                : (in_array($key, $not_null_keys) && empty($request->$key)
                    ? null
                    : $request->$key);
        }

        // extra save to get id 
        if ($request->id === null) {
            $animal->save();
        }

        $tables = Input::has('tables')
            ? Input::get('tables')
            : [];
        $animal->tables()->sync($tables);
        $animal->save();

        if ($request->hasFile('animal_image')) {
            $imageName = 'animal_' . $animal->id . '.' . $request->file('animal_image')->getClientOriginalExtension();
            $imageName = strtolower($imageName);
            $request->file('animal_image')->move(base_path() . '/public/img/', $imageName);
        }
    }
}
