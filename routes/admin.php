<?php
Route::group(['middleware' => 'auth'], function() {
//Конвертор мультиязычности
Route::get('/admin/convert/locations', 'Admin\\LocationsController@convert');
Route::get('/admin/convert/city', 'Admin\\LocationsController@convertcity');
Route::get('/admin/convert/categories', 'Admin\\LocationsController@convertcategories');
Route::get('/admin/convert/floors', 'Admin\\LocationsController@convertfloors');

//Категории
Route::get('admin/categories/{id}/edit/{lang}', [
    'uses' => 'Admin\\CategoriesController@edit'
]);
Route::match(['put', 'patch'],'admin/categories/{id}/{language}', 'Admin\\CategoriesController@update');
Route::resource('admin/categories', 'Admin\\CategoriesController');

//Локации
Route::get('admin/locations/{id}/edit/{lang}', [
    'uses' => 'Admin\\LocationsController@edit'
]);
Route::match(['put', 'patch'],'admin/locations/{id}/{language}', 'Admin\\LocationsController@update');
Route::get('admin/locations/main', 'Admin\LocationsController@main');
Route::get('admin/locations/unpublished', 'Admin\LocationsController@unpublished');
Route::get('admin/locations/featured', 'Admin\LocationsController@featured');
Route::get('admin/locations/hub', 'Admin\LocationsController@hub');
Route::resource('admin/locations', 'Admin\\LocationsController')->except(['show']);
Route::get('admin/locations/{lang}/{id}', 'Admin\LocationsController@show')->name('admin.locations.show');
Route::get('admin/locations/{id}/video', 'Admin\LocationsController@showVideo')->name('admin.locations.video');

//Небо
Route::get('admin/sky/{id}/edit/{lang}', [
    'uses' => 'Admin\\SkyController@edit'
]);
Route::match(['put', 'patch'],'admin/sky/{id}/{language}', 'Admin\\SkyController@update');
Route::resource('admin/sky', 'Admin\\SkyController');

//Город
Route::get('admin/cities/{id}/edit/{lang}', [
    'uses' => 'Admin\\CitiesController@edit'
]);
Route::match(['put', 'patch'],'admin/cities/{id}/{language}', 'Admin\\CitiesController@update');
Route::resource('admin/cities', 'Admin\\CitiesController');

//Теги
Route::get('admin/tags/{id}/edit/{lang}', [
    'uses' => 'Admin\\TagsController@edit'
]);
Route::match(['put', 'patch'],'admin/tags/{id}/{language}', 'Admin\\TagsController@update');
Route::resource('admin/tags', 'Admin\\TagsController');

//Видео
Route::get('admin/videos/{id}', [
    'uses' => 'Admin\\VideosController@index'
])->name('admin.videos');
Route::resource('admin/videos', 'Admin\\VideosController')->except(['edit']);
Route::get('admin/videos/{id}/edit/{lang}', 'Admin\\VideosController@edit');

//Подлокации
Route::get('admin/podloc/{id}', 'Admin\\PodlocController@index');
Route::get('admin/podloc/edit/{id}/{lang}', 'Admin\\PodlocController@edit');
Route::match(['put', 'patch'],'admin/podloc/update/{id}/{lang}', 'Admin\\PodlocController@update');
Route::get('admin/podloc/create/{id}', 'Admin\\PodlocController@create');
Route::post('admin/podloc/store/', 'Admin\\PodlocController@store');
Route::get('/sublocations/{id}', 'Admin\\LocationsController@apiSublocations');
Route::post('admin/podloc/show-sublocation/{id}', 'Admin\\PodlocController@showSublocation');

//Этажи
Route::get('admin/floors/{id}', 'Admin\\FloorsController@index');
Route::get('admin/floors/create/{id}', 'Admin\\FloorsController@create');
Route::post('admin/floors/store/', 'Admin\\FloorsController@store');
Route::get('admin/floors/edit/{id}/{lang}', 'Admin\\FloorsController@edit');
Route::match(['put', 'patch'],'admin/floors/update/{id}/{lang}', 'Admin\\FloorsController@update');
Route::delete('admin/floors/delete/{id}', 'Admin\\FloorsController@destroy');
Route::get('admin/floors/tochki/{id}', 'Admin\\FloorsController@tochki');
Route::post('admin/floors/tochki/{id}', 'Admin\\FloorsController@tochkiupdate');

Route::get('/admin/krpano/{id}', 'Admin\\LocationsController@krpano');
Route::get('admin', 'Admin\AdminController@index');
Route::resource('admin/roles', 'Admin\RolesController')->middleware('admin');
Route::resource('admin/permissions', 'Admin\PermissionsController')->middleware('admin');
Route::resource('admin/users', 'Admin\UsersController')->middleware('admin');
Route::resource('admin/pages', 'Admin\PagesController');
Route::resource('admin/activitylogs', 'Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);
Route::resource('admin/settings', 'Admin\SettingsController');
Route::get('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
Route::post('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);

Route::post('admin/ckeditor/upload', 'Admin\CkeditorController@upload')->name('ckeditor.upload');
});

?>
