<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('image/p2p logo.svg') }}" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <title>{{ $title }}</title> --}}

    {{-- Insert CDN links below --}}

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

    {{-- TW Elements --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/tw-elements.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/css/tw-elements.min.css" />
    <script src="https://cdn.tailwindcss.com/3.3.0"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    {{-- Animate On Scroll (AOS) --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    {{-- JQuery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    {{-- Datatables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />

    {{-- SwiperJS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />   

    {{-- google icon --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">

    {{-- Alphine JS --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap');

        * {
            font-family: 'Jost', sans-serif;
            --bblue: #7494ec;
            --purple: #633F92;
            --neutral: #292A67;
            --yellow: #F6CE3E;
            --red: #E62D63;
            --red-lg: #ff6e9a;
            --magenta: #902680;
            --purple-dark: #5500a4;
            --tosca: #4CB79C;
            --tosca-lg: rgb(74, 231, 192);
            --yellow-grad: linear-gradient(45deg, var(--yellow) 0%, var(--magenta) 100%);
            --tosca-grad: linear-gradient(45deg, var(--tosca) 0%, var(--purple) 100%);
            --ig-grad: linear-gradient(45deg, #ffde85 0%, #f7792a 30%, #f7504f 40%, #d82b81 60%, #d82b81 75%, #9536c2 100%);
            --line-grad: linear-gradient(45deg, #1a6c2a, #4cc764);
            --yt-grad: linear-gradient(45deg, #f76161, #dc2626);
            --spotify-grad: linear-gradient(45deg, #1DB954, #191414);
            --tiktok-grad: linear-gradient(45deg, #ff0050, #191414 40%, #191414 60%, #00f2ea);
        }
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        body {
            top: 0;
            left: 0;
            margin: 0;
            padding: 0;
            min-width: 100vw;
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
            /* background-color: #F4DEB5; */
            background-image:
                radial-gradient(at 93% 100%, #7494ec 0px, transparent 50%),
                radial-gradient(at 0% 0%, #633F92 0px, transparent 50%),
                radial-gradient(at 38% 60%, white 0px, transparent 50%),
                radial-gradient(at 100% 0%, #7494ec 0px, transparent 50%),
                radial-gradient(at 80% 50%, #633F92 0px, transparent 50%),
                radial-gradient(at 0% 100%, white 0px, transparent 50%);
            background-size: 200% 200%;
            background-repeat: no-repeat;
            overflow-x: hidden;
            animation: gradient 30s ease infinite;
            /* opacity: 50%; */
            z-index:1 ;
        }


        @font-face {
            font-family: 'Runtoe';
            src: url('{{ asset('assets/Runtoe.ttf') }}') format('truetype'),
                url('{{ asset('assets/Runtoe.otf') }}') format('opentype');
            font-weight: normal;
            font-style: normal;
        }
         /* Chrome, Edge and Safari */
         *::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }

        *::-webkit-scrollbar-track {
            /* border-radius: 6px; */
            background-color: #633F92;
        }

        *::-webkit-scrollbar-track:hover {
            background-color: #a87ddf;
        }

        *::-webkit-scrollbar-track:active {
            background-color: #a87ddf;
        }

        *::-webkit-scrollbar-thumb {
            border-radius: 5px;
            background: linear-gradient(to bottom, transparent, #7494ec);
        }


        .swal2-confirm {
            background: rgb(46, 143, 255) !important;
        }

        .swal2-deny,
        .swal2-cancel {
            background: rgb(255, 79, 79) !important;
        }

        @keyframes animateText {
            0% {
                background-position: 0%;
            }

            100% {
                background-position: 500%;
            }
        }

        @-webkit-keyframes animateText {
            0% {
                background-position: 0%;
            }

            100% {
                background-position: 500%;
            }
        }
    
    </style>
    @yield('style')
</head>
<body>
    @yield('content')

    {{-- TW Elements --}}
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/js/tw-elements.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>

    {{-- GSAP, ScrollTrigger --}}
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>

    {{-- SwiperJS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- Datatables --}}
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

    @yield('script')
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                confirmButtonColor: "#56843a",
            })
        </script>
    @endif
</body>

</html>