<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Floors;
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
class FloorsController extends Controller
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
            $floors = Floors::where('parrentid', $parentid)->orderBY('id', 'ASC')->paginate($perPage);
        }

        return view('admin.floors.index', ['floors'=>$floors, 'parentid'=>$parentid, 'parentname'=>$check['name']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
      $parentid = $id;
      $parentid = Location::where('id', $parentid)->first();

      if (empty($parentid) || is_null($parentid)) {
      return Redirect::back()->withErrors(['msg', 'The Message']);
      } else {

      return view('admin.floors.create', compact('parentid'));
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
        $this->validate($request, [
			'name' => 'required',
			'image' => 'required',
			'parrentid' => 'required'
		]);

        $data = $request->all();
        $requestData = $request->all();

        if(!empty($data["image"])) {
            if( request()->hasFile('image') && request()->file('image')->isValid()) {
               $file2 = request()->file('image');
               $fileName2 = md5($file2->getClientOriginalName() . time()) . "." . $file2->getClientOriginalExtension();
               $file2->move(public_path() . '/storage/floors/', $fileName2);
            }

             Floors::create([
                'name' => request()->get('name'),
                'image' => $fileName2,
                'parrentid' => request()->get('parrentid'),
                'code' => ''
             ]);

            return redirect('admin/floors/'.$requestData['parrentid'].'')->with('flash_message', 'Подлокация добавлена!');
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
    public function tochki($id)
    {
      $Floor = Floors::findOrFail($id);
     $locations = Location::where('podlocparent_id', $Floor["parrentid"])->get();

      return view('admin.floors.show', ['floor' => $Floor, 'locations'=> $locations]);
    }
    public function tochkiupdate(Request $request, $id)
    {
      $requestData = $request->all();
          

    Floors::where('id', $id)->update(array('code' => $requestData['codeetaj']));

      return redirect('admin/floors/'.$requestData['parrentid'].'')->with('flash_message', 'Этаж обновлен!');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {



$floor = Floors::findOrFail($id);
$parentid = Location::where('id', $floor['parrentid'])->first();

        return view('admin.floors.edit', compact('floor','parentid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
			'name' => 'required',
		]);
          $data = $request->all();
          $requestData = $request->all();


                            if(!empty($data["image"])) {

                                      if( request()->hasFile('image') && request()->file('image')->isValid()) {
                                       $file2 = request()->file('image');
                                       $fileName2 = md5($file2->getClientOriginalName() . time()) . "." . $file2->getClientOriginalExtension();
                                       $file2->move(public_path() . '/storage/floors/', $fileName2);
                                       $requestData["image"] = $fileName2;
                                   }}

  if(!empty($requestData['name'])) {

    $floor = Floors::findOrFail($id);
          $floor->update($requestData);
  return redirect('admin/floors/'.$data['parrentid'].'')->with('flash_message', 'Подлокация обновлена!');
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
        $parrentid = Floors::where('id', $id)->first();
        Floors::destroy($id);

        return redirect('admin/floors/'.$parrentid["parrentid"])->with('flash_message', 'Этаж удален!');
    }
}
