<?php

namespace App\Http\Controllers;


use App\Map;

class MapController extends Controller
{

    function __construct()
    {
        // TODO is this still relevant?
        parent::__construct('maps', 'animals');
    }

    public function index()
    {

        $map_data = Map::map_data();

        $data = array(
            'map_data'      => $map_data,
        );

        return view("map.index")->with($data);
    }
}
