<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - 2DAY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            background: #e5e7eb;
            transition: all 0.3s ease;
        }

        .password-strength.weak {
            background: #ef4444;
            width: 33%;
        }

        .password-strength.medium {
            background: #f59e0b;
            width: 66%;
        }

        .password-strength.strong {
            background: #10b981;
            width: 100%;
        }
    </style>
</head>
<body class="bg-white">

    <div class="min-h-screen flex">
        
        <!-- Left Side - Brand -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-gray-900 to-gray-800 flex-col items-center justify-center p-12">
            <div class="max-w-sm text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-3xl mb-8">
                    <span class="text-gray-900 font-bold text-4xl">2D</span>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Bergabung dengan 2DAY</h1>
                <p class="text-gray-400 text-lg mb-8">Temukan ribuan produk pilihan dengan harga terbaik</p>
                
                <div class="space-y-4 text-left">
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-tag text-blue-400 text-xl w-6"></i>
                        <span>Harga Terbaik & Terjangkau</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-star text-blue-400 text-xl w-6"></i>
                        <span>Reward Points Setiap Belanja</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-truck text-blue-400 text-xl w-6"></i>
                        <span>Gratis Ongkos Kirim</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-headset text-blue-400 text-xl w-6"></i>
                        <span>Dukungan Pelanggan 24/7</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                
                <!-- Header Mobile -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-900 rounded-2xl mb-4">
                        <span class="text-white font-bold text-2xl">2D</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">2DAY</h1>
                    <p class="text-gray-600 text-sm mt-1">For Today</p>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex gap-3">
                            <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                            <div>
                                <p class="text-sm text-red-700 font-semibold mb-2">Terjadi kesalahan!</p>
                                <ul class="text-xs text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Nama Lengkap</label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}"
                            required 
                            autofocus 
                            placeholder="Masukkan nama lengkap Anda"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            placeholder="contoh@email.com"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                placeholder="••••••••"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('password') border-red-500 @enderror"
                                oninput="checkPasswordStrength(this.value)">
                            <button 
                                type="button"
                                onclick="togglePassword('password')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="password-icon" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>

                        <!-- Password Strength Meter -->
                        <div class="mt-2 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">Kekuatan:</span>
                                <span class="text-xs font-semibold" id="strength-text">-</span>
                            </div>
                            <div class="password-strength" id="strength-meter"></div>
                        </div>

                        @error('password')
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                placeholder="••••••••"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('password_confirmation') border-red-500 @enderror"
                                oninput="checkPasswordMatch()">
                            <button 
                                type="button"
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="password_confirmation-icon" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        <p class="text-xs mt-1" id="match-status"></p>
                        @error('password_confirmation')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center">
                        <input 
                            id="agree_terms" 
                            type="checkbox" 
                            name="agree_terms"
                            class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                            required>
                        <label for="agree_terms" class="ml-2.5 text-xs text-gray-600">
                            Saya setuju dengan <a href="#" class="font-semibold hover:underline">Syarat & Ketentuan</a>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button 
                        type="submit"
                        class="w-full bg-gray-900 hover:bg-black text-white font-semibold py-2.5 rounded-lg transition duration-200 mt-6">
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500 text-xs">atau</span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-gray-900 font-semibold hover:text-black transition">
                            Masuk di sini
                        </a>
                    </p>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center text-xs text-gray-500">
                    <p>Protected by SSL • 256-bit encryption</p>
                </div>
            </div>
        </div>

    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.add('fa-eye');
                icon.classList.remove('fa-eye-slash');
            }
        }

        function checkPasswordStrength(password) {
            const strengthMeter = document.getElementById('strength-meter');
            const strengthText = document.getElementById('strength-text');
            
            const hasLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

            let strength = 0;
            if (hasLength) strength++;
            if (hasUpper) strength++;
            if (hasLower) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;

            strengthMeter.classList.remove('weak', 'medium', 'strong');
            
            if (strength < 2) {
                strengthMeter.classList.add('weak');
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-xs font-semibold text-red-600';
            } else if (strength < 4) {
                strengthMeter.classList.add('medium');
                strengthText.textContent = 'Sedang';
                strengthText.className = 'text-xs font-semibold text-amber-600';
            } else {
                strengthMeter.classList.add('strong');
                strengthText.textContent = 'Kuat';
                strengthText.className = 'text-xs font-semibold text-emerald-600';
            }

            checkPasswordMatch();
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation');
            const status = document.getElementById('match-status');

            if (confirm.value === '') {
                status.innerHTML = '';
                confirm.classList.remove('border-red-500', 'border-emerald-500');
                return;
            }

            if (password === confirm.value) {
                status.innerHTML = '<i class="fas fa-check-circle text-emerald-600 mr-1"></i> Password cocok';
                status.className = 'text-xs text-emerald-600 mt-1';
                confirm.classList.remove('border-red-500');
                confirm.classList.add('border-emerald-500');
            } else {
                status.innerHTML = '<i class="fas fa-times-circle text-red-600 mr-1"></i> Password tidak cocok';
                status.className = 'text-xs text-red-600 mt-1';
                confirm.classList.remove('border-emerald-500');
                confirm.classList.add('border-red-500');
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const terms = document.getElementById('agree_terms');
            if (!terms.checked) {
                e.preventDefault();
                terms.focus();
                alert('Silakan setujui Syarat & Ketentuan');
            }
        });
    </script>

</body>
</html>