<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\MenuItem;
use App\Table;
use App\Update;
use App\Animal;
use App\Vet;
use DateTime;
use Exception;
use \Illuminate\View\View;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Collection;
use App\Tablegroup;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // ids of table table
    // public $breedId = 1;
    // public $behaviourId = 2;
    // public $vaccinationId = 3;
    // public $animaltypeId = 4;
    // public $hometypeId = 5;
    // public $gendertypeId = 6;
    // public $employeeId = 7;
    // public $doctypeId = 8;
    // public $endtypeId = 9;
    public $updatetypeId = 10;
    public $updatetypeOwner = 179;

    public $model_name;
    public $menuItems = null;

    /**
     * @param string model_name. Naam van de model.... wordt gebruikt om de menu items te zetten.
     * @param string menu_items_source_override. Bv. in history zijn de menu items animals.
     */
    public function __construct($model_name, $menu_items_source = null)
    {
        $this->init_model_name($model_name);
        $this->init_menu_items($model_name, $menu_items_source);
    }

    private function init_model_name($model_name)
    {
        $this->model_name = $model_name;
    }

    private function init_menu_items($model_name, $menu_items_source = null)
    {

        // $menu items source mag overschrijven. Ook als het '' is... alleen null mag niet.
        $menu_items_model = !is_null($menu_items_source) ? $menu_items_source : $model_name;
        $this->menuItems = $this->GetMenuItems($menu_items_model);
    }


    /**
     * wrapper around view and -> with
     * so no every time include menuItems and shortening
     * @param string name of the view
     * @param array to be loaded into view besides menuItems
     * @throws E_NOTICE if menu items empty
     * @return loaded views.
     */
    public function get_view(string $view_name, array $data): View
    {
        if (empty($this->menuItems)) {
            throw new \Exception('menu items leeg', E_NOTICE);
        }
        return view($view_name)->with(array_merge($data, [
            'menuItems' => $this->menuItems
        ]));
    }

    /**
     * if the request has an id, it is assumed that it exists in the db
     * then this returns $Model->find($id)
     * else, return new Model
     * @return Model (new) instance of model
     * @param Request the post according to laravel
     * @param Model a reference to a model class
     */
    public function get_model_instance(Request $request, $Model)
    {
        return $request->id !== null
            ? $Model::find($request->id)
            : new $Model;
    }

    public function getUpdateDate()
    {
        $date = new DateTime();
        $date->modify('-14 day');
        return $date->format('Y-m-d H:i:s');
    }

    public function animalNeedUpdate($id)
    {
        $animal = Animal::find($id);

        if (!$animal->updates) {
            return false;
        }

        $update = Update::where([['updatetype_id', $this->updatetypeOwner], ['link_type', 'animals'], ['link_id', $id]])->orderBy('start_date', 'desc')->first();

        if ($update == null) {
            return ($animal->registration_date < $this->getUpdateDate());
        }

        if ($update->start_date < $this->getUpdateDate()) {
            return true;
        }

        return false;
    }



    public function GetMenuItems($currentPath)
    {
        $menuItem[] = $this->GetMenuItem('Dieren', 'animals', '', 'fa-paw', $currentPath);
        $menuItem[] = $this->GetMenuItem('Eigenaren', 'owners', '', 'fa-female', $currentPath);
        $menuItem[] = $this->GetMenuItem('Dierenartsen', 'vets', '', 'fa-user-md', $currentPath);
        $menuItem[] = $this->GetMenuItem('Opvanglocaties', 'locations', '', 'fa-building', $currentPath);
        $menuItem[] = $this->GetMenuItem('Gastgezinnen', 'guests', '', 'fa-users', $currentPath);
        $menuItem[] = $this->GetMenuItem('Pensions', 'shelters', '', 'fa-home', $currentPath);
        $menuItem[] = $this->GetMenuItem('Tabellen', 'tables', '', 'fa-cog', $currentPath);
        $menuItem[] = $this->GetMenuItem('Kaart', 'map', '', 'fa-map', $currentPath);

        return $menuItem;
    }

    public function GetMenuItem($title, $path, $classes, $icon, $currentPath)
    {
        $active  = $path == $currentPath ? 'active' : '';
        $classes = $classes != '' ? $classes + ' ' + $active : $active;

        return new MenuItem($title, $path, $classes, $icon);
    }

    public function FormatDate($date)
    {
        return date("d-m-Y", strtotime($date));
    }

    public static function FormatDateTime($date)
    {
        return date("d-m-Y H:i:s", strtotime($date));
    }

    public function getDescription($id)
    {
        $table = Table::find($id);
        if ($table) {
            return $table->description;
        }
        return "Onbekend";
    }

    /**
     * @return Collection associative array with * from tables -> tables.id => description
     */
    public function GetTableList($tablegroupId): Collection
    {
        $tableList = Table::All()->where('tablegroup_id', $tablegroupId)->sortBy('description');
        $tableList = $tableList->pluck('description', 'id');
        return $tableList;
    }
}
