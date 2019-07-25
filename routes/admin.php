<?php
Route::group(['middleware' => 'auth'], function() {
//Конвертор мультиязычности
Route::get('/admin/convert/locations', 'Admin\\LocationsController@convert');
Route::get('/admin/convert/city', 'Admin\\LocationsController@convertcity');
Route::get('/admin/convert/categories', 'Admin\\LocationsController@convertcategories');
Route::get('/admin/convert/floors', 'Admin\\LocationsController@convertfloors');


Route::get('/admin/krpano/{id}', 'Admin\\LocationsController@krpano');
Route::get('admin', 'Admin\AdminController@index');


Route::resource('admin/roles', 'Admin\RolesController');
    Route::resource('admin/permissions', 'Admin\PermissionsController');
    Route::resource('admin/users', 'Admin\UsersController');
    Route::resource('admin/pages', 'Admin\PagesController');
    Route::resource('admin/activitylogs', 'Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);
    Route::resource('admin/settings', 'Admin\SettingsController');
    Route::get('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);

    Route::resource('admin/locations', 'Admin\\LocationsController');

Route::resource('admin/sky', 'Admin\\SkyController');


Route::get('admin/floors/{id}', 'Admin\\FloorsController@index');
Route::get('admin/floors/create/{id}', 'Admin\\FloorsController@create');
Route::post('admin/floors/store/', 'Admin\\FloorsController@store');
Route::get('admin/floors/edit/{id}', 'Admin\\FloorsController@edit');
Route::match(['put', 'patch'],'admin/floors/update/{id}', 'Admin\\FloorsController@update');
Route::delete('admin/floors/delete/{id}', 'Admin\\FloorsController@destroy');
Route::get('admin/floors/tochki/{id}', 'Admin\\FloorsController@tochki');
Route::post('admin/floors/tochki/{id}', 'Admin\\FloorsController@tochkiupdate');


Route::get('admin/podloc/{id}', 'Admin\\PodlocController@index');
Route::get('admin/podloc/edit/{id}', 'Admin\\PodlocController@edit');
Route::match(['put', 'patch'],'admin/podloc/update/{id}', 'Admin\\PodlocController@update');
Route::get('admin/podloc/create/{id}', 'Admin\\PodlocController@create');
Route::post('admin/podloc/store/', 'Admin\\PodlocController@store');

    Route::group(['prefix' => 'api'], function() {

Route::get('/deletehotspot/{id}', 'Admin\\HotspotsController@deletehotspot');
Route::get('/locations/{id}', 'Admin\\LocationsController@apiLocations');
Route::get('/getcitydefaultlocation/{id}', 'Admin\\LocationsController@getcitydefaultlocation');
       Route::post('/locations/add', 'Admin\\LocationsController@apiAddhotspot');





       Route::resource('admin/categories', 'Admin\\CategoriesController');

       Route::resource('admin/cities', 'Admin\\CitiesController');
       Route::resource('admin/hotspots', 'ADmin\\HotspotsController');


    });
});

?>
