@extends('layouts.app')

@section('content')
@php
    $isEn = session('locale', 'ar') === 'en';
    $showGallery = ($site['show_gallery'] ?? '1') === '1';
    $showReels = ($site['show_reels'] ?? '1') === '1';
    $showReviews = ($site['show_reviews'] ?? '1') === '1';
    $showBeforeAfter = ($site['show_before_after'] ?? '0') === '1';

    $mediaUrl = function ($path, $fallback = 'images/service-1.svg') {
        if (!$path) {
            return asset($fallback);
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    };
@endphp

<section class="hero" id="home">
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="eyebrow hero-eyebrow">
                {{ $site['hero_eyebrow'] ?? 'LOLITA NAILS • LASHES • BEAUTY' }}
            </div>

            <h1 class="hero-title">
                {{ $isEn ? 'Your beauty begins in the details' : ($site['hero_title'] ?? 'جمالك يبدأ من التفاصيل') }}
            </h1>

            <p class="hero-lead">
                {{ $isEn
                    ? 'An elegant beauty experience, precise care, and a result designed for you.'
                    : ($site['hero_subtitle'] ?? 'تجربة أنثوية راقية، عناية دقيقة، ونتيجة مصممة لتشبهك.') }}
            </p>

            <div class="hero-actions">
                <a class="btn btn-primary" href="{{ route('booking.create') }}">
                    {{ $isEn ? 'Book now' : 'احجزي الآن' }}
                </a>

                @if($showGallery)
                    <a class="btn btn-outline" href="#gallery">
                        {{ $isEn ? 'View our work' : 'شاهدي الأعمال' }}
                    </a>
                @endif
            </div>

            <div class="hero-meta">
                <span>📍 {{ $site['location'] ?? ($isEn ? 'Lattakia, Syria' : 'اللاذقية، سوريا') }}</span>
                <span>♡ {{ $site['followers_label'] ?? '55K+ followers' }}</span>
                <span>✦ {{ $isEn ? 'Attention to detail' : 'عناية بالتفاصيل' }}</span>
            </div>
        </div>

        <div class="hero-art">
            <div class="hero-image">
                <img src="{{ asset('images/lolita/white-ombre.jpg') }}" alt="Lolita Nails">
            </div>
            <div class="floating-note">
                Pretty nails<br><span>happier you ♡</span>
            </div>
        </div>
    </div>
</section>

<section class="section" id="services">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">OUR SERVICES</span>
                <h2>{{ $isEn ? 'Beauty in every detail' : 'جمال في كل تفصيل' }}</h2>
                <p>{{ $isEn ? 'Selected services with clear time and pricing.' : 'خدمات مختارة مع وقت وسعر واضحين.' }}</p>
            </div>

            <a href="{{ route('booking.create') }}">
                {{ $isEn ? 'Book now →' : 'احجزي الآن ←' }}
            </a>
        </div>

        <div class="service-grid">
            @foreach($services as $service)
                <article class="service-card">
                    <img
                        src="{{ $service->image
                        ? asset(ltrim($service->image, '/'))
                        : asset('images/service-1.svg') }}"
                        alt="{{ $isEn
                        ? ($service->name_en ?? $service->name_ar)
                        : $service->name_ar }}"
                        loading="lazy"
                                        >

                    <div class="service-body">
                        <h3>{{ $isEn ? ($service->name_en ?? $service->name_ar) : $service->name_ar }}</h3>

                        <p>
                            {{ $isEn
                                ? ($service->description_en ?? $service->description_ar)
                                : $service->description_ar }}
                        </p>

                        <div class="service-info">
                            <span>
                                {{ $isEn ? 'From ' : 'من ' }}
                                {{ number_format($service->price) }}
                                {{ $isEn ? 'SYP' : 'ل.س' }}
                            </span>
                            <span>{{ $service->duration_minutes }} {{ $isEn ? 'min' : 'دقيقة' }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

@if($showGallery)
<section class="section soft" id="gallery">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">OUR WORK</span>
                <h2>{{ $isEn ? 'Real work, details that speak' : 'أعمال حقيقية، تفاصيل تحكي' }}</h2>
                <p>{{ $isEn ? 'Filter by the style you are looking for.' : 'فلّري حسب الستايل الذي تبحثين عنه.' }}</p>
            </div>
        </div>

        <div class="gallery-filters" role="tablist">
            <button class="gallery-filter is-active" data-filter="all">{{ $isEn ? 'All' : 'الكل' }}</button>
            <button class="gallery-filter" data-filter="french">French</button>
            <button class="gallery-filter" data-filter="gel">Gel</button>
            <button class="gallery-filter" data-filter="art">Nail Art</button>
            <button class="gallery-filter" data-filter="extensions">Extensions</button>
        </div>

        <div class="gallery-grid">
            @foreach($gallery as $item)
                <figure data-gallery-category="{{ $item->category ?: 'all' }}">
                    <img
                        src="{{ $mediaUrl($item->image, 'images/gallery-1.svg') }}"
                        alt="{{ $item->title }}"
                        loading="lazy"
                    >
                    <figcaption>{{ $item->title }}</figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($showBeforeAfter)
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">BEFORE / AFTER</span>
                <h2>{{ $isEn ? 'Small touches, visible difference' : 'لمسة صغيرة تغيّر التفاصيل' }}</h2>
            </div>
        </div>

        <div class="before-after" data-before-after>
            <img class="before-after-base" src="{{ asset('images/lolita/pink-stiletto.jpg') }}" alt="After">
            <div class="before-after-overlay" style="width:50%">
                <img src="{{ asset('images/lolita/white-ombre.jpg') }}" alt="Before">
            </div>
            <input type="range" min="0" max="100" value="50" aria-label="Before and after comparison">
            <span class="before-label">{{ $isEn ? 'Before' : 'قبل' }}</span>
            <span class="after-label">{{ $isEn ? 'After' : 'بعد' }}</span>
            <div class="before-after-handle"></div>
        </div>
    </div>
</section>
@endif

@if($showReels && $reels->count())
<section class="section reels-section" id="reels">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">BEFORE / AFTER REELS</span>
                <h2>{{ $isEn ? 'See the transformation ✨' : 'شوفي التحوّل ✨' }}</h2>
                <p>{{ $isEn ? 'Real before-and-after cases by Lolita.' : 'حالات قبل وبعد حقيقية من أعمال Lolita.' }}</p>
            </div>

            <a href="{{ $site['instagram_url'] ?? 'https://instagram.com/lolitanails22' }}" target="_blank" rel="noopener">
                {{ $isEn ? 'More on Instagram ↗' : 'المزيد على Instagram ↗' }}
            </a>
        </div>

        <div class="reels-grid real-reels">
            @foreach($reels as $reel)
                <button
                    class="reel-video-card"
                    type="button"
                    data-reel-src="{{ $mediaUrl($reel->video, '') }}"
                    aria-label="{{ $isEn ? 'Play reel' : 'تشغيل الريل' }}"
                >
                    <video muted playsinline preload="metadata" loop @if($reel->poster) poster="{{ $mediaUrl($reel->poster, '') }}" @endif>
                        <source src="{{ $mediaUrl($reel->video, '') }}">
                    </video>
                    <span class="reel-play">▶</span>
                    <b>{{ $reel->label ?: 'Before → After' }}</b>
                </button>
            @endforeach
        </div>
    </div>
</section>

<div class="reel-modal" id="reel_modal" aria-hidden="true">
    <button class="reel-modal-close" type="button" aria-label="{{ $isEn ? 'Close' : 'إغلاق' }}">×</button>
    <div class="reel-modal-frame">
        <video id="reel_modal_video" controls playsinline></video>
    </div>
</div>
@endif

<section class="section" id="about">
    <div class="container trust-grid">
        <div class="trust-item">
            <span>◇</span>
            <div>
                <h3>{{ $isEn ? 'High-quality products' : 'منتجات عالية الجودة' }}</h3>
                <p>{{ $isEn ? 'Carefully selected for a polished, lasting result.' : 'اختيارات مناسبة لنتيجة مرتبة وثابتة.' }}</p>
            </div>
        </div>

        <div class="trust-item">
            <span>♡</span>
            <div>
                <h3>{{ $isEn ? 'Cleanliness & care' : 'نظافة وعناية' }}</h3>
                <p>{{ $isEn ? 'Your comfort and tool hygiene always matter.' : 'اهتمام براحة العميلة ونظافة الأدوات.' }}</p>
            </div>
        </div>

        <div class="trust-item">
            <span>◌</span>
            <div>
                <h3>{{ $isEn ? 'Personal style' : 'ستايل شخصي' }}</h3>
                <p>{{ $isEn ? 'Every session is tailored to your look and preference.' : 'كل جلسة مصممة حسب رغبتك وإطلالتك.' }}</p>
            </div>
        </div>

        <div class="trust-item">
            <span>★</span>
            <div>
                <h3>{{ $site['followers_label'] ?? '55K+ followers' }}</h3>
                <p>{{ $isEn ? 'A strong social presence for Lolita Nails.' : 'حضور اجتماعي قوي لبراند Lolita Nails.' }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container booking-banner">
        <div>
            <span class="eyebrow">BOOK YOUR TIME</span>
            <h2>{{ $isEn ? 'Ready for your next set?' : 'جاهزة للـ next set؟' }}</h2>
            <p>{{ $isEn ? 'Choose your service, day and time — we will take care of the rest.' : 'اختاري الخدمة واليوم والساعة، واتركي الباقي علينا.' }}</p>
        </div>

        <a class="btn btn-primary" href="{{ route('booking.create') }}">
            {{ $isEn ? 'Book now' : 'احجزي الآن' }}
        </a>
    </div>
</section>

@if($showReviews)
<section class="section" id="reviews">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">CLIENT REVIEWS</span>
                <h2>{{ $isEn ? 'What clients say' : 'ماذا تقول العميلات' }}</h2>
            </div>
        </div>

        <div class="review-grid">
            @foreach($reviews as $review)
                <article class="review-card">
                    <div class="stars">{{ str_repeat('★', $review->rating) }}</div>
                    <p>“{{ $review->body }}”</p>
                    <strong>{{ $review->client_name }}</strong>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
