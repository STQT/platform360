<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Sky;
use App\Category;
use App\Cities;
use App\Hotspot;
use App\Location;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
class SkyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $sky = Sky::where('is_sky','on')->where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $sky = Sky::where('is_sky','on')->orderBY('id', 'ASC')->paginate($perPage);
        }

        return view('admin.sky.index', compact('sky'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $cities = Cities::all();
        return view('admin.sky.create', compact('cities'));
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
        app()->setLocale('ru');
        $this->validate($request, [
            'name' => 'required',
            'panorama' => 'required',
            'city_id' => 'required'
        ]);

        $data = $request->all();
        $requestData = $request->all();
        $requestData['is_sky'] = 'on';

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
            $xmllocation = '' . $randomStr . '/' . $baseName . '.tiles';
            $xmldata = simplexml_load_file(public_path() . '/storage/panoramas/unpacked/'.$randomStr.'/tour.xml');
            $d = "";
            foreach ($xmldata->scene->children() as $child){ $d .= $child->asXML();}
            $requestData['xmllocation'] = preg_replace('/panos[\s\S]+?tiles/', '/storage/panoramas/unpacked/'.$xmllocation.'', $d);;
            $panoramas = [['panoramas' => [['panorama' => $randomStr]]]];
        }


        if(!empty($panoramas)) {
            $requestData['panorama'] = json_encode($panoramas);

            $sky = Sky::create($requestData);

            return redirect('admin/sky')->with('flash_message', 'Небо добавлено!');
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
    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

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
        $sky = Sky::findOrFail($id);
        return view('admin.sky.edit', compact('sky','cities', 'language'));
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
            $xmllocation = '' . $randomStr . '/' . $baseName . '.tiles';
            $xmldata = simplexml_load_file(public_path() . '/storage/panoramas/unpacked/'.$randomStr.'/tour.xml');
            $d = "";
            foreach ($xmldata->scene->children() as $child){ $d .= $child->asXML();}
            $requestData['xmllocation'] = preg_replace('/panos[\s\S]+?tiles/', '/storage/panoramas/unpacked/'.$xmllocation.'', $d);;
            $panoramas = [['panoramas' => [['panorama' => $randomStr]]]];
        }


        if(!empty($panoramas)) {
            $requestData['panorama'] = json_encode($panoramas);
        };
        if(!empty($data['skymainforcity'])) {
            $requestData['skymainforcity'] = 'on';
        }
        else {
            $requestData['skymainforcity'] = 0;
        }

        if(empty($data['published'])) {
            $requestData['published'] = 0;
        }

        $sky = Sky::findOrFail($id);
        $sky->update($requestData);

        return redirect('admin/sky')->with('flash_message', 'Небо обновлено!');
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
        Location::withoutGlobalScope('published')->where('podlocparent_id', $id)->delete();
        Hotspot::where('location_id', $id)->delete();
        Hotspot::where('destination_id', $id)->delete();
        Location::withoutGlobalScope('published')->where('sky_id', $id)->update(array('sky_id' => ''));
        return redirect('admin/sky')->with('flash_message', 'Небо удалено!');
    }
}
