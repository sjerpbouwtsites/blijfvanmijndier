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
use DateTime;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $breedId;
    public $behaviourId;
    public $vaccinationId;    
    public $animaltypeId;
    public $hometypeId;
    public $gendertypeId;
    public $employeeId;
    public $doctypeId;
    public $endtypeId;
    public $updatetypeId;
    public $updatetypeOwner;

    public function __construct(){
        $this->breedId = 1;
        $this->behaviourId = 2;
        $this->vaccinationId = 3;    
        $this->animaltypeId = 4;
        $this->hometypeId = 5;
        $this->gendertypeId = 6;
        $this->employeeId = 7;
        $this->doctypeId = 8;
        $this->endtypeId = 9;
        $this->updatetypeId = 10;
        $this->updatetypeOwner = 179;
    }

    public function getUpdateDate(){
        $date = new DateTime();
        $date->modify('-14 day');
        return $date->format('Y-m-d H:i:s');
    }

    public function animalNeedUpdate($id){
        $animal = Animal::find($id);

        if(!$animal->updates){
            return false;
        }

        $update = Update::where([['updatetype_id', $this->updatetypeOwner],['link_type', 'animals'],['link_id', $id]])->orderBy('start_date', 'desc')->first();    

        if($update == null){
            return ($animal->registration_date < $this->getUpdateDate());
        } 

        if($update->start_date < $this->getUpdateDate()){
            return true;
        }

        return false;
    }

    public function getAnimalImage($id){
        $image = 'img/' . 'animal_' . $id . '.jpg';

        if(file_exists($image)){
            return $image;
        }

        return 'img/placeholder.jpg';
    }

    public function GetMenuItems($currentPath){
        $menuItem[] = $this->GetMenuItem('Dieren', 'animals', '', 'fa-paw', $currentPath); 
        $menuItem[] = $this->GetMenuItem('Eigenaren', 'owners', '', 'fa-female', $currentPath); 
        $menuItem[] = $this->GetMenuItem('Dierenartsen', 'vets', '', 'fa-user-md', $currentPath); 
        $menuItem[] = $this->GetMenuItem('Opvanglocaties', 'locations', '', 'fa-building', $currentPath); 
        $menuItem[] = $this->GetMenuItem('Gastgezinnen', 'guests', '', 'fa-users', $currentPath); 
        $menuItem[] = $this->GetMenuItem('Pensions', 'shelters', '', 'fa-home', $currentPath); 
        $menuItem[] = $this->GetMenuItem('Tabellen', 'tables', '', 'fa-cog', $currentPath); 

        return $menuItem;
    }

    public function GetMenuItem($title, $path, $classes, $icon, $currentPath){
        $active  = $path == $currentPath ? 'active' : '';
        $classes = $classes != '' ? $classes + ' ' + $active : $active;

        return new MenuItem($title, $path, $classes, $icon); 
    }

    public function FormatDate($date){
        return date("d-m-Y", strtotime($date));
    }

    public static function FormatDateTime($date){
        return date("d-m-Y H:i:s", strtotime($date));
    }

    public function getDescription($id){
        $table = Table::find($id);
        if($table){
            return $table->description;
        }
        return "Onbekend";
    }

    public function GetTableList($tablegroupId){
        $tableList = Table::All()->where('tablegroup_id', $tablegroupId)->sortBy('description');
        $tableList = $tableList->pluck('description', 'id');

        return $tableList;
    }
}
