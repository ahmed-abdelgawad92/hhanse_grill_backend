<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Karte;

/**
 *
 */
class KarteRepository
{
  //create karte item
  public function create($req)
  {
      $karte = new Karte;
      $karte->number = $req['number'];
      $karte->category = $req['category'];
      $karte->meal = $req['meal'];
      $karte->ingredient = $req['ingredients'];
      $karte->price = $req['price'];
      $karte->item_order = $req['item_order'] ?? 0;
      $karte->vegie = $req['vegie'] ?? 0;
      $karte->photo = $req['photo'] ? $req['photo']->store('public') : null;
      $saved = $karte->save();
      //check if saved correctly
      if (!$saved) {
        return response()->json(['error' => 'Etwas ist schief gelaufen beim Server, bitte versuchen Sie noch einmal'],410);
      }
      return $karte;
  }
  //update karte item
  public function update($id, $req)
  {
      $karte = Karte::findOrFail($id);
      $karte->number = $req['number'];
      $karte->meal = $req['meal'];
      $karte->ingredient = $req['ingredients'];
      $karte->category = $req['category'];
      $karte->price = $req['price'];
      $karte->item_order = $req['item_order'] ?? 0;
      $karte->vegie = $req['vegie'] ?? 0;
      $saved = $karte->save();
      //check if saved correctly
      if(!$saved) {
        return response()->json(['error' => 'Etwas ist schief gelaufen beim Server, bitte versuchen Sie noch einmal'],410);
      }
      return $karte;
  }
  //delete karte item
  public function delete($id)
  {
      $karte = Karte::findOrFail($id);
      $deleted = $karte->delete();
      if(!$deleted){
        return response()->json(['error' => 'Etwas ist schief gelaufen beim Server, bitte versuchen Sie noch einmal'],410);
      }
      return true;
  }
  //upload a photo
  public function uploadPhoto($id, $req)
  {
    $karte = Karte::findOrFail($id);
    if ($karte->photo != null && Storage::disk('public')->exists($karte->photo)) {
      Storage::disk('public')->delete($karte->photo);
    }
    $karte->photo = $req['photo']->store('public');
    $saved = $karte->save();
    //check if saved correctly
    if (!$saved) {
      return response()->json(['error' => 'Etwas ist schief gelaufen beim Server, bitte versuchen Sie noch einmal'],410);
    }
    return $karte;
  }
  //get item
  public function get($id)
  {
      return Karte::findOrFail($id);
  }
  //get all categories
  public function allCategories()
  {
      return Karte::distinct()->pluck('category');
  }
  //get all
  public function all()
  {
      return Karte::all();
  }
  //get all items that has a photo
  public function getAllWithPhoto()
  {
      return Karte::hasPhoto()->get();
  }
  //get all items within a category
  public function getAllWithCategory($value)
  {
      return Karte::category($value)->orderBy('item_order','asc')->get();
  }
}
