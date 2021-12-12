<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Webpatser\Uuid\Uuid;

class Property extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public $incrementing = false;
    protected $guarded = [];
    protected $appends = ['property_name', 'name_en', 'name_ar', 'property_description', 'description_en',
        'description_ar', 'category_name', 'service_name', 'service_icon', 'country_name', 'city_name', 'user_name',
        'user_mobile', 'user_image', 'user_rating', 'is_favorite', 'create_date', 'is_new', 'rating', 'reviews_count',
        '360_image_link', 'mobile_country_code', 'whatsapp_country_code'];
    protected $hidden = ['id', 'name', 'description', 'created_at', 'updated_at', 'deleted_at', 'category', 'service',
        'country', 'city', 'user', 'pivot'];
    protected $translatable = ['name', 'description'];
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
    public function features(){
        return $this->belongsToMany(Feature::class, 'feature_properties', 'property_uuid', 'feature_uuid', 'uuid', 'uuid')->withTrashed();
    }
    public function favorites(){
        return $this->belongsToMany(User::class, 'favorites', 'property_uuid', 'user_uuid', 'uuid', 'uuid');
    }

    public function category(){
        return $this->belongsTo(Category::class)->withTrashed();
    }
    public function country(){
        return $this->belongsTo(Country::class)->withTrashed();
    }
    public function city(){
        return $this->belongsTo(City::class)->withTrashed();
    }
    public function service(){
        return $this->belongsTo(Service::class)->withTrashed();
    }
    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function images(){
        return $this->hasmany(ImageProperty::class);
    }
    public function reviews(){
        return $this->hasmany(Review::class);
    }
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

    public function getPropertyNameAttribute()
    {
        return @$this->name;
    }
    public function getPropertyDescriptionAttribute()
    {
        return @$this->description;
    }
    public function getCategoryNameAttribute()
    {
        return @$this->category->name;
    }
    public function getCountryNameAttribute()
    {
        return @$this->country->name;
    }
    public function getCityNameAttribute()
    {
        return @$this->city->name;
    }
    public function getServiceNameAttribute()
    {
        return @$this->service->name;
    }
    public function getServiceIconAttribute()
    {
        return @$this->service->icon;
    }
    public function getUserNameAttribute()
    {
        return @$this->user->name;
    }
    public function getUserMobileAttribute()
    {
        return @$this->user->mobile;
    }
    public function getUserImageAttribute()
    {
        return @$this->user->image ?? asset('placeholder.jpg');
    }
    public function getImageAttribute($value)
    {
        return !is_null($value) ? asset(Storage::url($value)) : '';
    }
    public function get360ImageAttribute($value)
    {
        return !is_null($value) ? asset(Storage::url($value)) : '';
    }
    public function getIsFavoriteAttribute($value)
    {
        return $this->favorites()->where('user_uuid', auth('sanctum')->id())->count() > 0;
    }
    public function getIsNewAttribute($value)
    {
        return Carbon::now()->gte($this->created_at->addDays(7)) ? false : true;
    }

    public function getCreateDateAttribute($value)
    {
        return @$this->created_at->format('d/m/Y');
    }
    public function getRatingAttribute($value)
    {
        return number_format(@$this->reviews()->avg('rating') ?? 0, 1);
    }
    public function getReviewsCountAttribute($value)
    {
        return @$this->reviews()->count();
    }
    public function getNameEnAttribute($value)
    {
        return $this->getTranslation('name', 'en');
    }
    public function getNameArAttribute($value)
    {
        return $this->getTranslation('name', 'ar');
    }
    public function getDescriptionEnAttribute($value)
    {
        return $this->getTranslation('description', 'en');
    }
    public function getDescriptionArAttribute($value)
    {
        return $this->getTranslation('description', 'ar');
    }
    public function getUserRatingAttribute($value)
    {
        return @$this->user->rating;
    }
    public function get360ImageLinkAttribute($value)
    {
        return $this->getAttribute('360_image') ? url('/360_image/'.$this->uuid) : '';
    }
    public function getMobileAttribute($value)
    {
        $value = $value ?? '';
        if (!str_contains($value, '-')) {
            return $value;
        }
        return explode('-', $value)[1];
    }
    public function getMobileCountryCodeAttribute()
    {
        $value = $this->getRawOriginal('mobile') ?? '';
        if (!str_contains($value, '-')) {
            return '';
        }
        return explode('-', $value)[0];
    }
    public function getWhatsappAttribute($value)
    {
        $value = $value ?? '';
        if (!str_contains($value, '-')) {
            return $value;
        }
        return explode('-', $value)[1];
    }
    public function getWhatsappCountryCodeAttribute()
    {
        $value = $this->getRawOriginal('whatsapp') ?? '';
        if (!str_contains($value, '-')) {
            return '';
        }
        return explode('-', $value)[0];
    }


}
