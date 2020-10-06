<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Shelter;
use App\Animal;
use App\MenuItem;

class MapController extends Controller
{

    public function index()
    {

        $shelters = Shelter::all();
        $shelters = $shelters->sortBy('name');

        $menuItems = $this->GetMenuItems('shelters');

        $data = array(
            'shelters' => $shelters,
            'menuItems' => $menuItems
        );

        return view("map.index")->with($data);
    }
}
