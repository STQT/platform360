{!! $xmlVersion !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ $baseName }}/</loc>
        <priority>1.0</priority>
    </url>
    @foreach($locations as $location)
        <url>
            <loc>{{ $baseName }}/ru/location/{{ $location->slug }}</loc>
            <lastmod>{{ $location->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($categories as $category)
        <url>
            <loc>{{ $baseName }}/ru/category/{{ $category->slug }}</loc>
            <lastmod>{{ $category->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>