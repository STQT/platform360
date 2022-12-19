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

Route::get('/video-script', function() {
    $hotspots = \Illuminate\Support\Facades\DB::table('videos')->select(['id','video', 'hfov', 'yaw', 'pitch', 'roll'])
        ->get()->toArray();
    $strings = [];
    foreach ($hotspots as $key => $hotspot) {
        if (!isJson2($hotspot->video)) {
            $strings[$key]['video'] = $hotspot->video;
        }
        if (!isJson2($hotspot->hfov)) {
            $strings[$key]['hfov'] = $hotspot->hfov;
        }
        if (!isJson2($hotspot->yaw)) {
            $strings[$key]['yaw'] = $hotspot->yaw;
        }
        if (!isJson2($hotspot->pitch)) {
            $strings[$key]['pitch'] = $hotspot->pitch;
        }
        if (!isJson2($hotspot->roll)) {
            $strings[$key]['roll'] = $hotspot->roll;
        }

        if (
            isset($strings[$key]) && array_key_exists('video',$strings[$key]) ||
            isset($strings[$key]) && array_key_exists('hfov',$strings[$key])||
            isset($strings[$key]) && array_key_exists('yaw',$strings[$key])||
            isset($strings[$key]) && array_key_exists('pitch',$strings[$key])||
            isset($strings[$key]) && array_key_exists('roll',$strings[$key])
        ) {
            $strings[$key]['id']  = $hotspot->id;
            foreach ($strings[$key] as $key => $string) {

                if ($key == 'video' || $key == 'hfov'|| $key == 'yaw'|| $key == 'pitch'|| $key == 'roll') {
                    \Illuminate\Support\Facades\DB::table('videos')
                        ->where('id', $hotspot->id)
                        ->update([$key => json_encode(['ru' =>$string])]);
                }
            }
        }


    }
    return 'sucess';
});
function isJson2($str) {
    $json = json_decode($str);
    return $json && $str != $json;
}
Route::get('/script', function() {
    $hotspots = \Illuminate\Support\Facades\DB::table('hotspots')->select(['information','destination_id', 'image','type','id'])
        ->where('type', 2)->get();
    $strings = [];
    foreach ($hotspots as $key => $hotspot) {

        if (!isJson2($hotspot->information)) {
            $strings[$key]['information'] = $hotspot->information;
        }
        if (!isJson2($hotspot->image)) {
            $strings[$key]['image'] = $hotspot->image;

        }
        if ( isset($strings[$key]) && array_key_exists('information',$strings[$key]) ||
            isset($strings[$key]) && array_key_exists('image',$strings[$key])) {
            $strings[$key]['id']  = $hotspot->id;
            $strings[$key]['type']  = $hotspot->type;
            $strings[$key]['destination_id']  = $hotspot->destination_id;

            foreach ($strings[$key] as $key => $string) {

                if ($key == 'image' || $key == 'information') {
                    \Illuminate\Support\Facades\DB::table('hotspots')
                        ->where('id', $hotspot->id)
                        ->update([$key => json_encode(['ru' =>$string])]);
                }
            }
        }


    }
    return 'sucess';
});

function isJson($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}
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
Route::get('/help', 'PagesController@help');

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
Route::get('/ajax-modal', 'HomeController@ajaxModal');

Route::group(['prefix' => 'api'], function() {
    Route::get('/location/{id}', 'Admin\\LocationsController@show2');
    Route::get('/hotspots/{id}', 'Admin\LocationsController@apiHotspots');
});

Route::group(['middleware' => 'auth'], function() {
    Route::group(['prefix' => 'api'], function() {
        Route::get('/locations/{id}', 'Admin\\LocationsController@apiLocations');
        Route::get('/sublocations/{id}', 'Admin\LocationsController@apiSublocations');
        Route::post('/locations/add', 'Admin\\LocationsController@apiAddhotspot');
        Route::post('/locations/updatehlookat', 'Admin\\LocationsController@updateHlookat');
        Route::post('/locations/add-information/{lang}', 'Admin\\LocationsController@apiAddInformationhotspot');
        Route::post('/locations/upload-video/{lang}', 'Admin\\LocationsController@uploadVideo');
        Route::post('/locations/add-polygon', 'Admin\\LocationsController@apiAddPolygonhotspot');
        Route::get('/deletehotspot/{id}', 'Admin\\HotspotsController@deletehotspot');
        Route::get('/deleteinformation/{id}', 'Admin\\HotspotsController@deleteinformation');
        Route::get('/getcitydefaultlocation/{id}', 'Admin\\LocationsController@getcitydefaultlocation');
        Route::resource('admin/hotspots', 'ADmin\\HotspotsController');
    });
});

Route::get('/sitemap.xml', 'SitemapController@sitemap');

