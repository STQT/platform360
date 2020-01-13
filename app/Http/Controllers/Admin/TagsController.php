<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TagsController extends Controller
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
            $tags = Tag::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $tags = Tag::orderBY('id', 'ASC')->paginate($perPage);
        }

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.tags.create');
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
		]);
        $requestData = $request->all();

        Tag::create($requestData);

        return redirect('admin/tags')->with('flash_message', 'Tag added!');
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
        $tags = Tag::findOrFail($id);

        return view('admin.tags.show', compact('tags'));
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
        $tags = Tag::findOrFail($id);

        return view('admin.tags.edit', compact('tags', 'language'));
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
        ]);
        $data = $request->all();
        app()->setLocale($language);


        $requestData = $request->all();
         if(!empty($data['is_default'])) {
            $requestData['is_default'] = 1;
         } else {
             $requestData['is_default'] = 0;
         }
        $tags = Tag::findOrFail($id);
        $tags->update($requestData);

        return redirect('admin/tags')->with('flash_message', 'Tag updated!');
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
        Tag::destroy($id);

        return redirect('admin/tags')->with('flash_message', 'Tag deleted!');
    }
}
