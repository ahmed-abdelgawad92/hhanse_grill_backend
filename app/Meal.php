<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = ['name'];
    //has many menu_items
    public function menuitems()
    {
      return $this->hasMany('App\MenuItem');
    }
}
