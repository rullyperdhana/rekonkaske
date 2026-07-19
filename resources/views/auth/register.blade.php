<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Pendaftaran Akun - SiReKe (Sistem Rekonsiliasi Kas)</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <!-- TomSelect CSS for better select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #00346f;
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
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            padding: 32px 24px;
        }
        /* Override TomSelect styles to match input */
        .ts-control {
            border: 1px solid #d1d5db !important;
            border-radius: 0.25rem !important;
            padding: 0.5rem 1rem !important;
            min-height: 42px;
        }
        .ts-control.focus {
            border-color: #00346f !important;
            box-shadow: 0 0 0 2px rgba(0, 52, 111, 0.2) !important;
        }
        .ts-control input {
            font-size: 1rem !important;
            font-family: inherit !important;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="text-center mb-6">
                @php
                    $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first();
                    $logoApp = ($pengaturanGlobal && $pengaturanGlobal->logo) 
                        ? (\Illuminate\Support\Str::startsWith($pengaturanGlobal->logo, 'http') ? $pengaturanGlobal->logo : asset('storage/' . $pengaturanGlobal->logo)) 
                        : null;
                @endphp
                <div class="flex justify-center mb-3">
                    @if($logoApp)
                        <img src="{{ $logoApp }}" alt="Logo" class="h-[80px] object-contain">
                    @else
                        <div class="h-[80px] w-[80px] bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 font-bold">LOGO</span>
                        </div>
                    @endif
                </div>
                <h2 class="text-xl font-bold text-gray-800">Pendaftaran Akun Operator SKPD</h2>
                <p class="text-sm text-gray-500 mt-1">Sistem Rekonsiliasi Kas Kab. Tapin</p>
            </div>

            <x-auth-session-status class="mb-4 text-center text-sm text-blue-600 font-bold" :status="session('status')" />
            
            <form action="{{ route('register') }}" method="POST" autocomplete="off" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan Nama Anda" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#00346f] focus:border-[#00346f] text-gray-700">
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Pengguna</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: op.skpd@tapinkab.go.id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#00346f] focus:border-[#00346f] text-gray-700">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- SKPD -->
                <div>
                    <label for="skpd_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih SKPD (Instansi)</label>
                    <select id="skpd_id" name="skpd_id" required>
                        <option value="">-- Pilih SKPD Anda --</option>
                        @foreach($skpds as $skpd)
                            <option value="{{ $skpd->id }}" {{ old('skpd_id') == $skpd->id ? 'selected' : '' }}>
                                {{ $skpd->kode }} - {{ $skpd->nama }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">*Hanya SKPD yang belum memiliki operator yang akan tampil.</p>
                    <x-input-error :messages="$errors->get('skpd_id')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- Password -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required placeholder="Minimal 8 karakter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#00346f] focus:border-[#00346f] text-gray-700">
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-600" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Ulangi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Konfirmasi password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#00346f] focus:border-[#00346f] text-gray-700">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-600" />
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#00346f] hover:bg-[#00224d] text-white font-bold py-2.5 px-4 rounded transition-colors duration-200">
                        Daftar Akun
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center border-t border-gray-200 pt-4">
                <p class="text-sm text-gray-600 mb-1">Sudah memiliki akun?</p>
                <a href="{{ route('login') }}" class="text-sm font-bold text-[#00346f] hover:underline">
                    Kembali ke halaman Login
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        // Inisialisasi TomSelect untuk SKPD
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#skpd_id",{
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Cari SKPD..."
            });
        });

        // Konfigurasi Particles.js sama persis dengan Login
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
                    "grab": {
                        "distance": 400,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 100,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>
