<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    const PLAY_TYPE_SOUND_ICON = 0;
    const PLAY_TYPE_MANUALLY = 1;
    const PLAY_TYPE_AUTOMATICALLY = 2;

    protected $fillable = ['location_id', 'video', 'hfov', 'yaw', 'pitch', 'roll', 'play_type'];

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
