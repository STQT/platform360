<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Video extends Model
{
    use HasTranslations;

    const PLAY_TYPE_SOUND_ICON = 0;
    const PLAY_TYPE_MANUALLY = 1;
    const PLAY_TYPE_AUTOMATICALLY = 2;

    protected $translatable = ['video', 'hfov', 'yaw', 'pitch', 'roll'];

    protected $fillable = ['location_id', 'video', 'hfov', 'yaw', 'pitch', 'roll', 'play_type'];

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

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public static function playTypeOptions()
    {
        return [
            self::PLAY_TYPE_SOUND_ICON => 'Иконка звука',
            self::PLAY_TYPE_MANUALLY => 'Вручную, по клику',
            self::PLAY_TYPE_AUTOMATICALLY => 'Автоматически',
        ];
    }
}
