<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - 2DAY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white">

    <div class="min-h-screen flex">
        
        <!-- Left Side - Brand -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-gray-900 to-gray-800 flex-col items-center justify-center p-12">
            <div class="max-w-sm text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-3xl mb-8">
                    <span class="text-gray-900 font-bold text-4xl">2D</span>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">2DAY Admin</h1>
                <p class="text-gray-400 text-lg mb-8">Dashboard Management System</p>
                
                <div class="space-y-4 text-left">
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-chart-line text-blue-400 text-xl w-6"></i>
                        <span>Real-time Analytics</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-users text-blue-400 text-xl w-6"></i>
                        <span>User Management</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-box text-blue-400 text-xl w-6"></i>
                        <span>Product Control</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                
                <!-- Header Mobile -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-900 rounded-2xl mb-4">
                        <span class="text-white font-bold text-2xl">2D</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">2DAY Admin</h1>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus 
                            placeholder="admin@2day.com"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="text-sm font-semibold text-gray-900">Password</label>
                            <a href="{{ route('password.request') }}" class="text-xs text-gray-600 hover:text-gray-900 font-medium transition">
                                Forgot?
                            </a>
                        </div>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                placeholder="••••••••"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('password') border-red-500 @enderror">
                            <button 
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="eyeIcon" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember"
                            class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        <label for="remember_me" class="ml-2.5 text-sm text-gray-600">Remember me</label>
                    </div>

                    <!-- Submit -->
                    <button 
                        type="submit"
                        class="w-full bg-gray-900 hover:bg-black text-white font-semibold py-2.5 rounded-lg transition duration-200 mt-6">
                        Sign In
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-6 text-center text-xs text-gray-500">
                    <p>Protected by SSL • 256-bit encryption</p>
                </div>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const field = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            
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
    </script>

</body>
</html>