<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Map extends Model
{

    public function tables()
    {
        return $this->belongsToMany(Table::class);
    }

    /**
     * DB::table -> get all tables to get and return json encoded.
     * @return json all map data tables
     */
    public static function map_data()
    {

        $tables_to_get = ['addresses', 'guests', 'vets', 'shelters', 'owners'];

        $all_tables = [];
        foreach ($tables_to_get as $table) {
            $all_tables[$table] = DB::table($table)->get();
        }
        $all_tables['animals'] = DB::table('animals')->whereNull('end_date')->get();

        return json_encode($all_tables);
    }
}
