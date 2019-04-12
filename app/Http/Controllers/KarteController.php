<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\KarteRepository;
class KarteController extends Controller
{
    protected $karte;

    public function __construct(KarteRepository $karte)
    {
        $this->karte = $karte;
    }
    //get all
    public function index()
    {
        $categories = $this->karte->allCategories();
        //return response()->json(['karte'=>$karte],200);
        return response()->json(['karte'=>$categories],200);
    }

    //add item
    public function addItem(Request $request)
    {
        $req = $request->json()->all();
        $karte = $this->karte->create($req);
        return response()->json(['success' => 'Das Gericht ist erfolgreich zur Karte hinzugefügt'],201);
    }

    //update item
    public function editItem(Request $request, $id)
    {
        $req = $request->json()->all();
        $karte = $this->karte->update($id, $req);
        return response()->json(['success' => 'Das Gericht ist erfolgreich bearbeitet'],201);
    }

    //upload photo
    public function uploadPhoto(Request $req, $id)
    {
      // code...
    }

    //get items with photos
    public function getSlideShowItems()
    {
        $karte = $this->karte->getAllWithPhoto();
        return response()->json(['karte'=>$karte],200);
    }

    ///get items with category
    public function deleteItem($id)
    {
        if($this->karte->delete($id)){
          return response()->json(['success' => 'Das Gericht ist erfolgreich aus der Karte gelöscht!'], 200);
        }
    }
}
