<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;
use Illuminate\Http\Request;

class Location extends Model
{
    use LogsActivity;
    use HasTranslations;

    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_LINK = 2;

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
    protected $translatable = ['name', 'address', 'description', 'working_hours'];
    protected $fillable = [
        'name',
        'address',
        'number',
        'description',
        'working_hours',
        'website',
        'facebook',
        'instagram',
        'telegram',
        'panorama',
        'category_id',
        'floors',
        'isFloor',
        'isDefault',
        'slug',
        'isfeatured',
        'city_id',
        'lat',
        'lng',
        'onmap',
        'xmllocation',
        'sky_id',
        'subdomain',
        'published',
        'visibility',
        'show_sublocation',
        'seo_title',
        'audio',
        'order',
        'video',
        'preview',
    ];

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

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function folderName()
    {
        $filename = '';
        if (!empty(json_decode($this->panorama))) {
            $test = json_decode($this->panorama)[0]->panoramas[0]->panorama;

            $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
            foreach ($old as $item) {
                if (is_dir(public_path() . '/storage/panoramas/unpacked/' . $test . '/' . $item)) {

                    $filename = $test . '/' . $item;
                }
            }
        }

        return $filename;
    }

    public function getThumb()
    {
        return '/storage/panoramas/unpacked/' . $this->folderName() . '/thumb.jpg';
    }

    public function toArray22()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }

        return $attributes;
    }

    public function toArray33()
    {
        $attributes = parent::toArray();

        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }

        return $attributes;
    }

    public static function transl($massiv)
    {
        foreach ($massiv as $key => $massic) {
            $massiv[$key] = $massic->toArray22();
        }
        return $massiv;
    }

    public static function xmlName($xml)
    {
        try {
            $dirs = scandir(public_path() . '/storage/panoramas/unpacked/' . $xml);
        } catch (\Exception $e) {
            $dirs = [];
        }

        $filename = null;

        foreach ($dirs as $item) {
            if (is_dir(public_path() . '/storage/panoramas/unpacked/' . $xml . '/' . $item)) {
                $filename = $xml . '/' . $item;
            }
        }

        return $filename;
    }

    public static function folderNames($loc)
    {
        foreach ($loc as $key2 => $value2) {
            $jsonData = json_decode($loc[$key2]->panorama);
            if (!isset($jsonData[0]->panoramas)) {
                $filename[$key2] = '';
            }
            $test = json_decode($loc[$key2]->panorama);
            if (isset($test[0]->panoramas)) {
                $test = $test[0]->panoramas[0]->panorama;
            } else {
                continue;
            }
            try {
                $dirs = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
            } catch(\Exception $e) {
                $dirs = [];
            }

            foreach ($dirs as $item) {
                if (is_dir(public_path() . '/storage/panoramas/unpacked/' . $test . '/' . $item)) {
                    $filename[$key2] = $test . '/' . $item;
                }
            }
        }

        return isset($filename) ? $filename : null;
    }

    public static function setCookie($name, $value)
    {
        setcookie($name, $value, time() + (86400 * 30), "/");
    }

    public static function transliterate($string)
    {
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

        foreach ($leter_array as $leter => $kyr) {
            $kyr = explode(',', $kyr);
            $str = str_replace($kyr, $leter, $str);
        }

        $str = preg_replace('/(\s|[^A-Za-z0-9-])+/', '-', $str);
        $str = trim($str, '-');

        return $str;
    }

    public function hotspots()
    {
        return $this->hasMany('App\Hotspot', 'location_id');
    }

    public function videos()
    {
        return $this->hasMany('App\Video', 'location_id');
    }

    public function locscats()
    {
        return $this->hasMany('App\Category', 'id', 'category_id');
    }

    public function sublocations()
    {
        return $this->hasMany('App\Location', 'podlocparent_id');
    }

    public function parent()
    {
        return $this->hasOne('App\Location', 'id', 'podlocparent_id');
    }

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function categorylocation()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function locationhotspots()
    {
        return $this->hasMany('App\Hotspot', 'location_id', 'id');
    }

    public function etaji()
    {
        if ($this->parent) {
            return $this->parent->etaji();
        }
        return $this->hasMany('App\Floors', 'parrentid');
    }

    public function city()
    {
        return $this->hasOne('App\Cities', 'id', 'city_id');
    }

    public function meta()
    {
        return $this->hasOne('App\Meta', 'id', 'meta_id');
    }

    public function information()
    {
        return $this->hasOne('App\LocationInformation', 'location_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('published', 1);
        });
    }

    public function createUrl()
    {
        $baseName = request()->getSchemeAndHttpHost();
        return $baseName . '/' . \Lang::locale() . '/location/' . $this->slug;
    }

//    TODO: использовать встроенный механизм Laravel для получения опций
    public function getVisibilityOptions()
    {
        return [
            self::VISIBILITY_PUBLIC => 'Опубликовано для всех',
            self::VISIBILITY_LINK => 'Доступ по ссылке',
        ];
    }
}
