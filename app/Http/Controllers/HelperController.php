<?php

namespace App\Http\Controllers;

use App\Table;

class HelperController
{
    public static function FormatDate($date){
        return date("d-m-Y", strtotime($date));
    }

    public static function getDescription($id){
        $table = Table::find($id);
        if($table){
            return $table->description;
        }
        return "Onbekend";
    }
}	
