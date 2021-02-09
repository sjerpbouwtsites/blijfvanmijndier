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
      $all_tables[$table] = DB::table($table)->limit(100)->get();
    }
    $all_tables['animals'] = DB::select("SELECT an.id as id,
        an.name as name,
        an.shelter_id as shelter_id,
        an.owner_id as owner_id,
        an.guest_id as guest_id,
        tBreed.description as breed,
        tAnimalType.description as animal_type,
        tGenderType.description as gender,
        an.registration_date as reg_date,
        an.birth_date as birth_date,
        an.chip_number as chip_nr,
        an.passport_number as passport,
        an.max_hours_alone as max_hours_alone,
        an.witnessed_abuse as witnessed_abuse,
        an.abused as abused
      FROM animals an
        LEFT JOIN tables tBreed ON tBreed.id = an.breed_id
        LEFT JOIN tables tAnimalType ON tAnimalType.id = an.animaltype_id  
        LEFT JOIN tables tGenderType ON tGenderType.id = an.gendertype_id;
      ");

    return json_encode($all_tables);
  }
}
