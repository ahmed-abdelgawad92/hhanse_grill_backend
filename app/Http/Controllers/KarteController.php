<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\KarteRepository;
use App\Http\Requests\KarteCreateRequest;
use App\Http\Requests\KarteEditRequest;
use App\Http\Requests\KarteUploadRequest;

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
        $karte = [];
        $categories = $this->karte->allCategories();
        foreach ($categories as $category) {
          $karte[$category][] = $category;
          $karte[$category][] = $this->karte->getAllWithCategory($category);
        }
        return response()->json(['karte'=>$karte],200);
    }
    //get all
    public function getWithCategory($category)
    {
        $karte = $this->karte->getAllWithCategory($category);
        return response()->json(['karte'=>$karte],200);
    }

    //add item
    public function addItem(KarteCreateRequest $request)
    {
        $karte = $this->karte->create($request);
        return response()->json(['success' => 'Das Gericht ist erfolgreich zur Karte hinzugefügt'],201);
    }

    //update item
    public function editItem(KarteEditRequest $request, $id)
    {
        $req = $request->json()->all();
        $karte = $this->karte->update($id, $req);
        return response()->json(['success' => 'Das Gericht '.$karte->meal.' ist erfolgreich bearbeitet'],201);
    }

    //upload photo
    public function uploadPhoto(KarteUploadRequest $req, $id)
    {
        if($this->karte->uploadPhoto($id, $req)){
          return response()->json(['success' => 'Das Foto ist erfolgreich hochgelagden'], 200);
        }
        return response()->json(['error' => 'Etwas ist schief gelaufen beim Server'], 410);
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
