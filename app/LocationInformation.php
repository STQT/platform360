<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationInformation extends Model
{
    protected $table = 'locations_information';
    protected $primaryKey = 'id';
    protected $fillable = ['back_button_from_domain', 'back_button_image'];

    public function location()
    {
        return $this->belongsTo('App\Location', 'location_id');
    }
}
