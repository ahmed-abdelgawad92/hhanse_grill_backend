<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\PagePhoto;

/**
 *
 */
class PagePhotoRepository
{
   //get all photos
   public function getAll()
   {
      return PagePhoto::all();
   }
   //upload a photo
   public function uploadPhoto($req)
   {
      $photo = new PagePhoto;
      $photo->photo = $req['photo']->store('public');
      $saved = $photo->save();
      //check if saved correctly
      if (!$saved) {
         return response()->json(['error' => 'Etwas ist schief gelaufen beim Server, bitte versuchen Sie noch einmal'], 410);
      }
      return $photo;
   }

   //delete a photo 
   public function delete($id)
   {
      $photo = PagePhoto::findOrFail($id);
      Storage::disk('public')->delete($photo->photo);
      $deleted = $photo->delete();
      if (!$deleted) {
         return response()->json(['error' => 'Etwas ist schief gelaufen beim Server, bitte versuchen Sie noch einmal'], 410);
      }
      return true;
   }
}