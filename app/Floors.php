<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;
class Floors extends Model
{
    use LogsActivity;
    use HasTranslations;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'floors';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $appends = ['width', 'height'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
     protected $translatable = ['name'];
    protected $fillable = ['parrentid', 'name', 'image', 'code'];
    protected function asJson($value)
     {
         return json_encode($value, JSON_UNESCAPED_UNICODE);
     }
    public function location()
    {
        return $this->belongsTo('App\Location', 'parrentid', 'id');
    }

    public function getWidthAttribute()
    {
        return getimagesize('storage/floors/' . $this->image)[0];
    }

    public function getHeightAttribute()
    {
        return getimagesize('storage/floors/' . $this->image)[1];
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
