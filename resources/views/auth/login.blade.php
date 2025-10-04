<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login PPDB - Pondok Pesantren Bani Syahid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
        }
        .login-container {
            box-shadow: 0 15px 35px rgba(5, 117, 114, 0.3);
            background: linear-gradient(145deg, #057572, #04615f);
        }
        .btn-login {
            background: #002F2D;
            color: white;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .btn-login:hover {
            background: #001f1e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 47, 45, 0.4);
        }
        .btn-google {
            background: #002F2D;
            color: white;
            transition: all 0.3s ease;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-google:hover {
            background: #001f1e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 47, 45, 0.4);
        }
        .google-icon {
            background: conic-gradient(from -45deg, #ea4335 110deg, #4285f4 90deg 180deg, #34a853 180deg 270deg, #fbbc05 270deg) 73% 55%/150% 150% no-repeat;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
        }
        .input-field {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
            border-color: white;
            background: white;
        }
        .text-white-90 {
            color: rgba(255, 255, 255, 0.9);
        }
        .divider {
            border-color: rgba(255, 255, 255, 0.3);
        }
        .logo-container {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        .logo-text {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #ffffff, #e0f2f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-container text-white rounded-2xl w-full max-w-md overflow-hidden">
        <!-- Header dengan Logo PFDB -->
        <div class="p-6 text-center border-b border-white/20">
            <div class="logo-container">
                <div class="logo-text">PFDB</div>
            </div>
            <h1 class="text-2xl font-bold mt-2">Login PPDB</h1>
            <p class="text-white-90 text-sm mt-1">Pondok Pesantren Bani Syahid</p>
        </div>

        <!-- Form Login -->
        <div class="p-6 md:p-8">
            <form class="space-y-5">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-white-90 text-sm font-medium mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500"
                        placeholder="Masukkan email Anda"
                    >
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-white-90 text-sm font-medium mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500"
                        placeholder="Masukkan password Anda"
                    >
                </div>

                <!-- Login Button -->
                <button
                    type="submit"
                    class="btn-login w-full py-3 rounded-lg font-medium transition duration-200 shadow-md"
                >
                    Login
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-grow border-t divider"></div>
                <span class="mx-4 text-white-90 text-sm">Atau</span>
                <div class="flex-grow border-t divider"></div>
            </div>

            <!-- Google Login Button -->
            <button
                class="btn-google w-full flex items-center justify-center gap-3 py-3 rounded-lg font-medium transition duration-200 shadow-md"
            >
                <span class="google-icon text-xl font-bold">G</span>
                Login dengan Google
            </button>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-white-90 text-sm">
                    Belum punya akun?
                    <a href="#" class="text-white font-medium hover:underline ml-1">Daftar di sini</a>
                </p>
            </div>
        </div>


    </div>
</body>
</html>
