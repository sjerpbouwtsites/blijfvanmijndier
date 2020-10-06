<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    public function tables() {
        return $this->belongsToMany(Table::class);
    }
}
