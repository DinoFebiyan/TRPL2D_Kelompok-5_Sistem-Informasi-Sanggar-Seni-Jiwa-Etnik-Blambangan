<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Event;
use App\Models\Katalog;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0'],
            ['loc' => url('/profil-sanggar'), 'priority' => '0.8'],
            ['loc' => url('/berita'), 'priority' => '0.8'],
            ['loc' => url('/event'), 'priority' => '0.8'],
            ['loc' => url('/katalog'), 'priority' => '0.8'],
            ['loc' => url('/galeri'), 'priority' => '0.8'],
        ];

        $berita = Berita::latest()->get();
        $event = Event::latest()->get();
        $katalog = Katalog::all();

        return response()->view('sitemap', compact('urls', 'berita', 'event', 'katalog'))
            ->header('Content-Type', 'application/xml');
    }
}
