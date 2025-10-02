<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Votre compte a été créé</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #004f1c;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient( #153127 0%, #004f1c 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .credentials {
            background: #ecffe1;
            border: 1px solid #b2ffdf;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials h3 {
            color: #153127;
            margin-top: 0;
        }
        .info-item {
            margin-bottom: 10px;
            padding: 8px 0;
        }
        .info-label {
            font-weight: bold;
            color: #153127;
        }
        .footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            color: #90f776;
            font-size: 14px;
        }
        .warning {
            background: #ffebee;
            border: 1px solid #ffcdd2;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #c62828;
        }
        .btn {
            display: inline-block;
            text-decoration: none;
            background: linear-gradient( #153127 0%, #004f1c 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>La Coordination</h1>
            <p>Plateforme de gestion médicale</p>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $user->firstname }} ,</h2>
            
            <p>Votre compte a été créé avec succès sur notre plateforme de coordination médicale.</p>
            
            <div class="credentials">
                <h3>Vos identifiants de connexion :</h3>
                
                <div class="info-item">
                    <span class="info-label">Email :</span> {{ $user->email }}
                </div>
                
                <div class="info-item">
                    <span class="info-label">Mot de passe :</span> {{ $password }}
                </div>
                
                {{-- <div class="info-item">
                    <span class="info-label">Type de compte :</span> 
                    {{ $user->is_admin ? 'Administrateur' : 'Utilisateur' }}
                </div> --}}
            </div>
            
            {{-- <div class="warning">
                <strong>Important :</strong> Pour des raisons de sécurité, nous vous recommandons de 
                changer votre mot de passe après votre première connexion.
            </div> --}}
            
            <p>
                <a href="{{ url('/') }}" class="btn">Accéder à la plateforme</a>
            </p>
            
            <p>Si vous rencontrez des problèmes pour vous connecter, n'hésitez pas à nous contacter.</p>
            
            <p>Cordialement,<br>L'équipe La Coordination</p>
        </div>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>&copy; {{ date('Y') }} La Coordination. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>