<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Solicitud de Retiro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            color: #000;
            padding: 20px;
            line-height: 1.6;
        }

        .card {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #000;
            color: #ffd700;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .content {
            margin-top: 20px;
        }

        .content p {
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>Solicitud de Retiro</h2>
        </div>
        <div class="content">
            <p>Hola {{ $user->firstname }},</p>
            <p>Recibimos tu solicitud de retiro de fondos desde nuestra plataforma.</p>
            <p>Detalles de la solicitud:</p>
            <ul>
                <li>Fecha de solicitud: {{ $today }}</li>
                <li>Monto a retirar: ${{ $amount }} USD</li>
            </ul>
            <p>Procesaremos tu solicitud lo antes posible. Si tienes alguna pregunta o inquietud, no dudes en ponerte en contacto con nuestro equipo de soporte.</p>
            <p>¡Gracias por confiar en nosotros!</p>
            <p>Saludos cordiales,</p>
            <p>El equipo de Royal Empire</p>
        </div>
        <div class="footer">
            <p>© 2023 Royal Empire. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
