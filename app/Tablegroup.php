<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tablegroup extends Model
{

    private static $initialized = false;

    private static $tablegroup_by_id = array();

    private static $tablegroup_by_type = array();

    private static function initialize_tablegroup_cache(){
        $tablegroups = DB::select("SELECT * FROM tablegroups");
        foreach($tablegroups as $tablegroup){
            Tablegroup::$tablegroup_by_id[$tablegroup->id] = $tablegroup;
            Tablegroup::$tablegroup_by_type[$tablegroup->type] = $tablegroup;
        }
    }

    public static  function get_tablegroup_by_id($id = null){
        if (\is_null($id) || empty($id)) {
            throw new \Exception('forgot to add ID to func');
        }
        if (!Tablegroup::$initialized) {
            Tablegroup::initialize_tablegroup_cache();
        }
        $tablegroup = Tablegroup::$tablegroup_by_id[$id];
        if (\is_null($tablegroup) || empty($tablegroup)) {
            throw new \Exception("Tablegroup with id $id not found");
        }
        return $tablegroup;
    }

    public static  function get_tablegroup_by_type($type = null){
        if (\is_null($type) || empty($type)) {
            throw new \Exception('forgot to add ID to func');
        }
        if (!Tablegroup::$initialized) {
            Tablegroup::initialize_tablegroup_cache();
        }
        if (!array_key_exists($type, Tablegroup::$tablegroup_by_type)){
            echo "<pre>"; var_dump(Tablegroup::$tablegroup_by_id); echo "</pre>";
            throw new \Exception("Tablegroup $type unknown");
        }
        $tablegroup = Tablegroup::$tablegroup_by_type[$type];
        if (empty($tablegroup)) {
            throw new \Exception("Tablegroup with type $type empty");
        }
        return $tablegroup;
    }

    public static function type_to_id($type){
        return Tablegroup::get_tablegroup_by_type($type)->id;
    }
    public static function type_to_name($type){
        return Tablegroup::get_tablegroup_by_type($type)->name;
    }    
    public static function id_to_type($id){
        return Tablegroup::get_tablegroup_by_id($id)->type;
    }
    public static function id_to_name($id){
        return Tablegroup::get_tablegroup_by_id($id)->name;
    }    

}
