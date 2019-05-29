<?php

namespace App\Http\Controllers;

use App\Location;
use App\Category;
use App\Cities;
use Illuminate\Http\Request;
use DB;
use App\Sky;
use Mail;
use Illuminate\Support\Facades\Cookie;
class HomeController extends Controller

{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {


if (Cookie::has('city')) {
  $defaultlocation = Cookie::get('city');
} else {
$defaultlocation = "1";

  Cookie::queue(Cookie::forever('city', '1'));
}

$cities =  DB::select(DB::raw("SELECT * FROM cities"));
$location = Location::where([['isDefault', '1'],['city_id', $defaultlocation]])->firstOrFail();
if(empty($location->is_sky)) {

if(!empty($location->sky_id)) {

  $sky = Sky::where('id', $location->sky_id)->firstOrFail();

} else {
  $sky = Sky::where([['skymainforcity', 'on'],['city_id', $defaultlocation]])->firstOrFail();

}}   else { $sky = "no";};

$curlocation = Cities::where('id', $defaultlocation)->firstOrFail();
$locationscordinate = DB::select(DB::raw("SELECT l.name, l.id, l.slug, l.lat, l.lng, l.panorama, c.cat_icon, c.cat_icon_svg
FROM locations l, categories c
WHERE l.city_id = $defaultlocation
AND l.onmap = 'on'
AND c.id = l.category_id"));


foreach($locationscordinate as $key=>$value){

 $test = json_decode($locationscordinate[$key]->panorama)[0]->panoramas[0]->panorama;
    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
    $filename = $test . '/' . $old[2];
    $locationscordinate[$key]->img = $filename;
}


$isfeatured = DB::select(DB::raw("SELECT l.name, l.id, l.slug, l.lat, l.lng, l.panorama, c.cat_icon_svg
FROM locations l, categories c
WHERE l.isfeatured = 'on'
AND l.onmap = 'on'
AND l.city_id = $defaultlocation
AND c.id = l.category_id
ORDER BY RAND()
LIMIT 8
"));
$isnew = DB::select(DB::raw("SELECT l.name, l.id, l.slug, l.lat, l.lng, l.panorama, c.cat_icon_svg
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



foreach($isfeatured as $key=>$value){

 $test = json_decode($isfeatured[$key]->panorama)[0]->panoramas[0]->panorama;
    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
    $filename = $test . '/' . $old[2];
    $isfeatured[$key]->img = $filename;
}
$caticon = Category::where('id', $location->category_id)->firstOrFail();
$location->cat_icon = $caticon->cat_icon;
$location->cat_icon_svg = $caticon->cat_icon_svg;
$krhotspots =
 DB::select(DB::raw("SELECT l.name, l.slug, h.*, c.cat_icon, c.cat_icon_svg
FROM locations l, hotspots h, categories c
WHERE h.location_id = ".$location->id."
AND c.id = l.category_id"));
$otherlocations =  DB::select(DB::raw("SELECT l.name, l.id, l.lat, l.lng, l.slug, l.panorama, c.cat_icon_svg
FROM locations l, categories c
WHERE c.id = l.category_id
AND l.city_id = $defaultlocation
ORDER BY RAND()
LIMIT 7
"));


$sss =Location::folderNames($otherlocations);

foreach($otherlocations as $key2=>$value2){
$otherlocations[$key2]->img = $sss[$key2];
}



$array = array_column($krhotspots, 'destination_id');

$krhotspotinfo =DB::table('locations')
                ->join('categories', 'categories.id', '=', 'locations.category_id')
                ->select('locations.name', 'locations.slug', 'locations.id', 'locations.panorama' ,'categories.cat_icon', 'categories.cat_icon_svg')
                ->whereIn('locations.id', $array)
                ->get();





foreach($krhotspots as $key=>$value){

foreach($krhotspotinfo as $key2=>$value2){
 if (json_encode($krhotspots[$key]->destination_id) == json_encode($krhotspotinfo[$key2]->id)) {
    $test = json_decode($krhotspotinfo[$key2]->panorama)[0]->panoramas[0]->panorama;
    $old = scandir(public_path() . '/storage/panoramas/unpacked/' . $test);
    $filename = $test . '/' . $old[2];
 $krhotspots[$key]->img = $filename;
 $krhotspots[$key]->name = $krhotspotinfo[$key2]->name;
 $krhotspots[$key]->slug = $krhotspotinfo[$key2]->slug;
 $krhotspots[$key]->cat_icon = $krhotspotinfo[$key2]->cat_icon;
 $krhotspots[$key]->cat_icon_svg = $krhotspotinfo[$key2]->cat_icon_svg;}

}


}
        $categories = Category::orderBy('id', 'ASC')->get();

        return view('pages.index', ['location' => $location, 'categories' => $categories, 'krhotspots' => $krhotspots, 'otherlocations' => $otherlocations, 'cities' => $cities, 'defaultlocation'=>$defaultlocation, 'isfeatured' => $isfeatured, 'curlocation'=> $curlocation, 'locationscordinate'=> $locationscordinate, 'sky'=> $sky, 'isnew'=> $isnew ]);
    }

    public function loadScene($id) {
        $location = Location::findOrFail($id);

        return view('pages.index', ['location' => $location]);
    }
public function changeCity($id) {
    if (is_numeric($id)) {

$cities =
 DB::select(DB::raw("SELECT * FROM cities WHERE id=".$id.""));
   if(count($cities) > 0){



    $cityid = json_encode($cities[0]->id);


    Cookie::queue(Cookie::forever('city', $cityid));

return redirect('/');
} else {return redirect('/');};
    } else {
          return redirect('/');

    }
}
    public function krpano($index, $id) {
        $location = Location::find($id);

        return view('partials.xml', ['location' => $location, 'index' => $index]);
    }
    public function savescreenshot(Request $request) {
$base64img = $request->input('photo');
      if($base64img){
 $file = substr($base64img, strpos($base64img ,",")+1);
 $image = base64_decode($file);
 $png_url = "screenshot-".time().".jpg";
 $path = public_path() . "/screenshots/" . $png_url;
$success = file_put_contents($path, $image);

     return response()->json(['pngurl' => $png_url]);

         }

    }

    public function formProcessing(Request $request, $id) {
        $data = $request->all();

        if($id == 1) {
            $request->validate([
                'email' => 'required',
                'message' => 'required',
                'select' => 'required'
            ]);

            Mail::send('mails.feedback', ['bodymessage'=>$data['message'], 'clientmail'=>$data['email'], 'clientselect'=>$data['select']], function ($message) {
    $message->from('noreply@uzoom.uz', 'Письмо с Uzb360');

    $message->to('sherzod.nosirov@gmail.com');
});


        }
    }
}
