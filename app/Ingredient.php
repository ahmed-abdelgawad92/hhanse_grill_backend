<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    // 1 to M with MenuItem
    public function menu_item()
    {
      return $this->belongsTo('App\MenuItem');
    }
}
