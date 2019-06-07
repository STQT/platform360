<?php

namespace App\Http\Controllers\ADmin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Hotspot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotspotsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.hotspots.create');
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
			'location_id' => 'required',
			'destination_id' => 'required',
			'h' => 'required',
			'v' => 'required'
		]);
        $requestData = $request->all();

        Hotspot::create($requestData);

        return redirect('admin/hotspots')->with('flash_message', 'Hotspot added!');
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
        $hotspot = Hotspot::findOrFail($id);

        return view('admin.hotspots.show', compact('hotspot'));
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
        $hotspot = Hotspot::findOrFail($id);

        return view('admin.hotspots.edit', compact('hotspot'));
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
			'location_id' => 'required',
			'destination_id' => 'required',
			'h' => 'required',
			'v' => 'required'
		]);
        $requestData = $request->all();

        $hotspot = Hotspot::findOrFail($id);
        $hotspot->update($requestData);

        return redirect('admin/hotspots')->with('flash_message', 'Hotspot updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
     public function deletehotspot($id) {
       if (Auth::check()) {
         Hotspot::destroy($id);
         return 'ok';
       } else {
         return 'no';
       }
     }
    public function destroy($id)
    {
        Hotspot::destroy($id);

        return redirect('admin/hotspots')->with('flash_message', 'Hotspot deleted!');
    }
}
