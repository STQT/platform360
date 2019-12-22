<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{home?}', 'HomeController@getIndex');

Route::get('lang/{locale}', 'HomeController@changelanguage');
Route::get('/clear', function() {
    Artisan::call('view:clear');
    Artisan::call('optimize');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('key:generate');

    return "Cleareds2";
});

Route::get('/scene/{id}', 'HomeController@loadScene');
Route::get('/city/{id}', 'HomeController@changeCity');
Route::get('/location/{slug}', 'Admin\\LocationsController@generatePano');
Route::get('/search/{search}/{categories}', 'Admin\\LocationsController@search');
Route::post('/form/{id}', 'HomeController@formProcessing');
Route::get('/hasFloors/{id}', 'Admin\\LocationsController@hasFloors');
Route::get('/getDirectories/{id}', 'Admin\\LocationsController@getDirectory');
Auth::routes();
Route::get('/krpano/{index}/{id}', 'HomeController@krpano');
Route::post('/savescreenshot', 'HomeController@savescreenshot');

Route::group(['prefix' => 'api'], function() {
    Route::get('/location/{id}', 'Admin\\LocationsController@show2');
    Route::get('/hotspots/{id}', 'Admin\LocationsController@apiHotspots');
});


Route::group(['middleware' => 'auth'], function() {
    Route::group(['prefix' => 'api'], function() {
        Route::get('/locations/{id}', 'Admin\\LocationsController@apiLocations');
        Route::get('/sublocations/{id}', 'Admin\LocationsController@apiSublocations');
        Route::post('/locations/add', 'Admin\\LocationsController@apiAddhotspot');
        Route::post('/locations/upload-video', 'Admin\\LocationsController@uploadVideo');
        Route::get('/deletehotspot/{id}', 'Admin\\HotspotsController@deletehotspot');
        Route::get('/getcitydefaultlocation/{id}', 'Admin\\LocationsController@getcitydefaultlocation');
        Route::resource('admin/hotspots', 'ADmin\\HotspotsController');
    });
});
