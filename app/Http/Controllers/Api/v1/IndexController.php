<?php

namespace App\Http\Controllers\Api\v1;

use App\Category;
use App\Http\Controllers\Controller;
use App\Location;
use Illuminate\Http\Request;

class IndexController extends Controller

{
    public function categories()
    {
        $data = Category::select('id', 'name', 'cat_photo', 'cat_icon', 'cat_icon_svg', 'slug')->paginate(12);

        $data->getCollection()->transform(function ($item) {
            $item->cat_icon = $item->cat_icon ? '/storage/cat_icons/'.$item->cat_icon : null;
            $item->cat_icon_svg = $item->cat_icon_svg ? '/storage/cat_icons/'.$item->cat_icon_svg : null;
            $item->cat_photo = $item->cat_photo ? '/storage/cat_icons/'.$item->cat_photo : null;
            return $item;
        });

        return $data;
    }

    public function locations(Request $request)
    {
        $category_id = $request->get('category_id');
        $city_id = $request->get('city_id');
        $name = $request->get('name');

        $data = Location::select('id', 'name', 'category_id', 'city_id', 'slug', 'lat', 'lng', 'preview', 'logo', 'store_photo')
            ->when($category_id, function ($q) use ($category_id) {
                $q->where('category_id', $category_id);
            })
            ->when($city_id, function ($q) use ($city_id) {
                $q->where('city_id', $city_id);
            })
            ->when($name, function ($q) use ($name) {
                $q->where('name', 'like', '%'.$name.'%');
            })
            ->whereNull('podlocparent_id')
            ->paginate(16);

        $data->getCollection()->transform(function ($item) {
            $item->preview = $item->preview ? '/storage/panoramas/preview/'.$item->preview : null;
            $item->logo = $item->logo ? '/storage/locations/'.$item->logo : null;
            $item->store_photo = $item->store_photo ? '/storage/locations/'.$item->store_photo : null;
            return $item;
        });

        return $data;
    }
}
