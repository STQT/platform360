<?php

namespace App\Http\Controllers;

use App\Location;
use App\Category;


class PagesController extends Controller
{
    public function sitemap()
    {
        $location = [];
        $sitemap = $this->generateSiteMap();

        return view('pages.sitemap', ['sitemap' => $sitemap]);
    }

    public function show($slug)
    {
        $page = \App\Page::where('slug', $slug)->firstOrFail();
        return view('pages.show',
            [
                'page' => $page
            ]
        );
    }

    //TODO: вынести генерацию карты сайта в отдельный сервис
    private function generateSiteMap()
    {
        $baseName = $_SERVER['HTTP_HOST'];
        $links = [];
        $links[$baseName] = ucfirst($baseName);
        $locations = Location::get();
        $categories = Category::whereNotNull('slug')->get();
        foreach($locations as $location) {
            $links[$baseName . '/ru/location/' . $location->slug] = $location->name;
        }
        foreach($categories as $category) {
            $links[$baseName . '/ru/category/' . $location->slug] = $category->name;
        }
        return $links;
    }
}