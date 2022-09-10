<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Location;
use App\Video;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $perPage = 25;

        $videos = Video::where('location_id', $id)->paginate($perPage);

        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.videos.create');
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
        ]);
        $requestData = $request->all();

        Video::create($requestData);

        return redirect('admin/videos')->with('flash_message', 'Video added!');
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
        $videos = Video::findOrFail($id);

        return view('admin.videos.show', compact('videos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id, $lang)
    {
        app()->setLocale($lang);

        $video = Video::findOrFail($id);

        return view('admin.videos.edit', compact('video', 'lang'));
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
//        $this->validate($request, [
//            'name' => 'required',
//        ]);
        $data = $request->all();
        app()->setLocale($data['lang']);

        $requestData = $request->all();

         if(!empty($data['is_default'])) {
            $requestData['is_default'] = 1;
         } else {
             $requestData['is_default'] = 0;
         }

        $videoFile = $request->file('video');
        $newName = rand() . '.' . $videoFile->getClientOriginalExtension();
        $videoFile->move(public_path('storage/videos'), $newName);

        $video = Video::findOrFail($id);
        $video->hfov = str_replace(',', '.', $data['hfov']);
        $video->yaw = str_replace(',', '.', $data['yaw']);
        $video->pitch = str_replace(',', '.', $data['pitch']);
        $video->roll = str_replace(',', '.', $data['roll']);
        $video->video = $newName;
        $video->save();

        return redirect('admin/videos/' . $video->location->id)->with('flash_message', 'Video updated!');
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
        if (!auth()->user()->hasRole('Admin')) {
            return redirect('admin/locations')->with('flash_message', 'У вас нет прав для удаление!');
        }

        $video = Video::find($id);
        @unlink(public_path('storage/videos/') . $video->video);
        Video::destroy($id);

        return redirect()->route('admin.videos', ['id' => $video->location_id])->with('flash_message', 'Video deleted!');
    }
}
