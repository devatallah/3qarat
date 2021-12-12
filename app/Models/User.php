<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'email_verified_at',
        'country',
        'city'
    ];
    protected $appends = [
        'country_name',
        'city_name',
        'is_agent_text',
        'can_comment',
        'can_comment_text',
        'rating',
        'need_password',
        'mobile_country_code',
        'whatsapp_country_code'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }

    public function favorites(){
        return $this->belongsToMany(Property::class, 'favorites', 'user_uuid', 'property_uuid', 'uuid', 'uuid');
    }
    public function notifications(){
        return $this->belongsToMany(Notification::class, 'notification_users', 'user_uuid', 'notification_uuid', 'uuid', 'uuid');
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
    public function getCountryNameAttribute()
    {
        return @$this->country->name;
    }
    public function getAboutAttribute($value)
    {
        return $value ?? '';
    }
    public function getCityNameAttribute()
    {
        return @$this->city->name;
    }
    public function getImageAttribute($value)
    {
        return !is_null($value) ? asset(Storage::url($value)) : '';
    }
    public function getIsAgentAttribute($value)
    {
        return $value ? true : false ;
    }
    public function getIsAgentTextAttribute($value)
    {
        $text = @[true => 'true',false => 'false'][@$this->is_agent];
        return __($text);
    }
    public function getCanCommentAttribute($value)
    {
        return $value ? true : false ;
    }
    public function getCanCommentTextAttribute($value)
    {
        $text = @[true => 'true',false => 'false'][@$this->can_comment];
        return __($text);
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
    public function getRatingAttribute($value)
    {
        $avg = Review::query()->whereHas('property', function ($q){
            $q->where('user_uuid', $this->uuid);
        })->avg('rating');
        return number_format($avg ?? 0, 1);
    }
    public function getNeedPasswordAttribute($value)
    {
        return !is_null($this->password);
    }

}
