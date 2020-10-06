<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shelter extends Model
{
    public function tables() {
        return $this->belongsToMany(Table::class);
    }
}
