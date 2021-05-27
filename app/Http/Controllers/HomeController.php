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
use Illuminate\Support\Facades\Log;
use Mail;
use Illuminate\Support\Facades\Cookie;
use Spatie\Translatable\HasTranslations;

class HomeController extends Controller

{
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

        //Загрузка всех городов и координаты текущего города
        $cities = Cities::all();
        $curlocation = Cities::where('id', $defaultlocation)->firstOrFail();

        //Загрузка основноч точки
        //обработка субдоменов и локация по умолчанию
        $subdomain = $this->getSubdomainName();
        if ($subdomain && $subdomain != 'dev' && !is_numeric($subdomain)) {
            $city = Cities::where('subdomain', $subdomain)->first();
            if ($city) {
                $location = Location::where([
                    ['city_id', $city->id],
                    ['isDefault', '1']
                ])->with('categorylocation')->firstOrFail();
                Cookie::queue(Cookie::forever('city', $city->id));
            } else {
                $subdomainLocation = Location::where('subdomain', $subdomain)->with('categorylocation')->firstOrFail();
                if (!Input::get('home')) {
                    Cookie::queue(Cookie::forever('city', '1'));
                    $location = $subdomainLocation;
                } else {
                    //панорама по умолчанию для города
                    $location = Location::where([
                        ['isDefault', '1'],
                        ['city_id', $subdomainLocation->city_id]
                    ])->with('categorylocation')->firstOrFail();
                }
            }
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
            Log::info(print_r($sss, true));
            foreach ($locationscordinate as $key2 => $value2) {
                $locationscordinate[$key2]->img = $sss[$key2];
            }
            $locationscordinate = Location::transl($locationscordinate);
        }

        //Загрузка избранных точек для карты
        $isfeatured = Location::where('isfeatured', 'on')->where('onmap', 'on')->where('city_id',
            $defaultlocation)->where('onmap', 'on')->with('categorylocation')->orderBy('id', 'DESC')->limit(8)->get();
        if ($isfeatured->isNotEmpty()) {
            $sss = Location::folderNames($isfeatured);
            foreach ($isfeatured as $key2 => $value2) {
                $isfeatured[$key2]->img = $sss[$key2];
            }
        }

        //Загрузка новых точек для карты
        $isnew = Location::where('onmap', 'on')->where('city_id', $defaultlocation)->where('onmap',
            'on')->with('categorylocation')->inRandomOrder()->limit(8)->get();
        if ($isnew->isNotEmpty()) {
            $sss = Location::folderNames($isnew);
            foreach ($isnew as $key2 => $value2) {
                $isnew[$key2]->img = $sss[$key2];
            }
        }

        //Загрузка хотспотов основной точки
        $krhotspots = Hotspot::where('location_id', $location->id)->with('destination_locations')->get();
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
            Log::info($otherlocations[$key2]);
            $otherlocations[$key2]->img = $sss[$key2];
        }

        //Загрузка всех категорий
        $categories = Category::whereHas('locations', function ($q) use ($defaultlocation) {
            $q->where('city_id', $defaultlocation);
            $q->where('published', 1);
            $q->whereNull('podlocparent_id');
        })->orderBy('id', 'ASC')->get();

        $openedCategory = null;

        if (!empty($category)) {
            $openedCategory = Category::where('slug', 'LIKE', "%$category%")->whereNotNull('slug')->first();
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
                return redirect('http://' . request()->getHost() . '/');
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
        return view('partials.' . $view, [
            'location' => $location,
            'index' => $index
        ]);
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

}
