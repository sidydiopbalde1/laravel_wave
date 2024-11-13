<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #2d3748;
            margin-bottom: 30px;
            font-size: 2em;
            font-weight: 700;
        }

        .info-card {
            background: #f7fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
            border-left: 4px solid #667eea;
        }

        .info-label {
            color: #718096;
            font-size: 0.9em;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-value {
            color: #2d3748;
            font-size: 1.1em;
            font-weight: 500;
        }

        .qr-section {
            margin-top: 30px;
            padding: 20px;
            background: #edf2f7;
            border-radius: 12px;
        }

        .qr-title {
            color: #4a5568;
            margin-bottom: 20px;
            font-size: 1.2em;
        }

        .qr-code {
            background: white;
            padding: 20px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .qr-code img {
            max-width: 200px;
            height: auto;
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue, {{ $nom }} {{ $prenom }}</h1>
        
        <div class="info-card">
            <div class="info-label">Code Secret</div>
            <div class="info-value">{{ $code_secret }}</div>
        </div>

        <div class="info-card">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $email }}</div>
        </div>

        <div class="qr-section">
            <div class="qr-title">Scannez ce QR code pour accéder à vos informations</div>
            <div class="qr-code">
                <img src="{{ $qrCodePath }}" alt="QR Code">
            </div>
        </div>
    </div>
</body>
</html>