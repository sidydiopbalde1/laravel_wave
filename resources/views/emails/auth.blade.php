<!DOCTYPE html>
<html>
<head>
    <title>Informations d'authentification</title>
</head>
<body>
    <h1>Bienvenue, {{ $prenom }} {{ $nom }}</h1>
    <p>Voici vos informations de connexion :</p>
    <p><strong>Email :</strong> {{ $email }}</p>
    <p><strong>Mot de passe :</strong> {{ $password }}</p>
    <p><strong>Code secret :</strong> {{ $code_secret }}</p>
    <p>QR Code :</p>
    <img src="data:image/png;base64,{{ $qrCodePath }}" alt="QR Code" />
    <p>En pièce jointe, vous trouverez un document PDF contenant ces informations ainsi qu'un QR code pour un accès simplifié.</p>
</body>
</html>
