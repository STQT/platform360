<?php

namespace App\Http\Controllers;

use App\Category;
use App\Location;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function sitemap()
    {
        $locations = Location::orderBy('updated_at', 'DESC')->get();
        $categories = Category::whereNotNull('slug')->get();
        $xmlVersion = '<?xml version="1.0" encoding="UTF-8"?>';
        $baseName = 'https://' . request()->getHost();

        return response()->view('sitemap.sitemap', compact(
                'locations',
                'xmlVersion',
                'baseName',
                'categories'
            )
        )
            ->header('Content-Type', 'text/xml');
    }
}
