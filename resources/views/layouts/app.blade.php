@php
$globalSite = $site ?? \App\Models\SiteSetting::allKeyed();
$lang = session('locale', 'ar');
$isEn = $lang === 'en';
@endphp
<!doctype html>
<html lang="{{ $lang }}" dir="{{ $isEn ? 'ltr' : 'rtl' }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $globalSite['seo_title'] ?? 'Lolita Nails | Nails, Lashes & Beauty' }}</title>
<meta name="description" content="{{ $globalSite['seo_description'] ?? ($isEn ? 'Lolita Nails — nails, nail art, gel and extensions with online booking.' : 'Lolita Nails — أظافر، نيل آرت، جيل وتركيب مع حجز موعد أونلاين.') }}">
<meta property="og:title" content="{{ $globalSite['seo_title'] ?? 'Lolita Nails | Nails, Lashes & Beauty' }}"><meta property="og:type" content="website"><meta property="og:image" content="{{ asset('images/lolita/white-ombre.jpg') }}"><meta name="theme-color" content="#b86f74">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head><body class="lang-{{ $lang }}">
<div class="site-loader" id="site_loader" aria-hidden="true"><div class="loader-mark"><span>L</span><small>LOLITA NAILS</small></div></div>
<header class="site-header"><div class="container nav-wrap"><a class="brand" href="{{ route('home') }}"><span>Lolita</span><small>NAILS & LASHES</small></a><button class="menu-toggle" aria-label="{{ $isEn ? 'Open menu' : 'فتح القائمة' }}" onclick="document.body.classList.toggle('menu-open')">☰</button><nav class="main-nav"><a href="{{ route('home') }}#home">{{ $isEn ? 'Home' : 'الرئيسية' }}</a><a href="{{ route('home') }}#services">{{ $isEn ? 'Services' : 'الخدمات' }}</a><a href="{{ route('home') }}#gallery">{{ $isEn ? 'Our work' : 'الأعمال' }}</a><a href="{{ route('home') }}#about">{{ $isEn ? 'About' : 'عن لوليتا' }}</a><a href="{{ route('home') }}#reviews">{{ $isEn ? 'Reviews' : 'الآراء' }}</a><a href="{{ route('home') }}#contact">{{ $isEn ? 'Contact' : 'تواصل' }}</a></nav><div class="nav-actions"><a class="lang-switch" href="{{ route('language.switch', $isEn ? 'ar' : 'en') }}">{{ $isEn ? 'عربي' : 'EN' }}</a><a class="btn btn-primary nav-cta" href="{{ route('booking.create') }}">{{ $isEn ? 'Book now' : 'احجزي موعدك' }}</a></div></div></header>
<main>@yield('content')</main><a class="mobile-booking-bar" href="{{ route('booking.create') }}"><span>♡</span><b>{{ $isEn ? 'Book your appointment' : 'احجزي موعدك' }}</b><small>{{ $isEn ? 'Choose service & time' : 'اختاري الخدمة والوقت' }}</small></a>
<footer class="footer" id="contact"><div class="container footer-grid"><div><div class="brand footer-brand"><span>Lolita</span><small>NAILS & LASHES</small></div><p>Beauty is in the details ♡</p></div><div><h4>{{ $isEn ? 'Contact' : 'تواصل' }}</h4><p>{{ $globalSite['location'] ?? ($isEn ? 'Latakia, Syria' : 'اللاذقية، سوريا') }}</p><p><a href="tel:{{ $globalSite['phone'] ?? '0936812019' }}">{{ $globalSite['phone'] ?? '0936 812 019' }}</a></p></div><div><h4>{{ $isEn ? 'Links' : 'روابط' }}</h4><p><a href="{{ $globalSite['instagram_url'] ?? 'https://instagram.com/lolitanails22' }}" target="_blank" rel="noopener">Instagram</a></p><p><a href="https://wa.me/{{ $globalSite['whatsapp'] ?? '963936812019' }}" target="_blank" rel="noopener">WhatsApp</a></p></div></div><div class="container footer-bottom"><span>© {{ date('Y') }} {{ $globalSite['brand_name'] ?? 'Lolita Nails' }}.</span><span class="devix-credit">Built by <strong>DEVIX</strong></span></div></footer>
<script src="{{ asset('js/app.js') }}"></script>@stack('scripts')</body></html>
