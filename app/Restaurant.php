<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'description',
        'email',
        'p_iva',
        'sponsored',
        'photo',
        'user_id'
    ];

    public function categories() {
        return $this->belongsToMany('App\Category', 'category_restaurant');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
    
    public function plates() {
        return $this->hasMany('App\Plate');
    }
    
     public function orders() {
            return $this->hasMany('App\Order');
    }
    
}
