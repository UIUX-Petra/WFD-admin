@extends('partials.layout2')

@section('content')
@if (session()->has('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: '{{ session('error') }}',
            confirmButtonColor: '#3085d6',
        });
    </script>
@endif

<style>
    body {
        margin: 0;
        font-family: sans-serif;
        min-height: 100vh;
        background: linear-gradient(to top, #1C2245 0%, #30366A 100%);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(to right, #FFD249, #F4AB24);
        border-radius: 3px;
    }

    .shine-container {
        position: relative;
        overflow: hidden;
    }

    .shine-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -75%;
        width: 50%;
        height: 200%;
        background: linear-gradient(
            120deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.4) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        transform: skewX(-20deg);
    }

    .shine-container:hover::before {
        animation: shineMove 1s ease forwards;
    }
    
    .right-side-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 30%, rgba(255, 210, 73, 0.15), transparent 60%),
                radial-gradient(circle at 70% 80%, rgba(244, 171, 36, 0.08), transparent 65%);
    z-index: 0;
    pointer-events: none;
    }


    @keyframes shineMove {
        0% {
            left: -75%;
        }
        100% {
            left: 125%;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="grid grid-cols-1 md:grid-cols-2 w-full min-h-screen">
    <div class="left-side relative flex items-center justify-center p-10">
        <div class="absolute inset-0 bg-gradient-to-b from-[#F4AB24cc] to-[#FFD249cc] z-10"></div>
        <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('{{ asset('image/discuss.jpg') }}');"></div>
        <div class="relative z-20 w-full max-w-xl flex justify-center items-center">
            <div class="flex flex-row items-center justify-center gap-6">
                <img src="{{ asset('image/p2p%20logo%20-%20white.svg') }}" alt="P2P Logo"
                    class="h-20 md:h-24 w-auto opacity-0 animate-[fadeIn_0.7s_ease-out_forwards] delay-[200ms]">
                <img src="{{ asset('image/PCU-LOGO-1024x247.png') }}" alt="PCU Logo"
                    class="h-16 md:h-20 w-auto opacity-0 animate-[fadeIn_0.7s_ease-out_forwards] delay-[400ms]">
            </div>
        </div>
    </div>


    <!-- Right Column: Login Form -->
    <div class="relative flex items-center justify-center px-4 md:px-10 py-10 bg-transparent">
    
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(255,210,73,0.15),transparent_60%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_80%,rgba(244,171,36,0.08),transparent_65%)]"></div>
        </div>

        <!-- 💠 Container Login -->
        <div class="shine-container relative z-10 bg-white/10 border border-white/20 backdrop-blur-lg rounded-2xl shadow-2xl p-8 w-full max-w-md text-center text-white opacity-0 animate-[fadeIn_0.7s_ease-out_0.4s_forwards] transition-all duration-500 transform hover:scale-[1.01]">
            
            <!-- Icon Welcome -->
            <div class="flex justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-user">
                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                    <path d="M6.376 18.91a6 6 0 0 1 11.249.003"/>
                    <circle cx="12" cy="11" r="4"/>
                </svg>
            </div>

            <p class="text-lg mb-1">Hello Admin 👋</p>
            <h2 class="text-3xl font-bold form-title mb-4 relative">SIGN IN</h2>
            <p class="text-base text-gray-300 mb-3">Login using your Petra Email account</p>

            <!-- Login Button -->
            <button onclick="window.location.href='{{ route('admin.auth') }}'"
                class="w-full bg-gradient-to-r from-[#F4AB24] to-[#FFD249] text-white font-semibold py-3 rounded-xl shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 flex items-center justify-center">
                <span class="inline-flex items-center justify-center p-1.5 bg-white rounded-full shadow-md mr-3">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="-3 0 262 262" fill="currentColor">
                        <path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4" />
                        <path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853" />
                        <path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05" />
                        <path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335" />
                    </svg>
                </span>
                Petra Email
            </button>

            <!-- Footer Mini -->
            <p class="mt-6 text-sm text-gray-300 opacity-70">
                © {{ date('Y') }} P2P. All rights reserved.
            </p>
        </div>
    </div>


</div>
@endsection
