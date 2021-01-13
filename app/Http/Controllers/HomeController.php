<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MenuItem;
use App\Update;
use App\Animal;
use DateTime;

class HomeController extends Controller
{

    function __construct()
    {
        parent::__construct('homes', '');
    }

    public function home()
    {

        // Latest updates
        $updates = UpdateController::getLatestUpdates(true, 80, null);

        // Latest animals
        $animals = Animal::orderBy('registration_date', 'desc')->limit(5)->get();

        foreach ($animals as $animal) {
            $animal->registration_date = $this->FormatDate($animal->registration_date);
            $animal->breedDesc = $this->getDescription($animal->breed_id);
            $animal->animaltypeDesc = $this->getDescription($animal->animaltype_id);

            $update = Update::where([['link_type', 'animals'], ['link_id', $animal->id]])->orderBy('start_date', 'desc')->first();
            if ($update != null) {
                $animal->lastUpdate = $this->FormatDate($update->start_date);
            }
        }

        // Animals need update
        $animalsAll = Animal::whereNull('end_date')->get();
        $animalsToUpdate = array();
        $collection = collect();

        foreach ($animalsAll as $animal) {

            if (!$animal->updates) {
                continue;
            }

            if ($this->animalNeedUpdate($animal->id)) {

                $animal->breedDesc = $this->getDescription($animal->breed_id);
                $animal->animaltypeDesc = $this->getDescription($animal->animaltype_id);

                $update = Update::where([['updatetype_id', $this->updatetypeOwner], ['link_type', 'animals'], ['link_id', $animal->id]])->orderBy('start_date', 'desc')->first();

                if ($update != null) {
                    $animal->lastUpdate = $this->FormatDate($update->start_date);
                    $animal->lastUpdateSort = $update->start_date;
                }

                $collection->push($animal);
            }
        }

        // @TODO ????
        $menuItems = $this->GetMenuItems('');

        $data = array(
            'updates' => $updates,
            'animals' => $animals,
            'animalsToUpdate' => $collection->sortBy('lastUpdateSort'),
            'tiles' => $menuItems,
        );

        return view("home")->with($data);
    }
}
