<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['location_id', 'video', 'hfov', 'yaw', 'pitch', 'roll'];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
