<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Http\Request;
class Location extends Model
{
    use LogsActivity;


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
    protected $fillable = ['name', 'address', 'number',  'description', 'working_hours', 'website', 'facebook', 'instagram', 'telegram', 'panorama', 'category_id', 'floors', 'isFloor', 'isDefault', 'slug', 'isfeatured', 'city_id', 'lat', 'lng', 'onmap', 'xmllocation', 'sky_id', 'subdomain'];


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

    public  function folderName()
    {
        $test = json_decode($this->panorama)[0]->panoramas[0]->panorama;

        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
        foreach ($old as $item){
          if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)){
              $filename = $test . '/' . $item;
          }
        }


        return $filename;
    }
    public static function xmlName($xml)
    {
        $test = $xml;

        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);

        foreach ($old as $item){
          if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)){
              $filename = $test . '/' . $item;
          }
        }

        return $filename;
    }
public static  function folderNames($loc)
    {

        foreach($loc as $key2=>$value2){
         $test = json_decode($loc[$key2]->panorama)[0]->panoramas[0]->panorama;

        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
        foreach ($old as $item){
          if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)){
              $filename[$key2] = $test . '/' . $item;
          }
        }


}

        return $filename;
    }
     public static function setCookie($name, $value){


        setcookie($name,$value, time() + (86400 * 30), "/");


   }

    public static function transliterate ($string){
        $str = mb_strtolower($string, 'UTF-8');

        $leter_array = array(
            'a' => 'а',
            'b' => 'б',
            'v' => 'в',
            'g' => 'г',
            'd' => 'д',
            'e' => 'е,э',
            'jo' => 'ё',
            'zh' => 'ж',
            'z' => 'з',
            'i' => 'и,i',
            'j' => 'й',
            'k' => 'к',
            'l' => 'л',
            'm' => 'м',
            'n' => 'н',
            'o' => 'о',
            'p' => 'п',
            'r' => 'р',
            's' => 'с',
            't' => 'т',
            'u' => 'у',
            'f' => 'ф',
            'kh' => 'х',
            'ts' => 'ц',
            'ch' => 'ч',
            'sh' => 'ш',
            'shch' => 'щ',
            '' => 'ъ',
            'y' => 'ы',
            '' => 'ь',
            'yu' => 'ю',
            'ya' => 'я',
        );

        foreach ($leter_array as $leter => $kyr){
            $kyr = explode(',',$kyr);

            $str = str_replace($kyr, $leter, $str);
        }

        $str = preg_replace('/(\s|[^A-Za-z0-9-])+/', '-', $str);
        $str = trim($str,'-');

        return $str;
    }
    public function hotspots()
    {
        return $this->hasMany('App\Hotspot', 'location_id');
    }
    public function locscats()
    {
        return $this->hasMany('App\Category', 'id', 'category_id');
    }
    public function sublocations()
    {
        return $this->hasMany('App\Location', 'podlocparent_id');
    }
    public function etaji()
    {
        return $this->hasMany('App\Floors', 'parrentid');
    }

}
