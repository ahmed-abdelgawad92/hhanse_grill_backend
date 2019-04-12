<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karte extends Model
{
    //
    protected $table = "karte";

    //query scope for only items that has a photo
    public function scopeHasPhoto($query)
    {
      return $query->where('photo', '!=', null);
    }
    //query scope get the items based on the category
    public function scopeCategory($query, $value)
    {
      return $query->where('category',$value)->orderBy('number','asc');
    }
}
