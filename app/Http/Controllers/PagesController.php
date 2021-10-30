<?php

namespace App\Http\Controllers;

use App\Location;
use App\Category;


class PagesController extends Controller
{
    public function sitemap()
    {
        $data = $this->generateSiteMap();

        return view('pages.sitemap', ['data' => $data]);
    }

    public function help()
    {
        $page = \App\Page::where('slug', 'how-to-use')->firstOrFail();
        return view('pages.show',
            [
                'page' => $page
            ]
        );
    }

    //TODO: вынести генерацию карты сайта в отдельный сервис
    private function generateSiteMap()
    {
        $locationsArray = [];
        $categoriesArray = [];
        $locations = Location::get();
        $categories = Category::whereNotNull('slug')->get();
        foreach($locations as $location) {
            $locationsArray[] = $location;
        }
        foreach($categories as $category) {
            $categoriesArray[] = $category;
        }
        return [
            'locations' => $locationsArray,
            'categories' => $categoriesArray
        ];
    }
}
