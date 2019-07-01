<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Cities;
use App\Hotspot;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Location;
use App\Sky;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $category = $request->get('category');
        $perPage = 25;

        $totalLocations = Location::withoutGlobalScope('published')->count();
        $categories = Category::pluck('name', 'id');

        if (!empty($keyword) && $category == '') {
            $locations = Location::where('is_sky', '!=' , 'on')->whereNull('podlocparent_id')->where('name', 'LIKE', "%$keyword%")
                ->orWhere('address', 'LIKE', "%$keyword%")
                ->orWhere('number', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->orWhere('working_hours', 'LIKE', "%$keyword%")
                ->orWhere('website', 'LIKE', "%$keyword%")
                ->orWhere('facebook', 'LIKE', "%$keyword%")
                ->orWhere('instagram', 'LIKE', "%$keyword%")
                ->orWhere('telegram', 'LIKE', "%$keyword%")
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        } elseif ($category != '') {
            $locations = Location::
                where('category_id', $category)
                ->where('is_sky', '!=' , 'on')
                ->whereNull('podlocparent_id')
                ->where(function($q) use ($keyword) {
                     $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('address', 'LIKE', "%$keyword%")
                        ->orWhere('number', 'LIKE', "%$keyword%")
                        ->orWhere('description', 'LIKE', "%$keyword%")
                        ->orWhere('working_hours', 'LIKE', "%$keyword%")
                        ->orWhere('website', 'LIKE', "%$keyword%")
                        ->orWhere('facebook', 'LIKE', "%$keyword%")
                        ->orWhere('instagram', 'LIKE', "%$keyword%")
                        ->orWhere('telegram', 'LIKE', "%$keyword%");
                 })
                ->withoutGlobalScope('published')
                ->latest()->paginate($perPage);
        }
            else {
            $locations = Location::where('is_sky', '!=' , 'on')->whereNull('podlocparent_id')->latest()->withoutGlobalScope('published')->paginate($perPage);
        }

        return view('admin.locations.index', compact(
            'locations', 
            'totalLocations',
            'categories',
            'category'
        ));
    }

    public function hasFloors($id) {
        if (strpos($id, ':') !== false) {
            $tmp = explode(':', $id);

            $location = Location::find($tmp[1]);

            if(!empty($location)) {
                $tmp = json_decode($location->panorama);

                if(count($tmp) > 1) {
                    return 1;
                }
            }
        }

        return 0;
    }

    public function getDirectory($id) {
        $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $id);
        foreach ($old as $item){
          if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$id.'/' . $item)){
              $filename = $id . '/' . $item;

          }
        }



        return $filename;
    }

public function search(Request $search, $categories) {
  if (Cookie::has('city')) {
    $defaultlocation = Cookie::get('city');
  } else {
  $defaultlocation = "1";

    Cookie::queue(Cookie::forever('city', '1'));
  }

$search = request()->route('search');
if (request()->route('search') == "noresult") {
  $categories = array_map('intval', explode(',', request()->route('categories')));
$results = Location::where('city_id','=', $defaultlocation)->whereNull('podlocparent_id')->whereIN('category_id', $categories)->get();
} else {

if (request()->route('categories') == 0)  {

$results = Location::where('city_id','=', $defaultlocation)->whereNull('podlocparent_id')->where('name', 'LIKE', '%' . $search . '%')->get();

}
else {
$categories = array_map('intval', explode(',', request()->route('categories')));
$results = Location::where('city_id', '=', $defaultlocation)->whereNull('podlocparent_id')->where('name', 'LIKE', '%' . $search . '%')->whereIn('category_id', $categories)->get();
}
}




        if($results->count()) {

foreach($results as $key2=>$value2){
    $caticon = Category::where('id', $results[$key2]->category_id)->firstOrFail();
$results[$key2]->cat_icon = $caticon->cat_icon;
$results[$key2]->color = $caticon->color;
$results[$key2]->cat_icon_svg = $caticon->cat_icon_svg;
}


            return response()->json($results);
        }
        else {
            return response()->json('Null');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $sky = Location::where('is_sky', 'on')->get();
        return view('admin.locations.create', ['categories' => Category::all(), 'cities' => Cities::all(), 'sky' => $sky]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'panorama' => 'file'
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

        if(!empty($data['panorama'])) {
            $randomStr = Str::random(40);
            $file = $data['panorama']->store('panoramas');
            $fullPath = public_path() . '/storage/' . $file;
            $baseName = pathinfo($file);
            $baseName = $baseName['filename'];
            $panoDir = public_path() . '/storage/panoramas/vtour/panos/' . $baseName . '.tiles';
            $command = exec('"/opt/krpano/krpanotools" makepano -config=templates/vtour-multires.config -panotype=sphere -askforxmloverwrite=false ' . $fullPath);
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
    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $location = Location::withoutGlobalScope('published')->findOrFail($id);
        $locations = Location::withoutGlobalScope('published')->get()->all();

        $categories = Category::all();

        return view('pages.admin.edit', ['location' => $location, 'locations' => $locations, 'categories' => $categories]);
    }
    public function show2($slug)
    {

      if (Cookie::has('city')) {
        $defaultlocation = Cookie::get('city');
      } else {
      $defaultlocation = "1";

        Cookie::queue(Cookie::forever('city', '1'));
      }

     $location = Location::where('slug', $slug)->firstOrFail();
     if(empty($location->is_sky)) {

     if(!empty($location->sky_id)) {

       $location->skyslug = Sky::where('id', $location->sky_id)->pluck('slug')->first();

     } else {
       $location->skyslug = Sky::where([['skymainforcity', 'on'],['city_id', $defaultlocation]])->pluck('slug')->first();
       $location->sky_id = Sky::where([['skymainforcity', 'on'],['city_id', $defaultlocation]])->pluck('id')->first();

     }}   else { $location->skyslug = "no";};
        return $location;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $location = Location::withoutGlobalScope('published')->findOrFail($id);

        $categories = Category::all();
        $sky = Location::where('is_sky', 'on')->get();
        $cities = Cities::all();

        $returnUrl = Input::get('returnUrl');

        if ($returnUrl)
          $request->session()->put('returnUrl', $returnUrl);

        return view('admin.locations.edit', compact('location', 'sky','categories', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $location = Location::withoutGlobalScope('published')->findOrFail($id);

        $data = $request->all();

        $requestData = $request->all();

        if(!empty($data['isDefault'])) {
            $requestData['isDefault'] = 1;
        } else {

            $requestData['isDefault'] = 0;
        }

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
                    $command = exec('"/opt/krpano/krpanotools" makepano -config=templates/vtour-multires.config -panotype=sphere -askforxmloverwrite=false ' . $fullPath);
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


        if(!empty($requestData['name'])) {
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
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Location::where('podlocparent_id', $id)->withoutGlobalScope('published')->delete();
        Hotspot::where('location_id', $id)->delete();
        Hotspot::where('destination_id', $id)->delete();
        Location::where('sky_id', $id)->update(array('sky_id' => ''));

        Location::where('id', $id)->withoutGlobalScope('published')->delete();

        return redirect('admin/locations')->with('flash_message', 'Location deleted!');
    }

    public function generatePano($slug)
    {


      if (Cookie::has('city')) {
        $defaultlocation = Cookie::get('city');
      } else {
      $defaultlocation = "1";

        Cookie::queue(Cookie::forever('city', '1'));
      }

        $cities =  DB::select(DB::raw("SELECT * FROM cities"));

       $location = Location::where('slug', $slug)->firstOrFail();
       $etaji = $location->etaji;
       if (!empty($etaji)) {
         $code = "";
       foreach ($etaji as $ss => $etaj) {
         $code .= $etaji[$ss]->code;
       }

       preg_match_all ('/location : "([0-9]+)"/', $code, $matches);

       $etajlocations =DB::table('locations')
                       ->join('categories', 'categories.id', '=', 'locations.category_id')
                       ->select('locations.name', 'locations.slug', 'locations.id', 'locations.panorama' ,'categories.cat_icon', 'categories.cat_icon_svg', 'categories.color')
                       ->whereIn('locations.id', $matches[1])
                       ->get();
                       foreach($etajlocations as $key=>$value){

                        $test = json_decode($etajlocations[$key]->panorama)[0]->panoramas[0]->panorama;
                           $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
                           $filename = $test . '/' . $old[2];
                           $etajlocations[$key]->img = $filename;
                       }

       }



       if(empty($location->is_sky)) {

       if(!empty($location->sky_id)) {

         $sky = Sky::where('id', $location->sky_id)->firstOrFail();

       } else {
         $sky = Sky::where([['skymainforcity', 'on'],['city_id', $defaultlocation]])->firstOrFail();

       }}   else { $sky = "no";};


        $caticon = Category::where('id', $location->category_id)->firstOrFail();
$location->color = $caticon->color;
        $location->cat_icon = $caticon->cat_icon;
        $location->cat_icon_svg = $caticon->cat_icon_svg;

$krhotspots =
 DB::select(DB::raw("SELECT *
FROM hotspots
WHERE location_id = ".$location->id."
"));


$otherlocations =  DB::select(DB::raw("SELECT l.name, l.id, l.lat, l.lng, l.slug, l.panorama, c.cat_icon_svg, c.color
FROM locations l, categories c
WHERE c.id = l.category_id
AND l.city_id = $defaultlocation
ORDER BY RAND()
LIMIT 7
"));

$isfeatured = DB::select(DB::raw("SELECT l.name, l.id, l.slug, l.lat, l.lng, l.panorama, c.cat_icon_svg, c.color
FROM locations l, categories c
WHERE l.isfeatured = 'on'
AND l.onmap = 'on'
AND l.city_id = $defaultlocation
AND c.id = l.category_id
ORDER BY RAND()
LIMIT 8
"));
$isnew = DB::select(DB::raw("SELECT l.name, l.id, l.slug, l.lat, l.lng, l.panorama, c.cat_icon_svg, c.color
FROM locations l, categories c
WHERE l.onmap = 'on'
AND l.city_id = $defaultlocation
AND c.id = l.category_id
ORDER BY RAND()
LIMIT 8
"));
foreach($isnew as $key=>$value){

 $test = json_decode($isnew[$key]->panorama)[0]->panoramas[0]->panorama;
    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
    foreach ($old as $item){
      if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)){
          $filename = $test . '/' . $item;
            $isnew[$key]->img = $filename;
      }
    }
}
$curlocation = Cities::where('id', $defaultlocation)->firstOrFail();
$locationscordinate = DB::select(DB::raw("SELECT l.name, l.id, l.slug, l.lat, l.lng, l.panorama, c.cat_icon, c.cat_icon_svg, c.color
FROM locations l, categories c
WHERE l.city_id = $defaultlocation
AND l.onmap = 'on'
AND c.id = l.category_id"));


foreach($locationscordinate as $key=>$value){

 $test = json_decode($locationscordinate[$key]->panorama)[0]->panoramas[0]->panorama;
    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
    foreach ($old as $item){
      if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)){
          $filename = $test . '/' . $item;
      $locationscordinate[$key]->img = $filename;
      }
    }



}

foreach($isfeatured as $key=>$value){

 $test = json_decode($isfeatured[$key]->panorama)[0]->panoramas[0]->panorama;
    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
    foreach ($old as $item){
      if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)){
          $filename = $test . '/' . $item;
            $isfeatured[$key]->img = $filename;
      }
    }


}
$sss =Location::folderNames($otherlocations);

foreach($otherlocations as $key2=>$value2){
$otherlocations[$key2]->img = $sss[$key2];
}
if(Cookie::get('city') != null) {$defaultlocation = Cookie::get('city');} else {$defaultlocation = Cookie::queue(Cookie::forever('city', '1'));};


$array = array_column($krhotspots, 'destination_id');

$krhotspotinfo =DB::table('locations')
                ->join('categories', 'categories.id', '=', 'locations.category_id')
                ->select('locations.name', 'locations.slug', 'locations.id', 'locations.panorama' ,'categories.cat_icon', 'categories.cat_icon_svg', 'categories.color')
                ->whereIn('locations.id', $array)
                ->get();


foreach($krhotspots as $key=>$value){

foreach($krhotspotinfo as $key2=>$value2){
 if (json_encode($krhotspots[$key]->destination_id) == json_encode($krhotspotinfo[$key2]->id)) {
    $test = json_decode($krhotspotinfo[$key2]->panorama)[0]->panoramas[0]->panorama;
    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
    foreach ($old as $item){
      if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)){
          $filename = $test . '/' . $item;
           $krhotspots[$key]->img = $filename;
      }
    }







 $krhotspots[$key]->name = $krhotspotinfo[$key2]->name;
 $krhotspots[$key]->slug = $krhotspotinfo[$key2]->slug;
 $krhotspots[$key]->cat_icon = $krhotspotinfo[$key2]->cat_icon;
 $krhotspots[$key]->color = $krhotspotinfo[$key2]->color;
 $krhotspots[$key]->cat_icon_svg = $krhotspotinfo[$key2]->cat_icon_svg;}

}


}

        $categories = Category::orderBy('id', 'ASC')->get();



        if($location->count()) {
            return view('pages.index', ['location' => $location, 'categories' => $categories, 'krhotspots' => $krhotspots,'defaultlocation'=> $defaultlocation, 'otherlocations' => $otherlocations, 'isfeatured'=>$isfeatured, 'cities'=>$cities,  'curlocation'=> $curlocation, 'locationscordinate'=> $locationscordinate, 'sky' => $sky, 'isnew' => $isnew, 'etaji' => $etaji, 'etajlocations'=> $etajlocations]);
        }
        else {
            return response()->json([]);
        }

        return view('partials.admin.xml', ['location' => $location]);
    }
    public function krpano($id)
    {
        $location = Location::withoutGlobalScope('published')->find($id);


           return response()->view('partials.admin.xml', compact('location'))->header('Content-Type', 'text/xml');
    }

    public function apiLocations($id)
    {
        $category = Category::findOrFail($id);

        $locations = $category->locations()->withoutGlobalScope('published')->orderBy('created_at', 'DESC')->paginate(999);

        return $locations;
    }

    public function apiSublocations($id)
    {
        $location = Location::find($id);
        $locations = $location->sublocations()->orderBy('created_at', 'DESC')->paginate(999);

        return $locations;
    }
    
    public function getcitydefaultlocation($id)
    {


        $locations = Location::where('isDefault', 1)->where('city_id', $id)->first();

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

    public function apiHotspots($id)
    {


$krhotspots =
 DB::select(DB::raw("SELECT *
FROM hotspots
WHERE location_id = ".$id."
"));


$array = array_column($krhotspots, 'destination_id');

$krhotspotinfo =DB::table('locations')
                ->join('categories', 'categories.id', '=', 'locations.category_id')
                ->select('locations.name', 'locations.slug', 'locations.id', 'locations.panorama' ,'categories.cat_icon', 'categories.cat_icon_svg', 'categories.color')
                ->whereIn('locations.id', $array)
                ->get();





foreach($krhotspots as $key=>$value){

foreach($krhotspotinfo as $key2=>$value2){
 if (json_encode($krhotspots[$key]->destination_id) == json_encode($krhotspotinfo[$key2]->id)) {
    $test = json_decode($krhotspotinfo[$key2]->panorama)[0]->panoramas[0]->panorama;
    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
    foreach ($old as $item){
      if (is_dir(public_path() . '/storage/panoramas/unpacked/'.$test.'/' . $item)){
          $filename = $test . '/' . $item;
         $krhotspots[$key]->img = $filename;
      }
    }


 $krhotspots[$key]->name = $krhotspotinfo[$key2]->name;
 $krhotspots[$key]->slug = $krhotspotinfo[$key2]->slug;
 $krhotspots[$key]->cat_icon = $krhotspotinfo[$key2]->cat_icon;
 $krhotspots[$key]->color = $krhotspotinfo[$key2]->color;
 $krhotspots[$key]->cat_icon_svg = $krhotspotinfo[$key2]->cat_icon_svg;}

}


}
        return  $krhotspots;

    }
}
