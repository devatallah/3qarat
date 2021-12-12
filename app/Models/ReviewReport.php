<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class ReviewReport extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public $incrementing = false;
    protected $guarded = [];
    protected $appends = ['user_name', 'user_mobile', 'user_image', 'review_comment', 'review_rating',
        'review_user_name', 'review_user_mobile', 'review_user_image', 'property_name', 'property_code', 'create_date'];
    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at', 'user', 'property'];
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

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function review(){
        return $this->belongsTo(Review::class)->withTrashed();
    }
/*    public function property(){
        return $this->hasManyThrough(Property::class, Review::class);
    }*/
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
        return @$this->user->image;
    }
    public function getReviewUserNameAttribute()
    {
        return @$this->review->user->name;
    }
    public function getReviewUserMobileAttribute()
    {
        return @$this->review->user->mobile;
    }
    public function getReviewCommentAttribute()
    {
        return @$this->review->comment;
    }
    public function getReviewRatingAttribute()
    {
        return @$this->review->rating;
    }
    public function getReviewUserImageAttribute()
    {
        return @$this->review->user->image;
    }
    public function getPropertyNameAttribute()
    {
        return @$this->review->property_name;
    }
    public function getPropertyCodeAttribute()
    {
        return @$this->review->property_code;
    }

    public function getCreateDateAttribute($value)
    {
        return @$this->created_at->format('Y-m-d H:i');
    }

}
