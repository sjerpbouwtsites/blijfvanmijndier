<?php

namespace App\Http\Controllers;


use App\Map;
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

        // $tables_to_get = ['addresses', 'guests', 'vets', 'shelters', 'owners'];

        // $all_tables = [];
        // foreach ($tables_to_get as $table) {
        //     $all_tables[$table] = DB::table($table)->get();
        // }

        // dd($all_tables);

        return view("map.index");
    }

    public function map_data()
    {
        return json_encode(Map::map_data());
    }
}
