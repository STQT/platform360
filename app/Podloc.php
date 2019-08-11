<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;
use Illuminate\Http\Request;
class Podloc extends Model
{
    use LogsActivity;
use HasTranslations;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'locations';

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
     protected $translatable = ['name', 'address', 'description','working_hours'];
    protected $fillable = ['name', 'sky_id', 'address', 'podlocparent_id', 'number',  'description', 'working_hours', 'website', 'facebook', 'instagram', 'telegram', 'panorama', 'category_id', 'slug', 'isfeatured', 'city_id', 'lat', 'lng', 'onmap', 'xmllocation', 'published'];



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
    public function folderName()
    {
        $test = json_decode($this->panorama)[0]->panoramas[0]->panorama;

        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);

        $filename = $test . '/' . $old[2];

        return $filename;
    }
public static  function folderNames($loc)
    {

        foreach($loc as $key2=>$value2){
         $test = json_decode($loc[$key2]->panorama)[0]->panoramas[0]->panorama;

        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);

        $filename[$key2] = $test . '/' . $old[2];

}}
protected function asJson($value)
 {
     return json_encode($value, JSON_UNESCAPED_UNICODE);
 }
    public function hotspots()
    {
        return $this->hasMany('App\Hotspot', 'sky_id');
    }
}
