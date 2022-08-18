<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Hotspot extends Model
{
    use LogsActivity;

    const TYPE_MARKER = 1;
    const TYPE_INFORMATION = 2;
    const TYPE_POLYGON = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hotspots';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['location_id', 'destination_id', 'h', 'v', 'information', 'image', 'html_code', 'url'];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function mainlocation()
    {

        return $this->hasOne('App\Location', 'id', 'location_id');
    }
    public function destination_locations()
    {

        return $this->hasMany('App\Location', 'id', 'destination_id');
    }

    public function polygons()
    {

        return $this->hasMany('App\HotspotPolygon', 'hotspot_id', 'id');
    }

    public function destinationlocation()
    {

        return $this->hasOne('App\Location', 'id', 'destination_id');
    }

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName)
    {
        return __CLASS__ . " model has been {$eventName}";
    }
}
