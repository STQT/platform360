<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Meta extends Model
{
    use HasTranslations;

    protected $table = 'meta';
    protected $primaryKey = 'id';
    protected $translatable = ['title', 'keywords', 'description'];
    protected $fillable = ['title', 'keywords', 'description'];

    public function location()
    {
        return $this->belongsTo('App\Location','meta_id');
    }
}