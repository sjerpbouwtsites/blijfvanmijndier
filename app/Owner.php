<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    public function tables() {
        return $this->belongsToMany(Table::class);
    }

    public function contact(){
    	return $this->hasOne(Contact::class);
    }
}
