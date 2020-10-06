<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
	private $animalStatusDesc;
	private $breedDesc;
	private $animaltypeDesc;
	private $gendertypeDesc;
	private $animalImage;
	private $endtypeDesc;
	private $lastUpdate;
	private $lastUpdateSort;
	private $needUpdate;

    public function tables() {
        return $this->belongsToMany(Table::class);
    }
}
