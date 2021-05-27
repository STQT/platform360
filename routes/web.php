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

Route::get('/', 'HomeController@getIndex');

Route::get('lang/{locale}', 'HomeController@changelanguage');
Route::get('/clear', function() {
    Artisan::call('view:clear');
    Artisan::call('optimize');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('key:generate');

    return "Cleareds2";
});

//разные страницы сайта
Route::get('/how-to-use', 'PagesController@help');
Route::get('/sitemap', 'PagesController@sitemap');
Route::get('/help', 'PageController@help');

Route::get('/scene/{id}', 'HomeController@loadScene');
Route::get('/city/{id}', 'HomeController@changeCity');
Route::get('/location/{slug}', 'Admin\\LocationsController@generatePano');
Route::get('/category/{category}', 'HomeController@getIndex')->name('category');
Route::get('/search/{search}/{categories}', 'Admin\\LocationsController@search');
Route::post('/form/{id}', 'HomeController@formProcessing');
Route::get('/hasFloors/{id}', 'Admin\\LocationsController@hasFloors');
Route::get('/getDirectories/{id}', 'Admin\\LocationsController@getDirectory');
Auth::routes();
Route::get('/krpano/video/{id}', 'HomeController@krpanoVideo');
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
        Route::post('/locations/add-information', 'Admin\\LocationsController@apiAddInformationhotspot');
        Route::post('/locations/upload-video', 'Admin\\LocationsController@uploadVideo');
        Route::get('/deletehotspot/{id}', 'Admin\\HotspotsController@deletehotspot');
        Route::get('/deleteinformation/{id}', 'Admin\\HotspotsController@deleteinformation');
        Route::get('/getcitydefaultlocation/{id}', 'Admin\\LocationsController@getcitydefaultlocation');
        Route::resource('admin/hotspots', 'ADmin\\HotspotsController');
    });
});

Route::get('/sitemap.xml', 'SitemapController@sitemap');