<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - SiReKa (Sistem Rekonsiliasi Kas)</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #00346f; /* Original SiReKa blue style */
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        .login-wrapper {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            width: 100%;
            max-w: 420px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            padding: 32px 24px;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>

    <div class="login-wrapper">
        <div class="login-card max-w-[420px] w-full">
            <div class="text-center mb-6">
                @php
                    $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first();
                    $logoApp = ($pengaturanGlobal && $pengaturanGlobal->logo) 
                        ? (\Illuminate\Support\Str::startsWith($pengaturanGlobal->logo, 'http') ? $pengaturanGlobal->logo : asset('storage/' . $pengaturanGlobal->logo)) 
                        : null;
                @endphp
                <div class="flex justify-center mb-3">
                    @if($logoApp)
                        <img src="{{ $logoApp }}" alt="Logo" class="h-[100px] object-contain">
                    @else
                        <div class="h-[100px] w-[100px] bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 font-bold">LOGO</span>
                        </div>
                    @endif
                </div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-wide">SiReKa</h2>
                <h6 class="text-sm text-gray-500 italic mb-2">Sistem Rekonsiliasi Kas</h6>
                <h2 class="text-lg font-bold text-gray-800">Pemerintah Kabupaten Tapin</h2>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center text-sm text-blue-600 font-bold" :status="session('status')" />
            
            <form action="{{ route('login') }}" method="POST" autocomplete="off" class="space-y-4">
                @csrf

                <!-- Email / KDUser -->
                <div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Pengguna" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#00346f] focus:border-[#00346f] text-gray-700">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-blue-600" />
                </div>

                <!-- Password -->
                <div>
                    <input id="password" type="password" name="password" required placeholder="Password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#00346f] focus:border-[#00346f] text-gray-700">
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-blue-600" />
                </div>

                <!-- Tahun Login -->
                @php
                    $tahunAnggarans = \App\Models\TahunAnggaran::where('is_active', true)->orderBy('tahun', 'desc')->get();
                @endphp
                <div>
                    <select id="tahun_login" name="tahun_login" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#00346f] focus:border-[#00346f] text-gray-700 font-semibold">
                        @forelse($tahunAnggarans as $ta)
                            <option value="{{ $ta->tahun }}" {{ date('Y') == $ta->tahun ? 'selected' : '' }}>{{ $ta->tahun }}</option>
                        @empty
                            <option value="{{ date('Y') }}">{{ date('Y') }} (Default)</option>
                        @endforelse
                    </select>
                </div>

                <!-- Math Captcha -->
                <div>
                    <label class="block text-sm text-gray-600 mb-1 text-center font-medium">Pertanyaan Keamanan: Berapa {{ $num1 ?? 0 }} + {{ $num2 ?? 0 }}?</label>
                    <input id="captcha" type="number" name="captcha" required placeholder="Jawaban" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#00346f] focus:border-[#00346f] text-center font-bold text-gray-700">
                    <x-input-error :messages="$errors->get('captcha')" class="mt-1 text-xs text-blue-600 text-center" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mt-2">
                    <input id="customCheck1" type="checkbox" name="remember" class="w-4 h-4 text-[#00346f] bg-gray-100 border-gray-300 rounded focus:ring-[#00346f] focus:ring-2">
                    <label for="customCheck1" class="ml-2 text-sm font-medium text-gray-700">Remember me</label>
                </div>

                <!-- Submit -->
                <div class="pt-3">
                    <button type="submit" class="w-full bg-[#00346f] hover:bg-[#00224d] text-white font-bold py-2.5 px-4 rounded transition-colors duration-200">
                        Log In
                    </button>
                </div>
            </form>

            @if($pengaturanGlobal && $pengaturanGlobal->is_registration_open)
            <div class="mt-4 text-center border-t border-gray-200 pt-4">
                <p class="text-sm text-gray-600 mb-1">Belum memiliki akun operator SKPD?</p>
                <a href="{{ route('register') }}" class="text-sm font-bold text-[#00346f] hover:underline">
                    Daftar Akun Mandiri
                </a>
            </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        particlesJS('particles-js',
        {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 5,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 6,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "repulse": {
                        "distance": 200
                    },
                    "push": {
                        "particles_nb": 4
                    }
                }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>
