<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientPhotoUpload;
use App\Repositories\PagePhotoRepository;

class ClientPageController extends Controller
{
    protected $clientPhoto;
    public function __construct(PagePhotoRepository $pagePhoto)
    {
        $this->clientPhoto = $pagePhoto;
    }
    public function uploadPhoto(ClientPhotoUpload $req)
    {
        if ($this->clientPhoto->uploadPhoto($req)) {
            return response()->json(['success' => 'Das Foto ist erfolgreich hochgelagden'], 200);
        }
        return response()->json(['error' => 'Etwas ist schief gelaufen beim Server'], 410);
    }

    public function getPhotos()
    {
        $photos = $this->clientPhoto->getAll();
        return response()->json(['photos' => $photos],200);
    }

    public function delete($id)
    {
        if($this->clientPhoto->delete($id)){
            return response()->json(['success'=>'Das Foto ist erfolgreich gelöscht']);
        }
    }

}
