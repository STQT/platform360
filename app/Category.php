<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use LogsActivity;
    use HasTranslations;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

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
    protected $translatable = ['name', 'slug'];
    protected $fillable = ['name', 'cat_icon', 'cat_icon_svg', 'color', 'slug'];

    public function locations()
    {
        return $this->hasMany('App\Location', 'category_id');
    }


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
    public function createUrl()
    {
        $baseName = request()->getSchemeAndHttpHost();
        return $baseName . '/' . \Lang::locale() . '/category/' . $this->slug;
    }

    public function meta()
    {
        return $this->hasOne('App\Meta', 'id', 'meta_id');
    }

    protected function getSlugSourceString(): string
    {
        $slugSourceString = collect($this->slugOptions->generateSlugFrom)
            ->map(function (string $fieldName): string {
                if (in_array($fieldName, $this->translatable)) {
                    return $this->getTranslations($fieldName, app()->getLocale())[app()->getLocale()];
                    // if your field in the database is json type
                    // return $this->getTranslations($fieldName,app()->getLocale());
                }
                return data_get($this, $fieldName, '');
            })
            ->implode($this->slugOptions->slugSeparator);


        return substr($slugSourceString, 0, $this->slugOptions->maximumLength);
    }
}
