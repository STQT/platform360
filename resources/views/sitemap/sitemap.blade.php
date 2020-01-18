{!! $xmlVersion !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ $baseName }}/</loc>
    </url>
    @foreach($locations as $location)
        <url>
            <loc>{{ $baseName }}/ru/location/{{ $location->slug }}</loc>
            <lastmod>{{ $location->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset>