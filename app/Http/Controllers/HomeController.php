<?php

namespace App\Http\Controllers;

use App\Location;
use App\Category;
use App\Cities;
use Illuminate\Http\Request;
use DB;
use App\Sky;
use App\Hotspot;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Mail;
use Illuminate\Support\Facades\Cookie;
use Spatie\Translatable\HasTranslations;

class HomeController extends Controller

{

    public function videoScript() {
        $hotspots = \Illuminate\Support\Facades\DB::table('videos')->select(['id','video', 'hfov', 'yaw', 'pitch', 'roll'])
            ->get()->toArray();
        $strings = [];
        foreach ($hotspots as $key => $hotspot) {
            if (!$this->isJson2($hotspot->video)) {
                $strings[$key]['video'] = $hotspot->video;
            }
            if (!$this->isJson2($hotspot->hfov)) {
                $strings[$key]['hfov'] = $hotspot->hfov;
            }
            if (!$this->isJson2($hotspot->yaw)) {
                $strings[$key]['yaw'] = $hotspot->yaw;
            }
            if (!$this->isJson2($hotspot->pitch)) {
                $strings[$key]['pitch'] = $hotspot->pitch;
            }
            if (!$this->isJson2($hotspot->roll)) {
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
    }
    public function script() {
        $hotspots = \Illuminate\Support\Facades\DB::table('hotspots')->select(['information','destination_id', 'image','type','id'])
            ->where('type', 2)->get();
        $strings = [];
        foreach ($hotspots as $key => $hotspot) {

            if (!$this->isJson2($hotspot->information)) {
                $strings[$key]['information'] = $hotspot->information;
            }
            if (!$this->isJson2($hotspot->image)) {
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
    }

    public function isJson2($str) {
        $json = json_decode($str);
        return $json && $str != $json;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    //Главная страница

    public function getIndex($category = null, $home = null)
    {
        //Проверка кук на город
        if (Cookie::has('city')) {
            $defaultlocation = Cookie::get('city');
        } else {
            $defaultlocation = "1";
            Cookie::queue(Cookie::forever('city', '1'));
        }

        $openedCategory = null;

        if (!empty($category)) {
            $openedCategory = Category::where('slug', 'LIKE', "%$category%")->whereNotNull('slug')->first();
        }

        //Загрузка всех городов и координаты текущего города
        $cities = Cities::all();
        $curlocation = Cities::where('id', $defaultlocation)->firstOrFail();

        //Загрузка основноч точки
        //обработка субдоменов и локация по умолчанию
        $subdomain = $this->getSubdomainName();
        if ($subdomain && $subdomain != 'dev' && $subdomain != 'dev2' &&  $subdomain != 'platform' && !is_numeric($subdomain)) {
            $city = Cities::where('subdomain', $subdomain)->first();
            if ($city) {
                $location = Location::where([
                    ['city_id', $city->id],
                    ['isDefault', '1']
                ])->with('categorylocation')->firstOrFail();
                Cookie::queue(Cookie::forever('city', $city->id));
            } else {
                $subdomainLocation = Location::where('subdomain', $subdomain)
                    ->with('categorylocation')->firstOrFail();
                if (!isset($_GET['home'])) {
                    Cookie::queue(Cookie::forever('city', '1'));
                    $location = $subdomainLocation;
                } else {
                    //панорама по умолчанию для города
                    $location = Location::where([
                        ['isDefault', '1'],
                        ['city_id', $defaultlocation]
                    ])->with('categorylocation')->firstOrFail();
                }
            }
        } elseif ($openedCategory) {
            $location = Location::where([
                ['category_id', $openedCategory->id],
                ['city_id', $defaultlocation]
            ])->with('categorylocation')->firstOrFail();
        } else {
            $location = Location::where([
                ['isDefault', '1'],
                ['city_id', $defaultlocation]
            ])->with('categorylocation')->firstOrFail();
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
            $defaultlocation)->where('onmap', 'on')->with('categorylocation')->orderBy('id', 'DESC')->limit(8)->get();
        if ($isfeatured->isNotEmpty()) {
            $folderNames = Location::folderNames($isfeatured);
            foreach ($isfeatured as $key2 => $value2) {
                if ($isfeatured[$key2]->video) {
                    $isfeatured[$key2]->img = $isfeatured[$key2]->preview;
                } else {
                    $isfeatured[$key2]->img = $folderNames[$key2];
                }
            }
        }

        //Загрузка новых точек для карты
        $isnew = Location::where('onmap', 'on')->where('city_id', $defaultlocation)->where('onmap',
            'on')->with('categorylocation')->inRandomOrder()->limit(8)->get();
        if ($isnew->isNotEmpty()) {
            $folderNames = Location::folderNames($isnew);
            foreach ($isnew as $key2 => $value2) {
                if ($isnew[$key2]->video) {
                    $isnew[$key2]->img = $isnew[$key2]->preview;
                } else {
                    $isnew[$key2]->img = $folderNames[$key2];
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
            foreach ($krhotspotinfo as $key2 => $value2) {
                if (json_encode($krhotspots[$key]->destination_id) == json_encode($krhotspotinfo[$key2]->id)) {
                    if (empty($krhotspotinfo[$key2]->video)) {
                        $panoPath = json_decode($krhotspotinfo[$key2]->panorama)[0]->panoramas[0]->panorama;
                        try {
                            $dirs = scandir(public_path() . '/storage/panoramas/unpacked/' . $panoPath);
                        } catch (\Exception $e) {
                            $dirs = [];
                        }
                        foreach ($dirs as $item) {
                            if (is_dir(public_path() . '/storage/panoramas/unpacked/' . $panoPath . '/' . $item)) {
                                $filename = $panoPath . '/' . $item;
                                $krhotspots[$key]->img = $filename;
                            }
                        }
                    } else {
                         $krhotspots[$key]->img = '/storage/panoramas/preview/' . $krhotspotinfo[$key2]->preview;
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
            $otherlocations[$key2]->img = $sss[$key2];
        }

        //Загрузка всех категорий
        $categories = Category::whereHas('locations', function ($q) use ($defaultlocation) {
            $q->where('city_id', $defaultlocation);
            $q->where('published', 1);
            $q->whereNull('podlocparent_id');
        })->orderBy('id', 'ASC')->get();

        $referer = '';
        if ($location->information && $location->information->back_button_from_domain &&
            isset($_SERVER['HTTP_REFERER']) &&
            strpos($_SERVER['HTTP_REFERER'], $location->information->back_button_from_domain) !== false) {
            $referer = $_SERVER['HTTP_REFERER'];
        }

        $openedCategoryLocations = null;
        if (isset($openedCategory)) {
            $openedCategoryLocations = Location::where([
                ['category_id', $openedCategory->id],
                ['city_id', $defaultlocation]
            ])->where(function ($query) {
                $query->whereNull('podlocparent_id')->orWhere('show_sublocation', 1);
            })->get();
        }

        return view('pages.index', [
            'location' => $location,
            'categories' => $categories,
            'openedCategory' => $openedCategory,
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
            'referer' => $referer,
            'openedCategoryLocations' => $openedCategoryLocations
        ]);
    }

    /**
     * @return boolean|string Возвращает название субдомена, или false, если это не субдомен
     */
    public function getSubdomainName()
    {
        $serverName = $_SERVER['HTTP_HOST'];
        $serverNameArr = explode('.', $serverName);

        $subdomain = false;

        if (env('APP_ENV') == 'local') {
            if (count($serverNameArr) > 1) {
                $subdomain = true;
            }
        } else {
            if (count($serverNameArr) > 2) {
                $subdomain = true;
            }
        }

        if ($subdomain) {
            $subdomain = $serverNameArr[0];
        }

        return $subdomain;
    }

    //Загрузка сцены
    public function loadScene($id)
    {
        $location = Location::findOrFail($id);
        return view('pages.index', ['location' => $location]);
    }

    //Поменять город
    public function changeCity($id)
    {
        if (is_numeric($id)) {
            $cities = Cities::where('id', $id)->get();
            if (count($cities) > 0) {
                $cityid = json_encode($cities[0]->id);
                Cookie::queue(Cookie::forever('city', $cityid));
                return redirect('http://' . request()->getHost() . '/' . Lang::locale() . '/?home=1');
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    //Krpano
    public function krpano($index, $id)
    {
        $location = Location::find($id);
        $view = $location->video ? 'video' : 'xml';

        return response(view('partials.' . $view, [
            'location' => $location,
            'index' => $index
        ]));
//        ->header('Content-type', 'text/plain');
    }

    //Krpano panoramic video
    public function krpanoVideo($id)
    {
        $location = Location::find($id);
        return view('partials.video', [
            'location' => $location,
        ]);
    }

    //Создание скриншота
    public function savescreenshot(Request $request)
    {
        $base64img = $request->input('photo');
        if ($base64img) {
            $file = substr($base64img, strpos($base64img, ",") + 1);
            $image = base64_decode($file);
            $png_url = "screenshot-" . time() . ".jpg";
            $path = public_path() . "/screenshots/" . $png_url;
            $success = file_put_contents($path, $image);
            return response()->json(['pngurl' => $png_url]);
        }
    }

    //Отправка письма (Feedback)
    public function formProcessing(Request $request, $id)
    {
        $data = $request->all();
        if ($id == 1) {
            $request->validate([
                'email' => 'required',
                'message' => 'required',
                'select' => 'required'
            ]);
            Mail::send('mails.feedback',
                ['bodymessage' => $data['message'], 'clientmail' => $data['email'], 'clientselect' => $data['select']],
                function ($message) {
                    $message->from('noreply@uzoom.uz', 'Письмо с Uzb360');
                    $message->to('sherzod.nosirov@gmail.com');
                });
        }
    }

    public function ajaxModal()
    {
        $text = '';
        $link = '';
        $iframe = $_GET['frame'];
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
        }
        if (isset($_GET['link'])) {
            $link = $_GET['link'];
        }
        return (String) view('ajax-modal', [
            'iframe' => $iframe,
            'text' => $text,
            'link' => $link,
        ]);
    }

}
