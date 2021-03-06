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
])->where(['date' => '[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}']);

Route::get('/karte', 'KarteController@index');
Route::get('/karte/slideshow', 'KarteController@getSlideShowItems');
Route::get('/karte/get/{category}', 'KarteController@getWithCategory');
Route::get('/client/photos', 'ClientPageController@getPhotos');
//get week plan
Route::get('/weekplan/{week}',[
  'uses' => 'MenuItemsController@getWeekPlan'
])->where(['week' => '\-?[0-9]+']);

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
  //edit menu item
  Route::put('/edit_menu/{id}', 'MenuItemsController@editMenu')->where(['id' => '[0-9]+']);
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
  //logout
  Route::post('/logout', 'AuthController@logout');

  /*
  *
  * Routes for Karte manipulation
  *
  */
  Route::prefix('/karte')->group(function(){
    //add karte item
    Route::post('/add', 'KarteController@addItem');
    //edit karte item
    Route::put('/edit/{id}', 'KarteController@editItem')->where(['id' => '[0-9]+']);
    //upload a new photo for a karte item
    Route::post('/upload_photo/{id}', 'KarteController@uploadPhoto')->where(['id' => '[0-9]+']);
    //delete karte item
    Route::delete('/delete/{id}', 'KarteController@deleteItem')->where(['id' => '[0-9]+']);
  });

  /*
  *
  * Routes for slideshow manipulation
  *
  */
  Route::prefix('/slideshow')->group(function(){
    //add Slideshow item
    Route::post('/create', 'SlideshowController@store');
    //edit Slideshow item
    Route::put('/edit/{id}', 'SlideshowController@update')->where(['id' => '[0-9]+']);
    //fetch all Slideshow
    Route::get('/fetch', 'SlideshowController@index');
    //delete Slideshow item
    Route::delete('/delete/{id}', 'SlideshowController@destroy')->where(['id' => '[0-9]+']);
  });

  /**
   * 
   * Routes for Client Page
   * 
   */
  Route::prefix('/client')->group(function(){
    Route::post('/photo/upload','ClientPageController@uploadPhoto');
    Route::delete('/photo/delete/{id}','ClientPageController@delete')->where(['id' => '[0-9]+']);
  });
});
