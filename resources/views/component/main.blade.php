<html lang="en">

<head>

    @php
        $seo = $seos ?? null;
        // dd($seos);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    @if (!empty($seo?->meta_title))
        <meta name="title" content="{{ $seo->meta_title }}">
    @endif

    @if (!empty($seo?->title))
        <title>{{ $seo->title }}</title>
    @elseif(!empty($seo?->meta_title))
        <title>{{ $seo->meta_title }}</title>
    @endif

    <!-- Standard SEO -->
    @if (!empty($seo?->meta_description))
        <meta name="description" content="{{ $seo->meta_description }}">
    @endif

    @if (!empty($seo?->meta_keywords))
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif

    @if (!empty($seo?->robots))
        <meta name="robots" content="{{ $seo->robots }}">
    @endif

    @if (!empty($seo?->author))
        <meta name="author" content="{{ $seo->author }}">
    @endif

    <!-- Canonical & Language -->
    @if (!empty($seo?->canonical_url))
        <link rel="canonical" href="{{ $seo->canonical_url }}">
    @endif

    @if (!empty($seo?->locale))
        <meta name="language" content="{{ $seo->locale }}">
        <meta http-equiv="Content-Language" content="{{ $seo->locale }}">
    @endif

    <!-- Alternate hreflang -->
    @if (!empty($seo?->alternate_url))
        <link rel="alternate" hreflang="{{ $seo->alternate_href_lang ?? 'en' }}" href="{{ $seo->alternate_url }}">
    @endif

    <!-- Region, Country, Timezone -->
    @if (!empty($seo?->country))
        <meta name="country" content="{{ $seo->country }}">
    @endif
    @if (!empty($seo?->region))
        <meta name="region" content="{{ $seo->region }}">
    @endif
    @if (!empty($seo?->timezone))
        <meta name="timezone" content="{{ $seo->timezone }}">
    @endif

    <!-- Breadcrumb & Page Type -->
    @if (!empty($seo?->breadcrumb_title))
        <meta name="breadcrumb" content="{{ $seo->breadcrumb_title }}">
    @endif

    @if (!empty($seo?->content_type))
        <meta name="content_type" content="{{ $seo->content_type }}">
    @endif

    @if (!empty($seo?->page_type))
        <meta name="page_type" content="{{ $seo->page_type }}">
    @endif

    <!-- Sitemap Hints -->
    @if (!empty($seo?->priority))
        <meta name="priority" content="{{ $seo->priority }}">
    @endif

    @if (!empty($seo?->changefreq))
        <meta name="changefreq" content="{{ $seo->changefreq }}">
    @endif

    <!-- Open Graph -->
    @if (!empty($seo?->og_title))
        <meta property="og:title" content="{{ $seo->og_title }}">
    @endif

    @if (!empty($seo?->og_description))
        <meta property="og:description" content="{{ $seo->og_description }}">
    @endif

    @if (!empty($seo?->og_image))
        <meta property="og:image" content="{{ asset('storage/' . $seo->og_image) }}">
        <meta property="og:image:secure_url" content="{{ asset('storage/' . $seo->og_image) }}">
    @endif

    @if (!empty($seo?->og_type))
        <meta property="og:type" content="{{ $seo->og_type }}">
    @endif

    @if (!empty($seo?->og_url))
        <meta property="og:url" content="{{ $seo->og_url }}">
    @endif

    <!-- Twitter Card -->
    @if (!empty($seo?->twitter_card))
        <meta name="twitter:card" content="{{ $seo->twitter_card }}">
    @endif

    @if (!empty($seo?->twitter_title))
        <meta name="twitter:title" content="{{ $seo->twitter_title }}">
    @endif

    @if (!empty($seo?->twitter_description))
        <meta name="twitter:description" content="{{ $seo->twitter_description }}">
    @endif

    @if (!empty($seo?->twitter_image))
        <meta name="twitter:image" content="{{ asset('storage/' . $seo->twitter_image) }}">
    @endif

    @if (!empty($seo?->twitter_site))
        <meta name="twitter:site" content="{{ $seo->twitter_site }}">
    @endif

    @if (!empty($seo?->twitter_creator))
        <meta name="twitter:creator" content="{{ $seo->twitter_creator }}">
    @endif

    <!-- Focus Keyword -->
    @if (!empty($seo?->focus_keyword))
        <meta name="focus_keyword" content="{{ $seo->focus_keyword }}">
    @endif


  {{-- @if(!empty($seo?->meta_tags))
    @php
      // Sirf meta/link allow; baaki strip so koi style/script ghuske content print na kare
      $safeMeta = preg_replace('/<(?!\/?(?:meta|link)\b)[^>]*>/i', '', $seo->meta_tags);
    @endphp
    {!! $safeMeta !!}
@endif --}}

    {{-- JSON-LD: escape closing script --}}
    @if (!empty($seo?->structured_data_json))
        @php $jsonLd = str_replace('</script>', '<\/script>', $seo->structured_data_json); @endphp
        <script type="application/ld+json">{!! $jsonLd !!}</script>
    @endif

    <!-- Favicon -->
    <link href="{{ asset('logo.png') }}" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500&family=Jost:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="{{ asset('front-asset/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front-asset/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front-asset/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        .testimonial-item {
            transition: all 0.3s ease-in-out;
        }

        .testimonial-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .transition-scale {
            transition: transform 0.3s ease-in-out;
        }

        .transition-scale:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .transition-scale {
            transition: transform 0.4s ease-in-out;
        }

        .transition-scale:hover {
            transform: scale(1.05);
        }

        .product-card:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .project-card:hover .portfolio-overlay {
            opacity: 1;
        }

        #portfolio-flters button {
            transition: 0.3s;
            border-radius: 50px;
            padding: 6px 18px;
        }

        #portfolio-flters button.active,
        #portfolio-flters button:hover {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        meta, meta::before, meta::after { display: none !important; content: none !important; }
    </style>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('front-asset/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('front-asset/css/style.css') }}" rel="stylesheet">
</head>

<body>


    @include('component.header')

    @yield('content')

    @include('component.footer')

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33'
            });
        </script>
    @endif



    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('front-asset/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('front-asset/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('front-asset/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('front-asset/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('front-asset/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('front-asset/lib/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('front-asset/lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('front-asset/js/main.js') }}"></script>
</body>

</html>
