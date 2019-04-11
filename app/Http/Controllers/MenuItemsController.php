<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\MenuItem;
use App\Meal;
use App\Ingredient;
class MenuItemsController extends Controller
{
    public $days = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag'];
    //get items of a specific date
    public function getMenuItems($date = null)
    {
      if($date===null){
        $date = date('Y-m-d', time());
      }
      $items = MenuItem::with('meal','ingredients')->where('date',$date)->orderBy('row_order','asc')->get();

      return response()->json($items);
    }

    //get all meals and ingredients
    public function getAllMealsAndIngredients(){
      $ingredients = Ingredient::select('ingredient')->distinct()->get();
      $meals = Meal::all();
      $data = [
        'meals' => $meals,
        'ingredients' => $ingredients
      ];
      return response()->json($data);
    }

    public function addMenu(Request $request)
    {
      $req = $request->json()->all();
      $rules = [
        'price' => 'required|numeric',
        'order' => 'required|numeric|between:1,4',
        'meal' => 'required',
        'date' => 'date'
      ];
      $validator = Validator::make($req,$rules);
      if ($validator->fails()) {
       	return response()->json(['errors'=>$validator->errors()], 422);
      }
      $checkIfMenuNumberExists = MenuItem::whereDate('date', $req['date'])->where('row_order',$req['order'])->first();
      $m = Meal::where('name',$req['meal'])->first();
      $checkIfMenuExists = MenuItem::whereDate('date', $req['date'])->where('meal_id',$m->id)->first();
      if($checkIfMenuNumberExists){
        return response()->json(['error'=>'Es gibt schon ein Gericht mit der Nummer '.htmlspecialchars($req['order']).' an diesem Datum'], 422);
      }
      if($checkIfMenuExists){
        return response()->json(['error'=>'Es gibt schon das Gericht '.htmlspecialchars($req['meal']).' an diesem Datum'], 422);
      }
      try {
        $meal = Meal::firstOrCreate(['name' => mb_strtolower($req['meal'])]);
        $menu_item = new MenuItem;
        $menu_item->date = $req["date"];
        $menu_item->meal_id = $meal->id;
        $menu_item->price = $req["price"];
        $menu_item->row_order = $req["order"];
        $saved = $menu_item->save();
        $ingredient = new Ingredient;
        $ingredient->ingredient = $req['ingredients'];
        $menu_item->ingredients()->save($ingredient);
      } catch (\Exception $e) {
        return response()->json(['errors' => $e->getMessage()], 422);
      }
      return response()->json(['success' => true], 201);
    }

    public function deleteMenu($id)
    {
      $menu_item = MenuItem::find($id);
      try{
        DB::beginTransaction();
        $menu_item->ingredients()->delete();
        $menu_item->delete();
        DB::commit();
      }catch(\Exception $e){
        DB::rollBack();
        return response()->json(['errors' => $e->getMessage()], 422);
      }
      return response()->json(['success' => true], 201);
    }

    public function activateMenu($id)
    {
      $menu_item = MenuItem::find($id);
      $menu_item->available = 1;
      $saved = $menu_item->save();
      //check if saved correctly
      if (!$saved) {
        return response()->json(['error'=>true],422);
      }
      return response()->json(['success'=>true],201);
    }

    public function deactivateMenu($id)
    {
      $menu_item = MenuItem::find($id);
      $menu_item->available = 0;
      $saved = $menu_item->save();
      //check if saved correctly
      if (!$saved) {
        return response()->json(['error'=>true],422);
      }
      return response()->json(['success'=>true],201);
    }

    public function getWeekPlan($week)
    {
      $day = date('N');
      if($day == 1){
        if($week > 0){
          $weekStart = date('Y-m-d', strtotime('+'.$week.' Week'));
        }elseif($week < 0){
          $weekStart = date('Y-m-d', strtotime($week.' Week'));
        }else{
          $weekStart = date('Y-m-d');
        }
      }else{
        if($week > 0){
          $weekStart = date('Y-m-d', strtotime('Last Monday +'.$week.' Week'));
        }elseif($week < 0){
          $weekStart = date('Y-m-d', strtotime('Last Monday '.$week.' Week'));
        }else{
          $weekStart = date('Y-m-d', strtotime('Last Monday'));
        }
      }
      $weekPlan = [];
      $startDate = $weekStart;
      for($i=0; $i<5; $i++){
        $weekPlan[$i]['meals'] = MenuItem::with('meal','ingredients')
            ->where('date',$weekStart)
            ->orderBy('row_order','asc')
            ->get();
        $weekPlan[$i]['day'] = $this->days[$i];
        $weekPlan[$i]['date'] = $weekStart;
        if($i == 4) continue;
        $weekStart = date('Y-m-d', strtotime($weekStart.' +1 day'));
      }
      $data = [
        'weekPlan' => $weekPlan,
        'startDate' => $startDate,
        'endDate' => $weekStart
      ];
      return response()->json($data, 200);
    }
}
