<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Cities;
use App\Hotspot;
use App\HotspotPolygon;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Location;
use App\LocationInformation;
use App\Sky;
use App\Tag;
use App\Video;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ZipArchive;

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
        $video = $request->get('video');
        $perPage = 25;

        $totalLocations = Location::withoutGlobalScope('published')->count();
        $publishedLocations = Location::count();
        $unpublishedLocations = Location::withoutGlobalScope('published')->where('published', '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword)) {
            $locations = Location::where('is_sky', '!=', 'on')
                ->whereNull('podlocparent_id')
                ->where(function ($query) use ($keyword, $category, $city, $video) {
                    $query->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city) {
                        $query->where('city_id', $city);
                    }
                    if ($category) {
                        $query->where('category_id', $category);
                    }
                    if ($video) {
                        $query->where('video', '!=', '');
                    }
                }
                )
                ->latest()
                ->paginate($perPage);
        } else {
            $locations = Location::where(function ($query) use ($category, $city, $perPage, $video) {
                $query->where('is_sky', '!=', 'on')
                    ->whereNull('podlocparent_id');
                if ($city) {
                    $query->where('city_id', $city);
                }
                if ($category) {
                    $query->where('category_id', $category);
                }
                if ($video) {
                    $query->where('video', '!=', '');
                }
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
        $unpublishedLocations = Location::withoutGlobalScope('published')->where('isDefault', '1')->where('published',
            '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword) && $category == '') {
            $locations = Location::where('is_sky', '!=', 'on')
                ->where(function ($q) use ($keyword, $city) {
                    $q->where('isDefault', '1')->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city) {
                        $q->where('city_id', $city);
                    }
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } elseif ($category != '') {
            $locations = Location::
            where('category_id', $category)
                ->where('isDefault', '1')
                ->where('is_sky', '!=', 'on')
                ->where(function ($q) use ($keyword, $city, $category) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city) {
                        $q->where('city_id', $city);
                    }
                    if ($category) {
                        $q->where('category_id', $category);
                    }
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } else {
            $locations = Location::where(function ($q) use ($category, $city, $perPage) {
                $q->where('is_sky', '!=', 'on')
                    ->where('isDefault', '1');
                if ($city) {
                    $q->where('city_id', $city);
                }
                if ($category) {
                    $q->where('category_id', $category);
                }
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

    public function hasFloors($id)
    {
        if (strpos($id, ':') !== false) {
            $ids = explode(':', $id);
            //получаем ID панорамы
            $location = Location::find(end($ids));

            if(!empty($location)) {
                if($location->etaji) {
                    return 1;
                }
            }
        }

        return 0;
    }

    public function unpublished(Request $request)
    {
        $keyword = $request->get('search');
        $category = $request->get('category');
        $city = $request->get('city');
        $perPage = 25;

        $totalLocations = Location::withoutGlobalScope('published')->where('isDefault', '1')->count();
        $publishedLocations = Location::where('isDefault', '1')->count();
        $unpublishedLocations = Location::withoutGlobalScope('published')->where('isDefault', '1')->where('published',
            '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword) && $category == '') {
            $locations = Location::where('is_sky', '!=', 'on')
                ->where(function ($q) use ($keyword, $city) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city) {
                        $q->where('city_id', $city);
                    }
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } elseif ($category != '') {
            $locations = Location::
            where('category_id', $category)
                ->where('is_sky', '!=', 'on')
                ->where(function ($q) use ($keyword, $city, $category) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city) {
                        $q->where('city_id', $city);
                    }
                    if ($category) {
                        $q->where('category_id', $category);
                    }
                })
                ->withoutGlobalScope('published')
                ->where('published', '!=', 1)
                ->latest()->paginate($perPage);
        } else {
            $locations = Location::where(function ($q) use ($perPage, $city, $category) {
                $q->where('is_sky', '!=', 'on')
                    ->where('published', '!=', '1');
                if ($city) {
                    $q->where('city_id', $city);
                }
                if ($category) {
                    $q->where('category_id', $category);
                }
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
        $unpublishedLocations = Location::withoutGlobalScope('published')->where('isfeatured', 'on')->where('published',
            '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword) && $category == '') {
            $locations = Location::where('is_sky', '!=', 'on')
                ->whereNull('podlocparent_id')
                ->where(function ($q) use ($keyword, $city) {
                    $q->where('isfeatured', 'on')->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city) {
                        $q->where('city_id', $city);
                    }
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } elseif ($category != '') {
            $locations = Location::
            where('category_id', $category)
                ->where('isfeatured', 'on')
                ->where('is_sky', '!=', 'on')
                ->whereNull('podlocparent_id')
                ->where(function ($q) use ($keyword, $city, $category) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city) {
                        $q->where('city_id', $city);
                    }
                    if ($category) {
                        $q->where('category_id', $category);
                    }
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } else {
            $locations = Location::where(function ($q) use ($city, $category, $perPage) {
                $q->where('is_sky', '!=', 'on')
                    ->where('isfeatured', 'on')
                    ->whereNull('podlocparent_id');
                if ($city) {
                    $q->where('city_id', $city);
                }
                if ($category) {
                    $q->where('category_id', $category);
                }
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
        $unpublishedLocations = Location::withoutGlobalScope('published')->whereNotNull('sky_id')->where('published',
            '!=', 1)->count();
        $categories = Category::pluck('name', 'id');
        $cities = Cities::pluck('name', 'id');

        if (!empty($keyword) && $category == '') {
            $locations = Location::where('is_sky', '!=', 'on')
                ->whereNull('podlocparent_id')
                ->where(function ($q) use ($keyword, $city) {
                    $q->whereNotNull('sky_id')->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city) {
                        $q->where('city_id', $city);
                    }
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } elseif ($category != '') {
            $locations = Location::
            where('category_id', $category)
                ->whereNotNull('sky_id')
                ->where('is_sky', '!=', 'on')
                ->whereNull('podlocparent_id')
                ->where(function ($q) use ($keyword, $city, $category) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                    if ($city) {
                        $q->where('city_id', $city);
                    }
                    if ($category) {
                        $q->where('category_id', $category);
                    }
                })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } else {
            $locations = Location::where(function ($q) use ($perPage, $city, $category) {
                $q->where('is_sky', '!=', 'on')
                    ->whereNotNull('sky_id')
                    ->whereNull('podlocparent_id');
                if ($city) {
                    $q->where('city_id', $city);
                }
                if ($category) {
                    $q->where('category_id', $category);
                }
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
    public function convert()
    {
        $locations = DB::table('locations')->get();
        foreach ($locations as $key => $location) {
            $locations[$key]->name = str_replace('"', '\"', $locations[$key]->name);
            DB::table('locations')
                ->where('id', $location->id)
                ->update([
                    'name' => '{"ru":"' . $locations[$key]->name . '"}',
                    'address' => '{"ru":"' . $locations[$key]->address . '"}',
                    'description' => '{"ru":"' . $locations[$key]->description . '"}',
                    'working_hours' => '{"ru":"' . $locations[$key]->working_hours . '"}'
                ]);
        }
    }

    //Конвертор мультиязычности:Городов
    public function convertcity()
    {
        $locations = DB::table('cities')->get();
        foreach ($locations as $key => $location) {
            $locations[$key]->name = str_replace('"', '\"', $locations[$key]->name);
            DB::table('cities')
                ->where('id', $location->id)
                ->update(['name' => '{"ru":"' . $locations[$key]->name . '"}']);
        }
    }

    //Конвертор мультиязычности:Категории
    public function convertcategories()
    {
        $locations = DB::table('categories')->get();
        foreach ($locations as $key => $location) {
            $locations[$key]->name = str_replace('"', '\"', $locations[$key]->name);
            DB::table('categories')
                ->where('id', $location->id)
                ->update(['name' => '{"ru":"' . $locations[$key]->name . '"}']);
        }
    }

    //Конвертор мультиязычности:Этажи
    public function convertfloors()
    {
        $locations = DB::table('floors')->get();
        foreach ($locations as $key => $location) {
            $locations[$key]->name = str_replace('"', '\"', $locations[$key]->name);
            DB::table('floors')
                ->where('id', $location->id)
                ->update(['name' => '{"ru":"' . $locations[$key]->name . '"}']);
        }
    }

    //Функия для путей
    public function getDirectory($id)
    {
        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $id);
        foreach ($old as $item) {
            if (is_dir(public_path() . '/storage/panoramas/unpacked/' . $id . '/' . $item)) {
                $filename = $id . '/' . $item;
            }
        }
        return $filename;
    }

    //Поиск
    public function search(Request $search, $categories)
    {
        if (Cookie::has('city')) {
            $defaultlocation = Cookie::get('city');
        } else {
            $defaultlocation = "1";
            Cookie::queue(Cookie::forever('city', '1'));
        }
        $search = request()->route('search');
        if (request()->route('search') == "noresult") {
            $categories = array_map('intval', explode(',', request()->route('categories')));
            $results = Location::where('city_id', '=', $defaultlocation)
                ->whereIN('category_id', $categories)
                ->where(function ($query) {
                    $query->whereNull('podlocparent_id')->orWhere('show_sublocation', 1);
                })
                ->where('visibility', Location::VISIBILITY_PUBLIC)
                ->orderBy('order', 'asc')
                ->get();

        } else {
            if (request()->route('categories') == 0) {
                $results = Location::with('tags')
                    ->where(function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%');
                        $query->orWhereHas('tags', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->where('city_id', '=', $defaultlocation)
                    ->where('visibility', Location::VISIBILITY_PUBLIC)
                    ->where(function ($query) {
                        $query->whereNull('podlocparent_id')->orWhere('show_sublocation', 1);
                    })->get();
            } else {
                $categories = array_map('intval', explode(',', request()->route('categories')));
                $results = Location::where('city_id', '=', $defaultlocation)
                    ->where(function ($query) {
                        $query->whereNull('podlocparent_id')->orWhere('show_sublocation', 1);
                    })
                    ->where('name', 'LIKE', '%' . $search . '%')->whereIn('category_id', $categories)
                    ->where('visibility', Location::VISIBILITY_PUBLIC)
                    ->get();
            }
        }
        if ($results->count()) {
            foreach ($results as $key2 => $value2) {
                $caticon = Category::where('id', $results[$key2]->category_id)->firstOrFail();
                $results[$key2]->cat_icon = $caticon->cat_icon;
                $results[$key2]->color = $caticon->color;
                $results[$key2]->cat_icon_svg = $caticon->cat_icon_svg;
            }
            $results = Location::transl($results);
            return response()->json($results);
        } else {
            return response()->json('Null');
        }
    }

    //Создание локации
    public function create()
    {
        $sky = Location::withoutGlobalScope('published')->where('is_sky', 'on')->get();
        $tags = Tag::pluck('name', 'id')->all();
        $location = new Location;
        return view('admin.locations.create', [
            'location' => $location,
            'categories' => Category::all(),
            'cities' => Cities::all(),
            'sky' => $sky,
            'tags' => $tags,
        ]);
    }

    //Публикация локации
    public function store(Request $request)
    {
        app()->setLocale('ru');
        $this->validate($request, [
            'name' => 'required',
            'panorama' => 'file',
            'audio' => 'file',
            'video' => 'file'
        ]);
        $data = $request->all();
        $requestData = $request->all();
        $requestData['slug'] = Location::transliterate($requestData['name']) . str_random(3);
        if (!empty($data['isDefault'])) {
            $requestData['isDefault'] = 1;
        }

        if (empty($data['published'])) {
            $requestData['published'] = 0;
        }

        if (empty($data['show_sublocation'])) {
            $requestData['show_sublocation'] = 0;
        }

        if (!empty($data['panorama'])) {
            $randomStr = Str::random(40);
            $file = $data['panorama']->store('panoramas');
            $fullPath = public_path() . '/storage/' . $file;
            $baseName = pathinfo($file);
            $baseName = $baseName['filename'];
            $panoDir = public_path() . '/storage/panoramas/vtour/panos/' . $baseName . '.tiles';
            $command = exec('"/opt/krpano/krpanotools" makepano -config=templates/vtour-multires.config -panotype=sphere ' . $fullPath);
            mkdir(public_path() . '/storage/panoramas/unpacked/' . $randomStr);
            copy(public_path() . '/storage/panoramas/vtour/tour.xml',
                public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/tour.xml');
            rename($panoDir, public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/' . $baseName . '.tiles');
            self::delTree(public_path() . '/storage/panoramas/vtour');
            $xmllocation = Location::xmlName($randomStr);
            $xmldata = simplexml_load_file(public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/tour.xml');
            $d = "";
            foreach ($xmldata->scene->children() as $child) {
                $d .= $child->asXML();
            }
            $requestData['xmllocation'] = preg_replace('/panos[\s\S]+?tiles/',
                '/storage/panoramas/unpacked/' . $xmllocation . '', $d);;
            $panoramas = [['panoramas' => [['panorama' => $randomStr]]]];
        }

        if (!empty($data['video'])) {
            $randomStr = Str::random(10);
            //$file = $data['video']->store($randomStr);

            $file = $data['video'];
            $extension = pathinfo($file->getClientOriginalName())['extension'];
            $filenameVideo = $randomStr . '.' . $extension;
            $path = public_path().'/storage/panoramas/video';

            $file->move($path, $filenameVideo);
        }

        if (!empty($data['preview'])) {
            $randomStr = Str::random(10);
            //$file = $data['video']->store($randomStr);

            $file = $data['preview'];

            $filename = $randomStr .$file->getClientOriginalName();
            $path = public_path().'/storage/panoramas/preview';
            $requestData['preview'] = $filename;
            $file->move($path, $filename);
        }

        if (!empty($data['audio'])) {
            $randomStr = Str::random(40);
            $extension = $data['audio']->getClientOriginalExtension();
            $fullName = $randomStr . '.' . $extension;
            $file = $data['audio']->move(public_path('storage/audio'), $fullName);

            $requestData['audio'] = $fullName;
        }
        if (!empty($panoramas) || !empty($filenameVideo)) {
            $requestData['preview'] = '';
            if (!empty($panoramas)) {
                $requestData['panorama'] = json_encode($panoramas);
            }
            if (!empty($filenameVideo)) {
                $requestData['video'] = $filenameVideo;
                if (isset($filename)) {
                    $requestData['preview'] = $filename;
                }
            }
            $location = Location::create($requestData);
            $meta = \App\Meta::create($requestData['meta']);
            $location->meta_id = $meta->id;
            $location->save();

            if (isset($requestData['information']['back_button_file']) && $requestData['information']['back_button_file']) {
                $randomStr = Str::random(40);
                $extension = $requestData['information']['back_button_file']->getClientOriginalExtension();
                $fullName = $randomStr . '.' . $extension;
                $file = $requestData['information']['back_button_file']->move(public_path('storage/locations_information'),
                    $fullName);

                $requestData['information']['back_button_image'] = $fullName;
            }


            if (isset($requestData['information'])) {
                $information = \App\LocationInformation::create($requestData['information']);
                $information->location_id = $location->id;
                $information->save();
            }

            if (isset($requestData['tags'])) {
                $tagIds = $requestData['tags'];
                $location->tags()->sync($tagIds);
            }

            return redirect('admin/locations')->with('flash_message', 'Локация добавлена');
        } else {
            return redirect()->back()->withErrors('Корректно заполните форму ниже');
        }
    }

    public static function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
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
        return view('pages.admin.edit',
            ['location' => $location, 'locations' => $locations, 'categories' => $categories]);
    }

    //API Локация
    public function show2($slug)
    {
        //Проверка кук на город
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
        $etajlocations = "";
        if ($etaji->isNotEmpty()) {
            $code = "";
            foreach ($etaji as $ss => $etaj) {
                $code .= $etaji[$ss]->code;
            }
            preg_match_all('/location : "([0-9]+)"/', $code, $matches);
            $etajlocations = Location::whereIn('id', $matches[1])->with('categorylocation')->get();
            $folderNames = Location::folderNames($etajlocations);
            foreach ($etajlocations as $key2 => $value2) {
                $etajlocations[$key2]->img = $folderNames[$key2];
            }
        }

        if (empty($location->is_sky)) {
            if (!empty($location->sky_id)) {
                $sky = Sky::where('id', $location->sky_id)->first();
                $location->skyslug = $sky->slug;
            } else {
                $sky = Sky::where([
                    ['skymainforcity', 'on'],
                    ['city_id', $defaultlocation]
                ])->first();
                $location->skyslug = $sky->slug;
                $location->sky_id = $sky->id;
            }
        } else {
            $location->skyslug = "no";
        }

        $locationArray = $location->toArray22();
        if ($location->videos) {
            $locationArray['videos'] = $location->videos;
        }
        $locationArray['category_icon'] = $location->category->cat_icon_svg;
        $locationArray['floors_locations'] = $etajlocations;
        if (isset($sky)) {
            $locationArray['sky_video'] = $sky->video;
        }

        return $locationArray;
    }

    public function showVideo($id)
    {
        $location = Location::withoutGlobalScope('published')->findOrFail($id);

        return view('admin.locations.video', compact('location'));


    }

    //Редактирование локации
    public function edit(Request $request, $id, $language)
    {
        app()->setLocale($language);
        $location = Location::withoutGlobalScope('published')->findOrFail($id);
        $categories = Category::all();
        $sky = Location::withoutGlobalScope('published')->where('is_sky', 'on')->get();
        $cities = Cities::all();
        $tags = Tag::pluck('name', 'id')->all();

        $returnUrl = $request->get('returnurl');

        if ($returnUrl) {
            $request->session()->put('returnUrl', $returnUrl);
        }

        return view('admin.locations.edit', compact(
            'location',
            'sky',
            'categories',
            'cities',
            'language',
            'tags'
        ));
    }

    //Обновление локации
    public function update(Request $request, $id, $language)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $location = Location::withoutGlobalScope('published')->findOrFail($id);
        $initialLocationTitle = $location->name;
        $data = $request->all();
        $requestData = $request->all();

        if (isset($requestData['tags'])) {
            $tagIds = $requestData['tags'];
            $location->tags()->sync($tagIds);
        }

        if (!empty($data['isDefault'])) {
            $requestData['isDefault'] = 1;

            $otherLocations = Location::where('isDefault', 1)->where('city_id',
            $location->city_id)->get();
            foreach($otherLocations as $otherLocation) {
                $otherLocation->isDefault = 0;
                $otherLocation->save();
            }
        } else {
            $requestData['isDefault'] = 0;
        }
        if (empty($data['onmap'])) {
            $requestData['onmap'] = 0;
        }
        if (empty($data['isfeatured'])) {
            $requestData['isfeatured'] = 0;
        }

        if (empty($data['published'])) {
            $requestData['published'] = 0;
        }

        if (!empty($data['panorama'])) {
            $randomStr = Str::random(40);
            $file = $data['panorama']->store('panoramas');
            $fullPath = public_path() . '/storage/' . $file;
            $baseName = pathinfo($file);
            $baseName = $baseName['filename'];
            $panoDir = public_path() . '/storage/panoramas/vtour/panos/' . $baseName . '.tiles';
            $command = exec('"/opt/krpano/krpanotools" makepano -config=templates/vtour-multires.config -panotype=sphere ' . $fullPath);
            mkdir(public_path() . '/storage/panoramas/unpacked/' . $randomStr);
            copy(public_path() . '/storage/panoramas/vtour/tour.xml',
                public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/tour.xml');
            rename($panoDir, public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/' . $baseName . '.tiles');
            self::delTree(public_path() . '/storage/panoramas/vtour');
            $xmllocation = Location::xmlName($randomStr);
            $xmldata = simplexml_load_file(public_path() . '/storage/panoramas/unpacked/' . $randomStr . '/tour.xml');
            $d = "";
            foreach ($xmldata->scene->children() as $child) {
                $d .= $child->asXML();
            }
            $requestData['xmllocation'] = preg_replace('/panos[\s\S]+?tiles/',
                '/storage/panoramas/unpacked/' . $xmllocation . '', $d);;
            $panoramas = [['panoramas' => [['panorama' => $randomStr]]]];
            $requestData['panorama'] = json_encode($panoramas);
        }
        if (!empty($data['audio'])) {
            $randomStr = Str::random(40);
            $extension = $data['audio']->getClientOriginalExtension();
            $fullName = $randomStr . '.' . $extension;
            $file = $data['audio']->move(public_path('storage/audio'), $fullName);

            $requestData['audio'] = $fullName;
        }
        if (!empty($requestData['name'])) {
            app()->setLocale($language);
            $location = Location::withoutGlobalScope('published')->findOrFail($id);
            if (!$location->meta) {
                $meta = \App\Meta::create($requestData['meta']);
                $meta->save();
                $location->meta_id = $meta->id;
                $location->save();
            } else {
                $meta = $location->meta;
                $meta->update($requestData['meta']);
            }

            if (isset($requestData['information']['back_button_file']) && $requestData['information']['back_button_file']) {
                $randomStr = Str::random(40);
                $extension = $requestData['information']['back_button_file']->getClientOriginalExtension();
                $fullName = $randomStr . '.' . $extension;
                $file = $requestData['information']['back_button_file']->move(public_path('storage/locations_information'), $fullName);

                $requestData['information']['back_button_image'] = $fullName;
            }

            if (!$location->information) {
                $information = \App\LocationInformation::create($requestData['information']);
                $information->location_id = $location->id;
                $information->save();
            } else {
                $information = $location->information;
                $information->update($requestData['information']);
            }
            $location = Location::withoutGlobalScope('published')->with('meta')->findOrFail($id);
            if ($initialLocationTitle != $requestData['name']) {
                $requestData['slug'] = Str::slug($requestData['name'], '-');
            }
            $location->update($requestData);
            $returnUrl = $request->session()->get('returnUrl');

            if ($returnUrl) {
                return redirect(urldecode($returnUrl))->with('flash_message', 'Локация отредактирована!');
            } else {
                return redirect('admin/locations')->with('flash_message', 'Локация отредактирована!');
            }
        } else {
            return redirect()->back()->withErrors('Корректно заполните форму ниже');
        }
    }

    //Удаление локации
    public function destroy($id)
    {
        Video::where('location_id', $id)->delete();
        LocationInformation::where('location_id', $id)->delete();
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
        //Проверка кук на город
        if (Cookie::has('city')) {
            $defaultlocation = Cookie::get('city');
        } else {
            $defaultlocation = "1";
            Cookie::queue(Cookie::forever('city', $defaultlocation));
        }

        //Загрузка всех городов и координаты текущего города
        $cities = Cities::all();
        $curlocation = Cities::where('id', $defaultlocation)->firstOrFail();

        //Загрузка основной точки
        $location = Location::where('slug', $slug)->with('categorylocation')->firstOrFail();

        //Загрузка этажей основной точки
        $etaji = $location->etaji;
        $etajlocations = "";
        if ($etaji->isNotEmpty()) {
            $code = "";
            foreach ($etaji as $ss => $etaj) {
                $code .= $etaji[$ss]->code;
            }
            preg_match_all('/location : "([0-9]+)"/', $code, $matches);
            $etajlocations = Location::whereIn('id', $matches[1])->with('categorylocation')->get();
            $sss = Location::folderNames($etajlocations);
            foreach ($etajlocations as $key2 => $value2) {
                $etajlocations[$key2]->img = $sss[$key2];
            }
        }

        //Загрузка неба
        if (empty($location->is_sky)) {
            if (!empty($location->sky_id)) {
                $sky = Sky::where('id', $location->sky_id)->firstOrFail();
            } else {
                $sky = Sky::where([['skymainforcity', 'on'], ['city_id', $defaultlocation]])->firstOrFail();
            }
        } else {
            $sky = "no";
        }

        //Координаты локаций
        $locationscordinate = Location::where('city_id', $defaultlocation)->where('onmap',
            'on')->with('categorylocation')->get();
        if ($locationscordinate->isNotEmpty()) {
            $sss = Location::folderNames($locationscordinate);
            foreach ($locationscordinate as $key2 => $value2) {
                $locationscordinate[$key2]->img = $sss[$key2];
            }
            $locationscordinate = Location::transl($locationscordinate);
        }

        //Загрузка избранных точек для карты
        $isfeatured = Location::where('isfeatured', 'on')->where('onmap', 'on')->where('city_id',
            $defaultlocation)->where('onmap', 'on')->with('categorylocation')->inRandomOrder()->limit(8)->get();
        if ($isfeatured->isNotEmpty()) {
            $sss = Location::folderNames($isfeatured);
            foreach ($isfeatured as $key2 => $value2) {
                  if ($isfeatured[$key2]->video) {
                      $isfeatured[$key2]->img = $isfeatured[$key2]->preview;
                  } else {
                        $isfeatured[$key2]->img = $sss[$key2];
                  }

            }
        }


        //Загрузка новых точек для карты
        $isnew = Location::where('onmap', 'on')->where('city_id', $defaultlocation)->where('onmap',
            'on')->with('categorylocation')->inRandomOrder()->limit(8)->get();
        if ($isnew->isNotEmpty()) {
            $sss = Location::folderNames($isnew);
            foreach ($isnew as $key2 => $value2) {
                if ($isnew[$key2]->video) {
                    $isnew[$key2]->img = $isnew[$key2]->preview;
                } else {
                    $isnew[$key2]->img = $sss[$key2];
                }
            }
        }

        //Загрузка хотспотов основной точки
        $krhotspots = Hotspot::with('destination_locations')->join('locations', 'locations.id', 'destination_id')->where('location_id', $location->id)
            ->where('locations.published', 1)->get();
        $array = $krhotspots->pluck('destination_locations.*.id')->flatten()->values();
        //Загрузка информации хотспотов основной точки
        $krhotspotinfo = Location::whereIn('id', $array)->with('categorylocation')->get();
        foreach ($krhotspots as $key => $value) {
//            if ($krhotspots[$key]->type == \App\Hotspot::TYPE_POLYGON) {
//                continue;
//            }
            foreach ($krhotspotinfo as $key2 => $value2) {
                if (json_encode($krhotspots[$key]->destination_id) == json_encode($krhotspotinfo[$key2]->id)) {
                    if ($krhotspotinfo[$key2]->video) {
                        $krhotspots[$key]->img = $krhotspotinfo[$key2]->preview;
                    } else {
                        $test = json_decode($krhotspotinfo[$key2]->panorama)[0]->panoramas[0]->panorama;
                        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
                        foreach ($old as $item) {
                            if (is_dir(public_path() . '/storage/panoramas/unpacked/' . $test . '/' . $item)) {
                                $filename = $test . '/' . $item;
                                $krhotspots[$key]->img = $filename;
                            }
                        }
                    }

                    $krhotspots[$key]->name = $krhotspotinfo[$key2]->name;
                    $krhotspots[$key]->slug = $krhotspotinfo[$key2]->slug;
                    $krhotspots[$key]->cat_icon = $krhotspotinfo[$key2]->categorylocation->cat_icon;
                    $krhotspots[$key]->cat_icon_svg = $krhotspotinfo[$key2]->categorylocation->cat_icon_svg;
                    $krhotspots[$key]->color = $krhotspotinfo[$key2]->categorylocation->color;
                }
            }
        }

        //Другие локации
        $otherlocations = Location::where('city_id',
            $defaultlocation)->inRandomOrder()->limit(7)->with('categorylocation')->get();
        $sss = Location::folderNames($otherlocations);
        foreach ($otherlocations as $key2 => $value2) {
            if ($location->video == '') {
                $otherlocations[$key2]->img = $sss[$key2];
            } else {
                $otherlocations[$key2]->img = $location->preview;
            }
        }

        //Загрузка всех категорий
        $categories = Category::whereHas('locations', function ($q) use ($defaultlocation) {
            $q->where('city_id', $defaultlocation);
            $q->where('published', 1);
            $q->whereNull('podlocparent_id');
        })->orderBy('id', 'ASC')->get();

        $openedCategory = null;

        $referer = '';
        if ($location->information && $location->information->back_button_from_domain &&
            isset($_SERVER['HTTP_REFERER']) &&
            strpos($_SERVER['HTTP_REFERER'], $location->information->back_button_from_domain) !== false) {
            $referer = $_SERVER['HTTP_REFERER'];
        }

        if ($location->count()) {
            return view('pages.index', [
                'location' => $location,
                'categories' => $categories,
                'krhotspots' => $krhotspots,
                'otherlocations' => $otherlocations,
                'cities' => $cities,
                'defaultlocation' => $defaultlocation,
                'isfeatured' => $isfeatured,
                'curlocation' => $curlocation,
                'locationscordinate' => $locationscordinate,
                'sky' => $sky,
                'isnew' => $isnew,
                'etaji' => $etaji,
                'etajlocations' => $etajlocations,
                'openedCategory' => $openedCategory,
                'referer' => $referer
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
        $query = isset($_GET['query']) ? $_GET['query'] : '';
        if ($query) {
            $locations = $category->locations()
                ->withoutGlobalScope('published')
                ->where('name', 'LIKE', "%$query%")
                ->orderBy('created_at', 'DESC')
                ->paginate(999);
        } else {
            $locations = $category->locations()->withoutGlobalScope('published')->orderBy('created_at',
                'DESC')->paginate(999);
        }

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
        $hotspotInformation = $data['information'];
        $hotspot->information = $hotspotInformation;
        if (isset($image)) {
            $hotspot->image = $newName;
        }
        $hotspot->type = Hotspot::TYPE_INFORMATION;
        $hotspot->save();
    }

    public function apiAddPolygonhotspot(Request $request)
    {
        $data = $request->all();

        $hotspot = new Hotspot();
        $hotspot->location_id = $data['location'];
        $hotspot->destination_id = $data['location'];
        $hotspot->h = $data['h'];
        $hotspot->v = $data['v'];
//        $hotspotInformation = $data['html_code'];
//        $hotspot->html_code = $hotspotInformation;
        $hotspotInformation = $data['information'];
        $hotspot->information = $hotspotInformation;
        $hotspot->url = $data['url'];
        $hotspot->type = Hotspot::TYPE_POLYGON;
        if (!empty($data['model'])) {
            $randomStr = Str::random(40);
            $extension = $data['model']->getClientOriginalExtension();
            $fullName = $randomStr . '.' . $extension;
            $file = $data['model']->move(public_path('storage/models' . DIRECTORY_SEPARATOR . $randomStr), $fullName);
            $zipFile = public_path('storage/models' . DIRECTORY_SEPARATOR . $randomStr)
                . DIRECTORY_SEPARATOR . $fullName;

            $zip = new ZipArchive;
            $res = $zip->open($zipFile);
            if ($res === true) {
                $zip->extractTo(public_path('storage/models' . DIRECTORY_SEPARATOR . $randomStr));
                $zip->close();
            }
            unlink($zipFile);
            $files = scandir(public_path('storage/models' . DIRECTORY_SEPARATOR . $randomStr));
            foreach ($files as $file) {
                if (strpos($file, '.html') !== false) {
                    $hotspot->model_path = $randomStr . DIRECTORY_SEPARATOR . $file;
                }
            }
        }
        $hotspot->save();

        if (isset($data['polygons'])) {
            $polygons = json_decode('[' . $data['polygons'] . ']');
            foreach ($polygons as $polygon) {
                $pol = new HotspotPolygon();
                $pol->hotspot_id = $hotspot->id;
                $pol->h = $polygon->x;
                $pol->v = $polygon->y;
                $pol->save();
            }
        }
    }

    public function uploadVideo(Request $request)
    {
        $data = $request->all();

        $validation = Validator::make($request->all(), [
            'video' => 'required|file|max:500000'
        ]);

        if ($validation->passes()) {
            $video = $request->file('video');
            $newName = rand() . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('storage/videos'), $newName);

            $hotspot = new Video();
            $hotspot->location_id = $data['location'];
            $hotspot->hfov = str_replace(',', '.', $data['hfov']);
            $hotspot->yaw = str_replace(',', '.', $data['yaw']);
            $hotspot->pitch = str_replace(',', '.', $data['pitch']);
            $hotspot->roll = str_replace(',', '.', $data['roll']);
            $hotspot->video = $newName;
            $hotspot->play_type = $data['play_type'];
            $hotspot->save();

            return response()->json([
                'message' => 'Video uploaded successfully',
                'uploaded_video' => 'storage/videos/' . $newName,
                'class_name' => 'alert-success',
            ]);
        } else {
            return response()->json([
                'message' => $validation->errors()->all(),
                'uploaded_video' => '',
                'class_name' => 'alert-danger',
            ]);
        }
    }

    public function removeVideo($id)
    {

    }

    public function apiHotspots($id)
    {
        //Загрузка хотспотов основной точки
        $krhotspots = Hotspot::with('destination_locations')->join('locations', 'locations.id', 'destination_id')->where('location_id', $id)
            ->where('locations.published', 1)->get();
        $array = $krhotspots->pluck('destination_locations.*.id')->flatten()->values();

        //Загрузка информации хотспотов основной точки
        $krhotspotinfo = Location::whereIn('id', $array)->with('categorylocation')->get();
        foreach ($krhotspots as $key => $value) {
            foreach ($krhotspotinfo as $key2 => $value2) {
                if ($krhotspotinfo[$key2]->type == \App\Hotspot::TYPE_POLYGON) {
                    continue;
                }
                if (json_encode($krhotspots[$key]->destination_id) == json_encode($krhotspotinfo[$key2]->id)) {
                    if ($krhotspotinfo[$key2]->video) {
                        $krhotspots[$key]->img = $krhotspotinfo[$key2]->preview;
                    } else {
                        $test = json_decode($krhotspotinfo[$key2]->panorama)[0]->panoramas[0]->panorama;
                        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
                        foreach ($old as $item) {
                            if (is_dir(public_path() . '/storage/panoramas/unpacked/' . $test . '/' . $item)) {
                                $filename = $test . '/' . $item;
                                $krhotspots[$key]->img = $filename;
                            }
                        }
                    }
                    $krhotspots[$key]->name = $krhotspotinfo[$key2]->name;
                    $krhotspots[$key]->slug = $krhotspotinfo[$key2]->slug;
                    $krhotspots[$key]->cat_icon = $krhotspotinfo[$key2]->categorylocation->cat_icon;
                    $krhotspots[$key]->cat_icon_svg = $krhotspotinfo[$key2]->categorylocation->cat_icon_svg;
                    $krhotspots[$key]->color = $krhotspotinfo[$key2]->categorylocation->color;
                    $krhotspots[$key]->audio = $krhotspotinfo[$key2]->audio;
                    $krhotspots[$key]->type = $krhotspots[$key]->type;
                    $krhotspots[$key]->image = $krhotspots[$key]->image;
                    $krhotspots[$key]->video = $krhotspotinfo[$key2]->video;
                    $hotspotInformation = $krhotspots[$key]->information;
                    $hotspotInformation = str_replace("\r", "<br>", $hotspotInformation);
                    $hotspotInformation = str_replace('"', '\"', $hotspotInformation);
                    $hotspotInformation = str_replace("'", "\'", $hotspotInformation);
                    $hotspotInformation = str_replace("\r", '\\\r', $hotspotInformation);
                    $krhotspots[$key]->information = $hotspotInformation;
                }
            }
        }

        return $krhotspots;
    }
}
