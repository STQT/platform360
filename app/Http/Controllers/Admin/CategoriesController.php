<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class CategoriesController extends Controller
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
            $categories = Category::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $categories = Category::latest()->paginate($perPage);
        }

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
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
            'cat_icon' => 'required',
            'cat_icon_svg' => 'required',
            'cat_photo' => 'required',
        ]);
        $requestData = $request->all();

        $fileName = null;
        $fileName2 = null;
        $fileName3 = null;

        if (request()->hasFile('cat_icon') && request()->file('cat_icon')->isValid()) {
            $file = request()->file('cat_icon');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move(public_path() . '/storage/cat_icons/', $fileName);
        }
        if (request()->hasFile('cat_icon_svg') && request()->file('cat_icon_svg')->isValid()) {
            $file2 = request()->file('cat_icon_svg');
            $fileName2 = md5($file2->getClientOriginalName() . time()) . "." . $file2->getClientOriginalExtension();
            $file2->move(public_path() . '/storage/cat_icons/', $fileName2);
        }

        if (request()->hasFile('cat_photo') && request()->file('cat_photo')->isValid()) {
            $file3 = request()->file('cat_photo');
            $fileName3 = md5($file3->getClientOriginalName() . time()) . "." . $file3->getClientOriginalExtension();
            $file3->move(public_path() . '/storage/cat_icons/', $fileName3);
        }

        $category = Category::create([
            'name' => request()->get('name'),
            'color' => request()->get('color'),
            'cat_icon' => $fileName,
            'cat_icon_svg' => $fileName2,
            'cat_photo' => $fileName3,
        ]);

        $meta = \App\Meta::create($requestData['meta']);
        $category->meta_id = $meta->id;
        $category->save();

        return redirect('admin/categories')->with('flash_message', 'Category added!');
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
        $category = Category::findOrFail($id);

        return view('admin.categories.show', compact('category'));
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

        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $category->id)->get();

        return view('admin.categories.edit', compact('category', 'language', 'categories'));
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
            'cat_icon' => 'sometimes | required',
            'cat_icon_svg' => 'sometimes | required',
            'cat_photo' => 'sometimes | required'
        ]);
        $requestData = $request->all();

        if (!empty($requestData['cat_icon']) && request()->hasFile('cat_icon') && request()->file('cat_icon')->isValid()) {
            $file = request()->file('cat_icon');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move(public_path() . '/storage/cat_icons/', $fileName);
            $requestData['cat_icon'] = $fileName;
        }
        if (!empty($requestData['cat_icon_svg']) && request()->hasFile('cat_icon_svg') && request()->file('cat_icon_svg')->isValid()) {
            $file2 = request()->file('cat_icon_svg');
            $fileName2 = md5($file2->getClientOriginalName() . time()) . "." . $file2->getClientOriginalExtension();
            $file2->move(public_path() . '/storage/cat_icons/', $fileName2);
            $requestData['cat_icon_svg'] = $fileName2;
        }
        if (!empty($requestData['cat_photo']) && request()->hasFile('cat_photo') && request()->file('cat_photo')->isValid()) {
            $file3 = request()->file('cat_photo');
            $filename3 = md5($file3->getClientOriginalName() . time()) . "." . $file3->getClientOriginalExtension();
            $file3->move(public_path() . '/storage/cat_icons/', $filename3);
            $requestData['cat_photo'] = $filename3;
        }

        app()->setLocale($language);

        $category = Category::findOrFail($id);
        if (!$category->meta) {
            $meta = \App\Meta::create($requestData['meta']);
            $meta->save();
            $category->meta_id = $meta->id;
            $category->save();
        } else {
            $meta = $category->meta;
            $meta->update($requestData['meta']);
        }
        $category->update($requestData);

        return redirect('admin/categories')->with('flash_message', 'Category updated!');
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
        Category::destroy($id);

        return redirect('admin/categories')->with('flash_message', 'Category deleted!');
    }
}
