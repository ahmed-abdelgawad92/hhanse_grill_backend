<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');

Route::get('/menu_items/{date?}',[
  'uses' => 'MenuItemsController@getMenuItems'
])->where(['date' => '[0-9]{4}-[0-9]{2}-[0-9]{2}']);

//Authenticated Routes
Route::group(['middleware' => 'jwt.auth'],function(){
  //register new user
  Route::post('/register', 'AuthController@register');
  Route::get('/unique/{uname}', 'AuthController@isUnique');
  Route::get('/all/users', 'AuthController@allUsers');
  Route::delete('/delete/user/{id}', 'AuthController@deleteUser')->where(['id'=>'[0-9]+']);
  //change password
  Route::post('/change_password', 'AuthController@changePassword');
  //get all ingredients and meals
  Route::get('/all-meals-ingredients',[
    'uses' => 'MenuItemsController@getAllMealsAndIngredients'
  ]);
  //insert a new menu item
  Route::post('/add_menu',[
    'uses' => 'MenuItemsController@addMenu'
  ]);
  //delete menu item
  Route::delete('/delete_menu_item/{id}',[
    'uses' => 'MenuItemsController@deleteMenu'
  ])->where(['id' => '[0-9]+']);
  //activate or deactivate menu items
  Route::put('/activate_menu_item/{id}',[
    'uses' => 'MenuItemsController@activateMenu'
  ])->where(['id' => '[0-9]+']);
  Route::put('/deactivate_menu_item/{id}',[
    'uses' => 'MenuItemsController@deactivateMenu'
  ])->where(['id' => '[0-9]+']);
  //get week plan
  Route::get('/weekplan/{week}',[
    'uses' => 'MenuItemsController@getWeekPlan'
  ])->where(['week' => '\-?[0-9]+']);
  //logout
  Route::post('/logout', 'AuthController@logout');
});
