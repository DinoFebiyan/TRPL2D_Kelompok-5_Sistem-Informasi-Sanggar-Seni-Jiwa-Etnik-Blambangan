{{-- Ambil data sistem dinamis dari database secara global --}}
@php
    $sistemLayout = \App\Models\PengaturanSistem::first();
@endphp

<footer>
    {{-- Container pembatas lebar agar berada tepat di tengah --}}
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>Jiwa Etnik Blambangan</h3>
                <p>Sanggar Tari Jiwa Etnik Blambangan adalah pusat pelestarian seni dan budaya tari tradisional Banyuwangi. Kami berdedikasi untuk menggerakkan jiwa, merayakan budaya, dan melestarikan warisan Nusantara.</p>
            </div>
            
            <div class="footer-links">
                <h4>Tautan Singkat</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('umum.profil') }}">Profil Sanggar</a></li>
                    <li><a href="{{ route('umum.berita') }}">Artikel & Berita</a></li>
                    <li><a href="{{ route('umum.event') }}">Kalender Event</a></li>
                    <li><a href="{{ route('umum.katalog') }}">Katalog Kostum</a></li>
                    <li><a href="{{ route('umum.galeri') }}">Galeri</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Hubungi Kami</h4>
                <ul>
                    {{-- Google Maps --}}
                    <li>
                        @if($sistemLayout && $sistemLayout->link_maps)
                            <a href="{{ $sistemLayout->link_maps }}" target="_blank">
                                <i class="fa-solid fa-map-location-dot"></i> Sanggar Jiwa Etnik Blambangan
                            </a>
                        @else
                            <a href="http://maps.google.com" target="_blank">
                                <i class="fa-solid fa-map-location-dot"></i> Sanggar Seni Jiwa Etnik Blambangan
                            </a>
                        @endif
                    </li>
                    
                    {{-- WhatsApp --}}
                    <li>
                        @if($sistemLayout && $sistemLayout->link_wa)
                            @php 
                                $cleanWa = preg_replace('/[^0-9]/', '', $sistemLayout->link_wa); 
                                $displayWa = $sistemLayout->link_wa;
                                if(strpos($displayWa, '62') === 0) {
                                    $displayWa = '+' . substr($displayWa, 0, 2) . ' ' . substr($displayWa, 2, 3) . '-' . substr($displayWa, 5, 4) . '-' . substr($displayWa, 9);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $cleanWa }}" target="_blank">
                                <i class="fa-solid fa-phone"></i> {{ $displayWa }}
                            </a>
                        @else
                            <a href="https://api.whatsapp.com/send/?phone=6282232505355" target="_blank">
                                <i class="fa-solid fa-phone"></i> +62 822-3250-5355
                            </a>
                        @endif
                    </li>
                    
                    {{-- Email --}}
                    <li>
                        @if($sistemLayout && $sistemLayout->link_email)
                            <a href="mailto:{{ $sistemLayout->link_email }}">
                                <i class="fa-solid fa-envelope"></i> {{ $sistemLayout->link_email }}
                            </a>
                        @else
                            <a href="mailto:mustikasadida@gmail.com">
                                <i class="fa-solid fa-envelope"></i> mustikasadida@gmail.com
                            </a>
                        @endif
                    </li>
                    
                    {{-- Instagram --}}
                    <li>
                        @if($sistemLayout && $sistemLayout->link_instagram)
                            @php
                                $igUrl = rtrim($sistemLayout->link_instagram, '/');
                                $igUser = basename($igUrl);
                            @endphp
                            <a href="{{ $sistemLayout->link_instagram }}" target="_blank">
                                <i class="fa-brands fa-instagram"></i> @ {{ $igUser }}
                            </a>
                        @else
                            <a href="https://www.instagram.com/jiwaetnikblambangan" target="_blank">
                                <i class="fa-brands fa-instagram"></i> @jiwaetnikblambangan
                            </a>
                        @endif
                    </li>
                    
                    {{-- TikTok --}}
                    <li>
                        @if($sistemLayout && $sistemLayout->link_tiktok)
                            @php
                                $ttUrl = rtrim($sistemLayout->link_tiktok, '/');
                                $ttUser = basename($ttUrl);
                            @endphp
                            <a href="{{ $sistemLayout->link_tiktok }}" target="_blank">
                                <i class="fa-brands fa-tiktok"></i> {{ str_contains($ttUser, '@') ? $ttUser : '@' . $ttUser }}
                            </a>
                        @else
                            <a href="https://www.tiktok.com/@jiwaetnikblambangan" target="_blank">
                                <i class="fa-brands fa-tiktok"></i> @jiwaetnikblambangan
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2026 Sanggar Tari Jiwa Etnik Blambangan. Seluruh Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>