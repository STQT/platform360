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
        return view('admin.categories.create');
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
            'cat_icon' => 'required',
            'cat_icon_svg' => 'required'
		]);
        $requestData = $request->all();
        
$fileName = null;
    
        if( request()->hasFile('cat_icon') && request()->file('cat_icon')->isValid()) {
        $file = request()->file('cat_icon');
        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        $file->move(public_path() . '/storage/cat_icons/', $fileName);    
    }
       if( request()->hasFile('cat_icon_svg') && request()->file('cat_icon_svg')->isValid()) {
        $file2 = request()->file('cat_icon_svg');
        $fileName2 = md5($file2->getClientOriginalName() . time()) . "." . $file2->getClientOriginalExtension();
        $file2->move(public_path() . '/storage/cat_icons/', $fileName2);    
    }
   


 Category::create([
        'name' => request()->get('name'),
        'cat_icon' => $fileName,
    'cat_icon_svg' => $fileName2,

    ]);

  

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
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
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
            'cat_icon' => 'sometimes | required',
             'cat_icon_svg' => 'sometimes | required'
		]);
        $requestData = $request->all();
       

    
    if( !empty($requestData['cat_icon']) && request()->hasFile('cat_icon') && request()->file('cat_icon')->isValid()) {
        $file = request()->file('cat_icon');
        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        $file->move(public_path() . '/storage/cat_icons/', $fileName);    
        $requestData['cat_icon'] = $fileName;
    }
       if(!empty($requestData['cat_icon_svg']) && request()->hasFile('cat_icon_svg') && request()->file('cat_icon_svg')->isValid()) {
        $file2 = request()->file('cat_icon_svg');
        $fileName2 = md5($file2->getClientOriginalName() . time()) . "." . $file2->getClientOriginalExtension();
        $file2->move(public_path() . '/storage/cat_icons/', $fileName2); 
        $requestData['cat_icon_svg'] = $fileName2;   
    }
   


        $category = Category::findOrFail($id);
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
