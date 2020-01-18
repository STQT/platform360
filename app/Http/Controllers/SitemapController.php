<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function sitemap()
    {
        $locations = Location::orderBy('updated_at', 'DESC')->get();
        $xmlVersion = '<?xml version="1.0" encoding="UTF-8"?>';
        $baseName = 'https://' . request()->getHost();

        return response()->view('sitemap.sitemap', compact('locations', 'xmlVersion', 'baseName'))
            ->header('Content-Type', 'text/xml');
    }
}
