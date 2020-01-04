<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Cities;
use App\Hotspot;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Location;
use App\Sky;
use App\Video;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */


    //Титульная страница Локаций в админке
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $category = $request->get('category');
        $city = $request->get('city');
        $perPage = 25;

        $totalLocations = Location::withoutGlobalScope('published')->count();
        $publishedLocations = Location::count();
        $unpublishedLocations = Location::withoutGlobalScope('published')->where('published', '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword)) {
            $locations = Location::where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($query) use ($keyword, $category, $city) {
                    $query->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city)
                        $query->where('city_id', $city);
                    if ($category)
                        $query->where('category_id', $category);
                    }
                )
                ->latest()
                ->paginate($perPage);
        } else {
            $locations = Location::where(function($query) use ($category, $city, $perPage) {
                $query->where('is_sky', '!=' , 'on')
                    ->whereNull('podlocparent_id');
                if ($city)
                    $query->where('city_id', $city);
                if ($category)
                    $query->where('category_id', $category);
            })->withoutGlobalScope('published')
                ->latest()
                ->paginate($perPage);
        }
        return view('admin.locations.index', compact(
            'locations',
            'totalLocations',
            'publishedLocations',
            'unpublishedLocations',
            'categories',
            'category',
            'cities',
            'city'
        ));
    }

    public function main(Request $request)
    {
        $keyword = $request->get('search');
        $category = $request->get('category');
        $city = $request->get('city');
        $perPage = 25;

        $totalLocations = Location::withoutGlobalScope('published')->where('isDefault', '1')->count();
        $publishedLocations = Location::where('isDefault', '1')->count();
        $unpublishedLocations = Location::withoutGlobalScope('published')->where('isDefault', '1')->where('published', '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword) && $category == '') {
            $locations = Location::where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($q) use ($keyword, $city) {
                    $q->where('isDefault', '1')->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city)
                        $q->where('city_id', $city);
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } elseif ($category != '') {
            $locations = Location::
            where('category_id', $category)
                ->where('isDefault', '1')
                ->where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($q) use ($keyword, $city, $category) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city)
                        $q->where('city_id', $city);
                    if ($category)
                        $q->where('category_id', $category);
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } else {
            $locations = Location::where(function($q) use ($category, $city, $perPage) {
                    $q->where('is_sky', '!=' , 'on')
                    ->where('isDefault', '1')
                    ->whereNull('podlocparent_id');
                    if ($city)
                        $q->where('city_id', $city);
                    if ($category)
                        $q->where('category_id', $category);
            })->latest()
                ->withoutGlobalScope('published')
                ->paginate($perPage);
        }

        return view('admin.locations.index', compact(
            'locations',
            'totalLocations',
            'publishedLocations',
            'unpublishedLocations',
            'categories',
            'category',
            'cities',
            'city'
        ));
    }

//    public function hasFloors($id) {
//        if (strpos($id, ':') !== false) {
//            $tmp = explode(':', $id);
//
//            $location = Location::find($tmp[1]);
//
//            if(!empty($location)) {
//                $tmp = json_decode($location->panorama);
//
//                if(count($tmp) > 1) {
//                    return 1;
//                }
//            }
//        }
//
//        return 0;
//    }

    public function unpublished(Request $request)
    {
        $keyword = $request->get('search');
        $category = $request->get('category');
        $city = $request->get('city');
        $perPage = 25;

        $totalLocations = Location::withoutGlobalScope('published')->where('isDefault', '1')->count();
        $publishedLocations = Location::where('isDefault', '1')->count();
        $unpublishedLocations = Location::withoutGlobalScope('published')->where('isDefault', '1')->where('published', '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword) && $category == '') {
            $locations = Location::where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($q) use ($keyword, $city) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city)
                        $q->where('city_id', $city);
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } elseif ($category != '') {
            $locations = Location::
            where('category_id', $category)
                ->where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($q) use ($keyword, $city, $category) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city)
                        $q->where('city_id', $city);
                    if ($category)
                        $q->where('category_id', $category);
                })
                ->withoutGlobalScope('published')
                ->where('published', '!=', 1)
                ->latest()->paginate($perPage);
        }
        else {
            $locations = Location::where(function($q) use ($perPage, $city, $category) {
                $q->where('is_sky', '!=' , 'on')
                    ->where('published', '!=', '1')
                    ->whereNull('podlocparent_id');
                if ($city)
                    $q->where('city_id', $city);
                if ($category)
                    $q->where('category_id', $category);
            })->latest()
                ->withoutGlobalScope('published')
                ->paginate($perPage);
        }

        return view('admin.locations.index', compact(
            'locations',
            'totalLocations',
            'publishedLocations',
            'unpublishedLocations',
            'categories',
            'category',
            'cities',
            'city'
        ));
    }

    public function featured(Request $request)
    {
        $keyword = $request->get('search');
        $category = $request->get('category');
        $city = $request->get('city');
        $perPage = 25;

        $totalLocations = Location::withoutGlobalScope('published')->where('isfeatured', 'on')->count();
        $publishedLocations = Location::where('published', '=', 1)->where('isfeatured', 'on')->count();
        $unpublishedLocations = Location::withoutGlobalScope('published')->where('isfeatured', 'on')->where('published', '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword) && $category == '') {
            $locations = Location::where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($q) use ($keyword, $city) {
                    $q->where('isfeatured', 'on')->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city)
                        $q->where('city_id', $city);
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } elseif ($category != '') {
            $locations = Location::
            where('category_id', $category)
                ->where('isfeatured', 'on')
                ->where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($q) use ($keyword, $city, $category) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city)
                        $q->where('city_id', $city);
                    if ($category)
                        $q->where('category_id', $category);
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        }
        else {
            $locations = Location::where(function($q) use ($city, $category, $perPage) {
                $q->where('is_sky', '!=' , 'on')
                    ->where('isfeatured', 'on')
                    ->whereNull('podlocparent_id');
                if ($city)
                    $q->where('city_id', $city);
                if ($category)
                    $q->where('category_id', $category);
            })->latest()
                ->withoutGlobalScope('published')
                ->paginate($perPage);
        }

        return view('admin.locations.index', compact(
            'locations',
            'totalLocations',
            'publishedLocations',
            'unpublishedLocations',
            'categories',
            'category',
            'cities',
            'city'
        ));
    }

    public function hub(Request $request)
    {
        $keyword = $request->get('search');
        $category = $request->get('category');
        $city = $request->get('city');
        $perPage = 25;

        $totalLocations = Location::withoutGlobalScope('published')->whereNotNull('sky_id')->count();
        $publishedLocations = Location::whereNotNull('sky_id')->count();
        $unpublishedLocations = Location::withoutGlobalScope('published')->whereNotNull('sky_id')->where('published', '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword) && $category == '') {
            $locations = Location::where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($q) use ($keyword, $city) {
                    $q->whereNotNull('sky_id')->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city)
                        $q->where('city_id', $city);
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } elseif ($category != '') {
            $locations = Location::
            where('category_id', $category)
                ->whereNotNull('sky_id')
                ->where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($q) use ($keyword, $city, $category) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city)
                        $q->where('city_id', $city);
                    if ($category)
                        $q->where('category_id', $category);
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        }
        else {
            $locations = Location::where(function($q) use ($perPage, $city, $category) {
                $q->where('is_sky', '!=' , 'on')
                    ->whereNotNull('sky_id')
                    ->whereNull('podlocparent_id');
                if ($city)
                    $q->where('city_id', $city);
                if ($category)
                    $q->where('category_id', $category);
            })
            ->latest()
            ->withoutGlobalScope('published')->paginate($perPage);
        }

        return view('admin.locations.index', compact(
            'locations',
            'totalLocations',
            'publishedLocations',
            'unpublishedLocations',
            'categories',
            'category',
            'cities',
            'city'
        ));
    }


//Конвертор мультиязычности:Локации
    public function convert() {
        $locations = DB::table('locations')->get();
        foreach ($locations as $key => $location) {
            $locations[$key]->name = str_replace('"','\"', $locations[$key]->name);
            DB::table('locations')
                ->where('id', $location->id)
                ->update(['name' => '{"ru":"'.$locations[$key]->name.'"}', 'address' => '{"ru":"'.$locations[$key]->address.'"}','description' => '{"ru":"'.$locations[$key]->description.'"}','working_hours' => '{"ru":"'.$locations[$key]->working_hours.'"}']);
        }}

//Конвертор мультиязычности:Городов
    public function convertcity() {
        $locations = DB::table('cities')->get();
        foreach ($locations as $key => $location) {
            $locations[$key]->name = str_replace('"','\"', $locations[$key]->name);
            DB::table('cities')
                ->where('id', $location->id)
                ->update(['name' => '{"ru":"'.$locations[$key]->name.'"}']);
        }}

//Конвертор мультиязычности:Категории
    public function convertcategories() {
        $locations = DB::table('categories')->get();
        foreach ($locations as $key => $location) {
            $locations[$key]->name = str_replace('"','\"', $locations[$key]->name);
            DB::table('categories')
                ->where('id', $location->id)
                ->update(['name' => '{"ru":"'.$locations[$key]->name.'"}']);
        }}

//Конвертор мультиязычности:Этажи
    public function convertfloors() {
        $locations = DB::table('floors')->get();
        foreach ($locations as $key => $location) {
            $locations[$key]->name = str_replace('"','\"', $locations[$key]->name);
            DB::table('floors')
                ->where('id', $location->id)
                ->update(['name' => '{"ru":"'.$locations[$key]->name.'"}']);
        }}

//Функия для путей
    public function getDirectory($id) {
        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $id);
        foreach ($old as $item){
            if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$id.'/' . $item)){
                $filename = $id . '/' . $item;
            }}
        return $filename;
    }

//Поиск
    public function search(Request $search, $categories) {
        if (Cookie::has('city')) { $defaultlocation = Cookie::get('city');}
        else { $defaultlocation = "1"; Cookie::queue(Cookie::forever('city', '1'));}
        $search = request()->route('search');
        if (request()->route('search') == "noresult") {
            $categories = array_map('intval', explode(',', request()->route('categories')));
            $results = Location::where('city_id','=', $defaultlocation)
                ->whereIN('category_id', $categories)
                ->where(function($query) {
                    $query->whereNull('podlocparent_id')->orWhere('show_sublocation', 1);
                })
                ->orderBy('order', 'asc')
                ->get();

        } else {
            if (request()->route('categories') == 0)  {
                $results = Location::where('city_id','=', $defaultlocation)
                    ->where(function($query)
                    {
                        $query->whereNull('podlocparent_id')->orWhere('show_sublocation', 1);
                    })
                    ->where('name', 'LIKE', '%' . $search . '%')->get();

            }
            else {
                $categories = array_map('intval', explode(',', request()->route('categories')));
                $results = Location::where('city_id', '=', $defaultlocation)
                    ->where(function($query) {
                        $query->whereNull('podlocparent_id')->orWhere('show_sublocation', 1);
                    })
                    ->where('name', 'LIKE', '%' . $search . '%')->whereIn('category_id', $categories)->get();
            }}
        if($results->count()) {
            foreach($results as $key2=>$value2){
                $caticon = Category::where('id', $results[$key2]->category_id)->firstOrFail();
                $results[$key2]->cat_icon = $caticon->cat_icon;
                $results[$key2]->color = $caticon->color;
                $results[$key2]->cat_icon_svg = $caticon->cat_icon_svg;
            }
            $results = Location::transl($results);
            return response()->json($results);
        }
        else {
            return response()->json('Null');
        }}

//Создание локации
    public function create()
    {
        $sky = Location::withoutGlobalScope('published')->where('is_sky', 'on')->get();
        return view('admin.locations.create', ['categories' => Category::all(), 'cities' => Cities::all(), 'sky' => $sky]);
    }

//Публикация локации
    public function store(Request $request)
    {
        app()->setLocale('ru');
        $this->validate($request, [
            'name' => 'required',
            'panorama' => 'file',
            'audio' => 'file',
        ]);
        $data = $request->all();
        $requestData = $request->all();
        $requestData['slug'] = Location::transliterate( $requestData['name']).str_random(3);
        if(!empty($data['isDefault'])) {
            $requestData['isDefault'] = 1;
        }

        if(empty($data['published'])) {
            $requestData['published'] = 0;
        }

        if(empty($data['show_sublocation'])) {
            $requestData['show_sublocation'] = 0;
        }

        if(!empty($data['panorama'])) {
            $randomStr = Str::random(40);
            $file = $data['panorama']->store('panoramas');
            $fullPath = public_path() . '/storage/' . $file;
            $baseName = pathinfo($file);
            $baseName = $baseName['filename'];
            $panoDir = public_path() . '/storage/panoramas/vtour/panos/' . $baseName . '.tiles';
            $command = exec('"/opt/krpano/krpanotools" makepano -config=templates/vtour-multires.config ' . $fullPath);
            mkdir(public_path() . '/storage/panoramas/unpacked/' . $randomStr);
            copy(public_path() . '/storage/panoramas/vtour/tour.xml', public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/tour.xml');
            rename($panoDir, public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/' . $baseName . '.tiles');
            self::delTree(public_path() . '/storage/panoramas/vtour');
            $xmllocation = Location::xmlName($randomStr);
            $xmldata = simplexml_load_file(public_path() . '/storage/panoramas/unpacked/'.$randomStr.'/tour.xml');
            $d = "";
            foreach ($xmldata->scene->children() as $child){ $d .= $child->asXML();}
            $requestData['xmllocation'] = preg_replace('/panos[\s\S]+?tiles/', '/storage/panoramas/unpacked/'.$xmllocation.'', $d);;
            $panoramas = [['panoramas' => [['panorama' => $randomStr]]]];
        }
        if(!empty($data['audio'])) {
            $randomStr = Str::random(40);
            $extension = $data['audio']->getClientOriginalExtension();
            $fullName = $randomStr . '.' . $extension;
            $file = $data['audio']->move(public_path('storage/audio'), $fullName);

            $requestData['audio'] = $fullName;
        }
        if(!empty($panoramas)) {
            $requestData['panorama'] = json_encode($panoramas);
            $location = Location::create($requestData);
            return redirect('admin/locations')->with('flash_message', 'Location added!');
        }
        else {
            return redirect()->back()->withErrors('Корректно заполните форму ниже');
        }
    }


    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

//Просмотр локации
    public function show($id)
    {
        $location = Location::withoutGlobalScope('published')->findOrFail($id);
        $locations = Location::withoutGlobalScope('published')->get()->all();

        $categories = Category::all();
        return view('pages.admin.edit', ['location' => $location, 'locations' => $locations, 'categories' => $categories]);
    }

    //API Локация
    public function show2($slug)
    {
        //Проверка куков на город
        if (Cookie::has('city')) {
            $defaultlocation = Cookie::get('city');
        } else {
            $defaultlocation = "1";
            Cookie::queue(Cookie::forever('city', '1'));
        }

        if (is_numeric($slug)) {
            $location = Location::where('id', $slug)->firstOrFail();
        } else {
            $location = Location::where('slug', $slug)->firstOrFail();
        }


        //Загрузка этажей основной точки
        $etaji = $location->etaji;
        $etajlocations="";
        if ($etaji->isNotEmpty()) {
            $code = "";
            foreach ($etaji as $ss => $etaj) {
                $code .= $etaji[$ss]->code;}
            preg_match_all ('/location : "([0-9]+)"/', $code, $matches);
            $etajlocations = Location::whereIn('id', $matches[1])->with('categorylocation')->get();
            $sss =Location::folderNames($etajlocations);
            foreach($etajlocations as $key2=>$value2){
                $etajlocations[$key2]->img = $sss[$key2];}}

        if(empty($location->is_sky)) {
            if(!empty($location->sky_id)) {
                $location->skyslug = Sky::where('id', $location->sky_id)->pluck('slug')->first();
            } else {
                $location->skyslug = Sky::where([['skymainforcity', 'on'],['city_id', $defaultlocation]])->pluck('slug')->first();
                $location->sky_id = Sky::where([['skymainforcity', 'on'],['city_id', $defaultlocation]])->pluck('id')->first();
            }} else { $location->skyslug = "no";};


        $locationArray = $location->toArray22();
        $locationArray['category_icon'] = $location->category->cat_icon_svg;
        return $locationArray;
    }


//Редактирование локации
    public function edit(Request $request, $id, $language)
    {
        app()->setLocale($language);
        $location = Location::withoutGlobalScope('published')->findOrFail($id);
        $categories = Category::all();
        $sky = Location::withoutGlobalScope('published')->where('is_sky', 'on')->get();
        $cities = Cities::all();

        $returnUrl = Input::get('returnUrl');

        if ($returnUrl) {
            $request->session()->put('returnUrl', $returnUrl);
        }

        return view('admin.locations.edit', compact('location', 'sky','categories', 'cities', 'language'));
    }

    //Обновление локации
    public function update(Request $request, $id, $language)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $location = Location::withoutGlobalScope('published')->findOrFail($id);
        $data = $request->all();
        $requestData = $request->all();
        if(!empty($data['isDefault'])) {$requestData['isDefault'] = 1;}
        else {$requestData['isDefault'] = 0;}
        if(empty($data['onmap'])) {
            $requestData['onmap'] = 0;
        }
        if(empty($data['isfeatured'])) {
            $requestData['isfeatured'] = 0;
        }

        if(empty($data['published'])) {

            $requestData['published'] = 0;
        }

        if(!empty($data['panorama'])) {
            $randomStr = Str::random(40);
            $file = $data['panorama']->store('panoramas');
            $fullPath = public_path() . '/storage/' . $file;
            $baseName = pathinfo($file);
            $baseName = $baseName['filename'];
            $panoDir = public_path() . '/storage/panoramas/vtour/panos/' . $baseName . '.tiles';
            $command = exec('"/opt/krpano/krpanotools" makepano -config=templates/vtour-multires.config ' . $fullPath);
            mkdir(public_path() . '/storage/panoramas/unpacked/' . $randomStr);
            copy(public_path() . '/storage/panoramas/vtour/tour.xml', public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/tour.xml');
            rename($panoDir, public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/' . $baseName . '.tiles');
            self::delTree(public_path() . '/storage/panoramas/vtour');
            $xmllocation = Location::xmlName($randomStr);
            $xmldata = simplexml_load_file(public_path() . '/storage/panoramas/unpacked/'.$randomStr.'/tour.xml');
            $d = "";
            foreach ($xmldata->scene->children() as $child){ $d .= $child->asXML();}
            $requestData['xmllocation'] = preg_replace('/panos[\s\S]+?tiles/', '/storage/panoramas/unpacked/'.$xmllocation.'', $d);;
            $panoramas = [['panoramas' => [['panorama' => $randomStr]]]];
            $requestData['panorama'] = json_encode($panoramas);
        }
        if(!empty($data['audio'])) {
            $randomStr = Str::random(40);
            $extension = $data['audio']->getClientOriginalExtension();
            $fullName = $randomStr . '.' . $extension;
            $file = $data['audio']->move(public_path('storage/audio'), $fullName);

            $requestData['audio'] = $fullName;
        }
        if(!empty($requestData['name'])) {
            app()->setLocale($language);
            $location = Location::withoutGlobalScope('published')->findOrFail($id);
            $location->update($requestData);
            $returnUrl = $request->session()->get('returnUrl');

            if ($returnUrl) {
                return redirect(urldecode($returnUrl))->with('flash_message', 'Локация отредактирована!');
            } else {
                return redirect('admin/locations')->with('flash_message', 'Локация отредактирована!');
            }
        }
        else {
            return redirect()->back()->withErrors('Корректно заполните форму ниже');
        }}


//Удаление локации
    public function destroy($id)
    {
        Location::withoutGlobalScope('published')->findOrFail($id)->delete();
        Location::where('podlocparent_id', $id)->withoutGlobalScope('published')->delete();
        Hotspot::where('location_id', $id)->delete();
        Hotspot::where('destination_id', $id)->delete();
        Location::where('sky_id', $id)->withoutGlobalScope('published')->update(array('sky_id' => ''));
        
        return redirect('admin/locations')->with('flash_message', 'Location deleted!');
    }

//Генерация панорамы
    public function generatePano($slug)
    {
        //Проверка куков на город
        if (Cookie::has('city')) {
            $defaultlocation = Cookie::get('city');
        } else {
            $defaultlocation = "1";
            Cookie::queue(Cookie::forever('city', '1'));
        }

        //Загрузка всех городов и координаты текущего города
        $cities = Cities::all();
        $curlocation = Cities::where('id', $defaultlocation)->firstOrFail();

        //Загрузка основноч точки
        $location = Location::where('slug', $slug)->with('categorylocation')->firstOrFail();

        //Загрузка этажей основной точки
        $etaji = $location->etaji;
        $etajlocations="";
        if ($etaji->isNotEmpty()) {
            $code = "";
            foreach ($etaji as $ss => $etaj) {
                $code .= $etaji[$ss]->code;}
            preg_match_all ('/location : "([0-9]+)"/', $code, $matches);
            $etajlocations = Location::whereIn('id', $matches[1])->with('categorylocation')->get();
            $sss =Location::folderNames($etajlocations);
            foreach($etajlocations as $key2=>$value2){
                $etajlocations[$key2]->img = $sss[$key2];}}

        //Загрузка неба
        if(empty($location->is_sky)) {
            if(!empty($location->sky_id)) {
                $sky = Sky::where('id', $location->sky_id)->firstOrFail();
            } else {
                $sky = Sky::where([['skymainforcity', 'on'],['city_id', $defaultlocation]])->firstOrFail();
            }}else { $sky = "no";};

        //Координаты локаций
        $locationscordinate = Location::where('city_id', $defaultlocation)->where('onmap', 'on')->with('categorylocation')->get();
        if ($locationscordinate->isNotEmpty()) {
            $sss =Location::folderNames($locationscordinate);
            foreach($locationscordinate as $key2=>$value2){
                $locationscordinate[$key2]->img = $sss[$key2];}
            $locationscordinate = Location::transl($locationscordinate);}

        //Загрузка избранных точек для карты
        $isfeatured = Location::where('isfeatured', 'on')->where('onmap', 'on')->where('city_id', $defaultlocation)->where('onmap', 'on')->with('categorylocation')->inRandomOrder()->limit(8)->get();
        if ($isfeatured->isNotEmpty()) {
            $sss =Location::folderNames($isfeatured);
            foreach($isfeatured as $key2=>$value2){
                $isfeatured[$key2]->img = $sss[$key2];}}


        //Загрузка новых точек для карты
        $isnew = Location::where('onmap', 'on')->where('city_id', $defaultlocation)->where('onmap', 'on')->with('categorylocation')->inRandomOrder()->limit(8)->get();
        if ($isnew->isNotEmpty()) {
            $sss =Location::folderNames($isnew);
            foreach($isnew as $key2=>$value2){
                $isnew[$key2]->img = $sss[$key2];}}

        //Загрузка хотспотов основной точки
        $krhotspots = Hotspot::where('location_id', $location->id)->with('destination_locations')->get();
        $array = $krhotspots->pluck('destination_locations.*.id')->flatten()->values();

        //Загрузка информации хотспотов основной точки
        $krhotspotinfo = Location::whereIn('id', $array)->with('categorylocation')->get();
        foreach($krhotspots as $key=>$value){
            foreach($krhotspotinfo as $key2=>$value2){
                if (json_encode($krhotspots[$key]->destination_id) == json_encode($krhotspotinfo[$key2]->id)) {
                    $test = json_decode($krhotspotinfo[$key2]->panorama)[0]->panoramas[0]->panorama;
                    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
                    foreach ($old as $item){
                        if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)){
                            $filename = $test . '/' . $item;
                            $krhotspots[$key]->img = $filename;}}
                    $krhotspots[$key]->name = $krhotspotinfo[$key2]->name;
                    $krhotspots[$key]->slug = $krhotspotinfo[$key2]->slug;
                    $krhotspots[$key]->cat_icon = $krhotspotinfo[$key2]->categorylocation->cat_icon;
                    $krhotspots[$key]->cat_icon_svg = $krhotspotinfo[$key2]->categorylocation->cat_icon_svg;
                    $krhotspots[$key]->color = $krhotspotinfo[$key2]->categorylocation->color;}}}

        //Другие локации
        $otherlocations = Location::where('city_id', $defaultlocation)->inRandomOrder()->limit(7)->with('categorylocation')->get();
        $sss =Location::folderNames($otherlocations);
        foreach($otherlocations as $key2=>$value2){
            $otherlocations[$key2]->img = $sss[$key2];}

        //Загрузка всех категорий
        $categories = Category::whereHas('locations', function($q) use($defaultlocation) {
            $q->where('city_id', $defaultlocation);
            $q->where('published', 1);
            $q->whereNull('podlocparent_id');
        })->orderBy('id', 'ASC')->get();

        if($location->count()) {
            return view('pages.index', [
                'location' => $location,
                'categories' => $categories,
                'krhotspots' => $krhotspots,
                'otherlocations' => $otherlocations,
                'cities' => $cities,
                'defaultlocation'=>$defaultlocation,
                'isfeatured' => $isfeatured,
                'curlocation'=> $curlocation,
                'locationscordinate'=> $locationscordinate,
                'sky'=> $sky,
                'isnew'=> $isnew,
                'etaji' => $etaji,
                'etajlocations'=>$etajlocations
            ]);

        } else {
            return response()->json([]);
        }
        return view('partials.admin.xml', ['location' => $location]);
    }

//Генерация Krpano для админки
    public function krpano($id)
    {
        $location = Location::withoutGlobalScope('published')->find($id);
        return response()->view('partials.admin.xml', compact('location'))->header('Content-Type', 'text/xml');
    }


//Загрузка всей локаций опредленной категории
    public function apiLocations($id)
    {
        $category = Category::findOrFail($id);
        $locations = $category->locations()->withoutGlobalScope('published')->orderBy('created_at', 'DESC')->paginate(999);

        $locations = Location::transl($locations);
        return $locations;
    }

    public function apiSublocations($id)
    {
        $location = Location::withoutGlobalScope('published')->find($id);
        $locations = $location->sublocations()->orderBy('created_at', 'DESC')->paginate(999);

        return $locations;
    }


    public function getcitydefaultlocation($id)
    {
        $locations = Location::where('isDefault', 1)->where('city_id', $id)->first();
        $locations = $locations->toArray22();
        return $locations;
    }

    public function apiAddhotspot(Request $request)
    {
        $data = $request->all();
        $hotspot = new Hotspot();
        $hotspot->location_id = $data['location'];
        $hotspot->destination_id = $data['src'];
        $hotspot->h = $data['h'];
        $hotspot->v = $data['v'];
        $hotspot->save();
        return 'ok';
    }

    public function apiAddInformationhotspot(Request $request)
    {
        $data = $request->all();

        $validation = Validator::make($request->all(), [
            'image' => 'required|file|max:150000'
        ]);

        if ($validation->passes()) {
            $image = $request->file('image');
            $newName = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/information'), $newName);
        }

        $hotspot = new Hotspot();
        $hotspot->location_id = $data['location'];
        $hotspot->destination_id = $data['location'];
        $hotspot->h = $data['h'];
        $hotspot->v = $data['v'];
        $hotspot->information = $data['information'];
        if (isset($image)) {
            $hotspot->image = $newName;
        }
        $hotspot->type = Hotspot::TYPE_INFORMATION;
        $hotspot->save();
//        return 'ok';
    }

    public function uploadVideo(Request $request)
    {
        $data = $request->all();

        $validation = Validator::make($request->all(), [
            'video' => 'required|file|max:150000'
        ]);

        if ($validation->passes())
        {
            $video = $request->file('video');
            $newName = rand() . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('storage/videos'), $newName);

            $hotspot = new Video();
            $hotspot->location_id = $data['location'];
            $hotspot->hfov = $data['hfov'];
            $hotspot->yaw = $data['yaw'];
            $hotspot->pitch = $data['pitch'];
            $hotspot->roll = $data['roll'];
            $hotspot->video = $newName;
            $hotspot->save();

            return response()->json([
                'message' => 'Video uploaded successfully',
                'uploaded_video' => 'storage/videos/' . $newName,
                'class_name' => 'alert-success',
            ]);
        } else
        {
            return response()->json([
                'message' => $validation->errors()->all(),
                'uploaded_video' => '',
                'class_name' => 'alert-danger',
            ]);
        }
    }

    public function apiHotspots($id)
    {
        //Загрузка хотспотов основной точки
        $krhotspots = Hotspot::where('location_id', $id)->with('destination_locations')->get();
        $array = $krhotspots->pluck('destination_locations.*.id')->flatten()->values();

        //Загрузка информации хотспотов основной точки
        $krhotspotinfo = Location::whereIn('id', $array)->with('categorylocation')->get();
        foreach($krhotspots as $key=>$value){
            foreach($krhotspotinfo as $key2=>$value2){
                if (json_encode($krhotspots[$key]->destination_id) == json_encode($krhotspotinfo[$key2]->id)) {
                    $test = json_decode($krhotspotinfo[$key2]->panorama)[0]->panoramas[0]->panorama;
                    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
                    foreach ($old as $item){
                        if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)) {
                            $filename = $test . '/' . $item;
                            $krhotspots[$key]->img = $filename;
                        }
                    }
                    $krhotspots[$key]->name = $krhotspotinfo[$key2]->name;
                    $krhotspots[$key]->slug = $krhotspotinfo[$key2]->slug;
                    $krhotspots[$key]->cat_icon = $krhotspotinfo[$key2]->categorylocation->cat_icon;
                    $krhotspots[$key]->cat_icon_svg = $krhotspotinfo[$key2]->categorylocation->cat_icon_svg;
                    $krhotspots[$key]->color = $krhotspotinfo[$key2]->categorylocation->color;
                    $krhotspots[$key]->audio = $krhotspotinfo[$key2]->audio;
                    $krhotspots[$key]->type = $krhotspotinfo[$key2]->type;
                    $krhotspots[$key]->image = $krhotspotinfo[$key2]->image;
                    $krhotspots[$key]->information = $krhotspotinfo[$key2]->information;
                }
            }
        }

        return  $krhotspots;
    }
}
