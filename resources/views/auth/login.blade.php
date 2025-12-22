<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Kantong Darah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center text-red-600 mb-2">
                SISTEM MANAJEMEN KANTONG DARAH PMI DAN RUMAH SAKIT TERINTEGRASI
            </h1>
            
            <div class="text-center mb-6">
                <h2 class="text-lg font-semibold text-gray-700">Login Staff</h2>
                <div class="flex justify-center space-x-4 mt-2">
                    <button id="pmi-btn" class="px-4 py-2 bg-red-600 text-white rounded toggle-btn active">
                        Staff PMI
                    </button>
                    <button id="rs-btn" class="px-4 py-2 bg-blue-600 text-white rounded toggle-btn">
                        Staff Rumah Sakit
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                        Username/Email
                    </label>
                    <input type="email" id="email" name="email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                           value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                        Password
                    </label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                           required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    **Silakan Login untuk mengakses sistem**
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('pmi-btn').addEventListener('click', function() {
            document.querySelector('h2').textContent = 'Login Staff PMI';
            this.classList.add('active');
            document.getElementById('rs-btn').classList.remove('active');
        });

        document.getElementById('rs-btn').addEventListener('click', function() {
            document.querySelector('h2').textContent = 'Login Staff Rumah Sakit';
            this.classList.add('active');
            document.getElementById('pmi-btn').classList.remove('active');
        });
    </script>

    <style>
        .toggle-btn.active {
            opacity: 1;
        }
        .toggle-btn:not(.active) {
            opacity: 0.7;
        }
    </style>
</body>
</html>