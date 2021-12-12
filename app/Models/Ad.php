<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Webpatser\Uuid\Uuid;

class Ad extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public $incrementing = false;
    protected $guarded = [];
    protected $appends = ['ad_text', 'place_text'];
    protected $hidden = ['id', 'text', 'created_at', 'updated_at', 'deleted_at'];
    protected $translatable = ['text'];
    protected $primaryKey = 'uuid';


    /*
    |--------------------------------------------------------------------------
    | BOOTS
    |--------------------------------------------------------------------------
    */

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });

    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getRouteKeyName()
    {
        return 'uuid';
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function getAdTextAttribute($value)
    {
        return @$this->text;
    }
    public function getPlaceTextAttribute($value)
    {
        $text = @['main' => 'main','favorite' => 'favorite','recent_added' => 'recent_added','nearby' => 'nearby','property_details' => 'property_details'][@$this->place];
        return __($text);
    }
    public function getImageAttribute($value)
    {
        return !is_null($value) ? asset(Storage::url($value)) : '';
    }

}
