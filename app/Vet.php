<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vet extends Model
{
    public function tables() {
        return $this->belongsToMany(Table::class);
    }
}
