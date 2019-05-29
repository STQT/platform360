<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CitiesController extends Controller
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
            $cities = Cities::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $cities = Cities::orderBY('position', 'ASC')->paginate($perPage);
        }

        return view('admin.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.cities.create');
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
			'lat' => 'required',
			'lng' => 'required',
            'position' => 'required'
		]);
        $requestData = $request->all();
        
        Cities::create($requestData);

        return redirect('admin/cities')->with('flash_message', 'City added!');
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
        $cities = Cities::findOrFail($id);

        return view('admin.cities.show', compact('cities'));
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
        $cities = Cities::findOrFail($id);

        return view('admin.cities.edit', compact('cities'));
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
			'lat' => 'required',
			'lng' => 'required',
            'position' => 'required'
		]);
          $data = $request->all();

           

        $requestData = $request->all();
         if(!empty($data['is_default'])) {
            $requestData['is_default'] = 1;
        }
   else {
    $requestData['is_default'] = 0;
   }
        $cities = Cities::findOrFail($id);
        $cities->update($requestData);

        return redirect('admin/cities')->with('flash_message', 'City updated!');
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
        Cities::destroy($id);

        return redirect('admin/cities')->with('flash_message', 'City deleted!');
    }
}
