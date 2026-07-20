<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Bimbingan Belajar Peta Ilmu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* Subtle background pattern to match public theme */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 350px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            z-index: 1;
            border-bottom-left-radius: 50% 20px;
            border-bottom-right-radius: 50% 20px;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(30, 60, 114, 0.12);
        }

        .logo-area {
            margin-bottom: 25px;
        }

        .logo-img {
            max-height: 80px;
            width: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }

        .login-title {
            color: #2c3e50;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .login-subtitle {
            color: #6c757d;
            font-size: 13px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
            position: relative;
        }

        .form-label {
            display: block;
            color: #495057;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px 12px 42px;
            background: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 8px;
            color: #212529;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
        }

        .form-input:focus + .input-icon {
            color: #3498db;
        }

        .error-message {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            color: #c53030;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .success-message {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            color: #22543d;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .submit-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #2980b9 0%, #1f618d 100%);
            box-shadow: 0 6px 15px rgba(52, 152, 219, 0.3);
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(1px);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #6c757d;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #3498db;
        }

        .back-link i {
            margin-right: 5px;
            font-size: 12px;
        }
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            .login-card {
                padding: 30px 15px;
            }
            body::before {
                height: 250px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <div class="logo-area">
            <img src="{{ asset('petailmu/images/IMG_3898.PNG') }}" alt="Logo Peta Ilmu" class="logo-img" />
            <h1 class="login-title">Login Admin</h1>
            <p class="login-subtitle">Bimbingan Belajar Peta Ilmu</p>
        </div>

        @if (session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username admin" value="{{ old('username') }}" required autofocus autocomplete="username">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan password admin" required autocomplete="current-password">
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <span>Masuk</span>
                <i class="fas fa-sign-in-alt"></i>
            </button>
        </form>

        <a href="/" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</div>

</body>
</html>
