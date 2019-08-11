<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;
class Cities extends Model
{
    use LogsActivity;
    use HasTranslations;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cities';

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
    protected $translatable = ['name'];
    protected $fillable = ['name', 'lat', 'lng', 'is_default', 'position'];



    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
     protected function asJson($value)
      {
          return json_encode($value, JSON_UNESCAPED_UNICODE);
      }
    public function getDescriptionForEvent($eventName)
    {
        return __CLASS__ . " model has been {$eventName}";
    }
}
