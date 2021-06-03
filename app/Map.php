<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Tablegroup;

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
    $tables_to_get = ['addresses', 'vets', 'shelters', 'owners', 'locations'];

    $all_tables = [];
    foreach ($tables_to_get as $table) {
      $all_tables[$table] = DB::table($table)->limit(1000)->get();
    }
    
    $tg_id_map = [
      'animal_type' => Tablegroup::type_to_id('animal_type'),
      'behaviour' => Tablegroup::type_to_id('behaviour'),
      'home_type' => Tablegroup::type_to_id('home_type'),
      'own_animal_type' => Tablegroup::type_to_id('own_animal_type'),
    ];

    $all_animal_types = [];

    $eigen_dier = DB::table('tables')->where('tablegroup_id', $tg_id_map['own_animal_type'])->limit(1000)->get()->all();

    foreach ($eigen_dier as $animal_type_info) {
      $all_animal_types[] = $animal_type_info->description;
    };

    $diervoorkeur = DB::table('tables')->where('tablegroup_id', $tg_id_map['animal_type'])->limit(1000)->get()->all();
    $gedrag = DB::table('tables')->where('tablegroup_id', $tg_id_map['behaviour'])->limit(1000)->get()->all();
    $wonen = DB::table('tables')->where('tablegroup_id', $tg_id_map['home_type'])->limit(1000)->get()->all();

    $guests = DB::select("SELECT g.id as id,
    g.name as name,
    g.phone_number as phone_number,
    g.email_address as email_address,
    g.max_hours_alone as max_hours_alone,
    g.address_id as address_id,
    g.text as text,
    gt.table_ids as table_ids
  FROM guests g
    LEFT JOIN (
        SELECT guest_id, GROUP_CONCAT(table_id) as table_ids FROM guest_table GROUP BY guest_id
    ) gt on gt.guest_id = g.id
  ");


$tabel_descriptions = [];
foreach($diervoorkeur as $meta) {
    $tabel_descriptions[$meta->id] = $meta->description;
}
$gedrag_descriptions = [];
foreach($gedrag as $meta) {
    $gedrag_descriptions[$meta->id] = $meta->description;
}        
$wonen_descriptions = [];
foreach($wonen as $meta) {
    $wonen_descriptions[$meta->id] = $meta->description;
}
$eigen_dier_descriptions = [];
foreach($eigen_dier as $meta) {
  $eigen_dier_descriptions[$meta->id] = $meta->description;
}          

foreach($guests as $guest) {
    $guest->table_ids . "<br>";
    $guest->animal_preference = [];
    $guest->behaviour = [];
    $guest->residence = [];
    $guest->own_animals = [];
    if (empty($guest->table_ids)) continue;
    foreach(explode(',', $guest->table_ids) as $table_id) {

        if (array_key_exists($table_id, $tabel_descriptions)) {
            $guest->animal_preference[] = $tabel_descriptions[$table_id];
        } 
        if (array_key_exists($table_id, $gedrag_descriptions)) {
            $guest->behaviour[] = $gedrag_descriptions[$table_id];
        }                 
        if (array_key_exists($table_id, $wonen_descriptions)) {
            $guest->residence[] = $wonen_descriptions[$table_id];
        }    
        if (array_key_exists($table_id, $eigen_dier_descriptions)) {
          $guest->own_animals[] = $eigen_dier_descriptions[$table_id];
      } 

    }

}

    $all_tables['guests'] = $guests;

    $all_tables['meta'] = [
      'animal_preference' => array_values($tabel_descriptions),
      'behaviour' => array_values($gedrag_descriptions),
      'residence' => array_values($wonen_descriptions),
      'own_animals' => $eigen_dier_descriptions,
      'own_animals_absent' => $eigen_dier_descriptions
    ];

    $all_tables['animals'] = DB::select("SELECT an.id as id,
        an.name as name,
        an.shelter_id as shelter_id,
        an.owner_id as owner_id,
        an.guest_id as guest_id,
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
