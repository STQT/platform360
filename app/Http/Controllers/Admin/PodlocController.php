<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Podloc;
use App\Category;
use App\Cities;
use App\Sky;
use App\Hotspot;
use App\Location;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Redirect;
use DB;
class PodlocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index($id)
    {

        $parentid = $id;
        $perPage = 25;
        $check = Location::where('id', $parentid)->first();

        if (empty($check)) {
        return Redirect::back()->withErrors(['msg', 'The Message']);
        } else {
            $podlocs = Podloc::where('podlocparent_id', $parentid)->orderBY('id', 'ASC')->paginate($perPage);
        }

        return view('admin.podloc.index', ['podlocs'=>$podlocs, 'parentid'=>$parentid, 'parentname'=>$check['name']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
      $parentid = $id;
      $sky = Location::where('id', $parentid)->first();
      $skyy = Sky::where('is_sky', 'on')->get();
    $sky->podlocparent_id = $sky->id;
      if (empty($sky) || is_null($sky)) {
      return Redirect::back()->withErrors(['msg', 'The Message']);
      } else {
        $cities = Cities::all();
        $categories = Category::all();
      return view('admin.podloc.create', compact('sky','skyy','cities', 'categories'));
      }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
     public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
         foreach ($files as $file) {
           (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
         }
         return rmdir($dir);
     }
    public function store(Request $request)
    {
  app()->setLocale('ru');

    $this->validate($request, [
			'name' => 'required',
			'panorama' => 'required',
			'city_id' => 'required'
		]);

        $data = $request->all();
        $requestData = $request->all();

      $requestData['podlocparent_id'] = $requestData['parrentid'] ;

        $requestData['slug'] = Location::transliterate( $requestData['name']).str_random(3);

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




        if(!empty($panoramas)) {

            $requestData['panorama'] = json_encode($panoramas);

            $sky = Podloc::create($requestData);

            return redirect('admin/podloc/'.$requestData['parrentid'].'')->with('flash_message', 'Подлокация добавлена!');
        }
        else {
            return redirect()->back()->withErrors('Корректно заполните форму ниже');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */


    public function show($id)
    {
      $location = Sky::findOrFail($id);
      $locations = Sky::all();

      $categories = Category::all();

      return view('pages.admin.edit', ['location' => $location, 'locations' => $locations, 'categories' => $categories]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id, $language)
    {
  app()->setLocale($language);

        $cities = Cities::all();
        $categories = Category::all();
          $skyy = Sky::where('is_sky', 'on')->get();
$sky = Location::findOrFail($id);

        return view('admin.podloc.edit', compact('skyy','sky','cities', 'categories', 'language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id, $language)
    {
        $this->validate($request, [
			'name' => 'required',

			'city_id' => 'required'
		]);
      app()->setLocale($language);
          $data = $request->all();
          $requestData = $request->all();

          $requestData['podlocparent_id'] = $requestData['parrentid'] ;
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


  if(!empty($requestData['name'])) {

    $location = Location::findOrFail($id);
          $location->update($requestData);
  return redirect('admin/podloc/'.$data['parrentid'].'')->with('flash_message', 'Подлокация обновлена!');
}  else {
      return redirect()->back()->withErrors('Корректно заполните форму ниже');
  }




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Sky::destroy($id);
        Location::where('podlocparent_id', $id)->delete();
        Hotspot::where('location_id', $id)->delete();
        Hotspot::where('destination_id', $id)->delete();
        Location::where('sky_id', $id)->update(array('sky_id' => ''));
        return redirect('admin/sky')->with('flash_message', 'Небо удалено!');
    }
}
