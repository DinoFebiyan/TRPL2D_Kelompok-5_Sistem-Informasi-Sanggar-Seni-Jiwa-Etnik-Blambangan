<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($urls as $url)
        <url>
            <loc>{{ $url['loc'] }}</loc>
            <changefreq>daily</changefreq>
            <priority>{{ $url['priority'] }}</priority>
        </url>
    @endforeach

    @foreach ($berita as $post)
        <url>
            <loc>{{ route('umum.berita-detail', $post->id) }}</loc>
            <lastmod>{{ $post->updated_at->tz('GMT')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach

    @foreach ($event as $evt)
        <url>
            <loc>{{ route('umum.event-detail', $evt->id) }}</loc>
            <lastmod>{{ $evt->updated_at->tz('GMT')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach

    @foreach ($katalog as $kat)
        <url>
            <loc>{{ route('umum.katalog-detail', $kat->id) }}</loc>
            <lastmod>{{ $kat->updated_at->tz('GMT')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
