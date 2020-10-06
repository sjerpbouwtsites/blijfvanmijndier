<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function tables() {
        return $this->belongsToMany(Table::class);
    }
}
