<?php

namespace App\Http\Controllers;


use App\Map;
use App\Table;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{

    function __construct()
    {
        // TODO is this still relevant?
        parent::__construct('maps', 'animals');
    }

    public function index()
    {

        return view("map.index");
    }

    public function map_data()
    {
        return json_encode(Map::map_data());
    }
}
