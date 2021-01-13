<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TableGroup;

class Table extends Model
{
    private $tableGroupDesc;

    public function animals()
    {
        return $this->belongsToMany(Animal::class);
    }

    public function guests()
    {
        return $this->belongsToMany(Guest::class);
    }
}
