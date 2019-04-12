<?php
namespace App\Repository;

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
      $karte->ingredient = $req['ingredient'];
      $karte->price = $req['price'];
      $karte->photo = $req['photo'];
      $saved = $karte->save()
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
      $karte->category = $req['category'];
      $karte->meal = $req['meal'];
      $karte->ingredient = $req['ingredient'];
      $karte->price = $req['price'];
      $saved = $karte->save()
      //check if saved correctly
      if (!$saved) {
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

  }
  //get item
  public function get($id)
  {
      return Karte::findOrFail($id);
  }
  //get all categories
  public function allCategories()
  {
      return Karte::select('category')->distinct()->get();
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
      return Karte::category($value)->get();
  }
}
