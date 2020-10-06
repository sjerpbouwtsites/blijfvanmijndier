<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
	private $guestStatusDesc;
	private $tableGroupDesc;
	private $tableDesc;

    public function tables() {
        return $this->belongsToMany(Table::class);
    }
}
