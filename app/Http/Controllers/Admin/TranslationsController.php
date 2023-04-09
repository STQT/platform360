<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\LcTranslation;
use Illuminate\Http\Request;

class TranslationsController extends Controller
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
            $translations = LcTranslation::where('key', 'LIKE', "%$keyword%")
                ->orWhere('value', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $translations = LcTranslation::latest()->paginate($perPage);
        }

        return view('admin.translations.index', compact('translations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.translations.create');
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
            'key' => 'required|unique:translations',
            'value_ru' => 'required'
		]);

        $translation = new LcTranslation;
        $translation->key = $request->key;
        $translation->setTranslation('value', 'en', $request->value_en);
        $translation->setTranslation('value', 'uzb', $request->value_uzb);
        $translation->setTranslation('value', 'ru', $request->value_ru);
        $translation->save();

        return redirect('admin/translations')->with('flash_message', 'Translation added!');
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
        $translation = LcTranslation::findOrFail($id);

        return view('admin.translations.show', compact('translation'));
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
        $translation = LcTranslation::findOrFail($id);

        return view('admin.translations.edit', compact('translation'));
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
			'key' => 'required',
			'value_ru' => 'required'
		]);
        $requestData = $request->all();
        
        $translation = LcTranslation::findOrFail($id);
        $translation->key = $request->key;
        $translation->setTranslation('value', 'en', $request->value_en);
        $translation->setTranslation('value', 'uzb', $request->value_uzb);
        $translation->setTranslation('value', 'ru', $request->value_ru);
        $translation->save();

        return redirect('admin/translations')->with('flash_message', 'Translation updated!');
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
        LcTranslation::destroy($id);

        return redirect('admin/translations')->with('flash_message', 'Translation deleted!');
    }
}
