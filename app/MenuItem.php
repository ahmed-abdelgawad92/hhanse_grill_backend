<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';
    //relationship to meals 1 to M
    public function meal()
    {
      return $this->belongsTo('App\Meal');
    }
    //rela# to ingredient 1 to M
    public function ingredients()
    {
      return $this->hasMany('App\Ingredient');
    }
}
