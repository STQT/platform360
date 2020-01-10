<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function locations()
    {
        return $this->belongsToMany('App\Location')->withTimestamps();
    }
}
