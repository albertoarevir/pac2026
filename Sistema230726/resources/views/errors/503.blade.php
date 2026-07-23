<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema en Mantención - PAC Dilocar</title>
    <style @cspNonce>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #02183A 0%, #042a63 60%, #1a4a9f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            padding: 50px 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .logo {
            width: 110px;
            height: auto;
            margin-bottom: 20px;
        }

        .icon-wrench {
            font-size: 64px;
            margin-bottom: 10px;
            animation: giro 3s linear infinite;
            display: inline-block;
        }

        @keyframes giro {
            0%   { transform: rotate(0deg);   }
            20%  { transform: rotate(-20deg); }
            40%  { transform: rotate(20deg);  }
            60%  { transform: rotate(-15deg); }
            80%  { transform: rotate(15deg);  }
            100% { transform: rotate(0deg);   }
        }

        .badge {
            display: inline-block;
            background: #FEF3C7;
            color: #92400E;
            border: 1.5px solid #F59E0B;
            border-radius: 30px;
            padding: 6px 20px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            font-weight: 800;
            color: #02183A;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 16px;
            color: #475569;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .info-box {
            background: #F0F7FF;
            border-left: 4px solid #2563EB;
            border-radius: 8px;
            padding: 16px 20px;
            text-align: left;
            margin-bottom: 30px;
            font-size: 14px;
            color: #1e3a8a;
            line-height: 1.7;
        }

        .info-box strong { color: #02183A; }

        .dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .dot {
            width: 12px; height: 12px;
            border-radius: 50%;
            background: #2563EB;
            animation: pulso 1.4s ease-in-out infinite;
        }

        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes pulso {
            0%, 80%, 100% { transform: scale(0.7); opacity: 0.5; }
            40%           { transform: scale(1.2); opacity: 1; }
        }

        .footer {
            font-size: 14px;
            color: #ca4b3a;
            border-top: 1px solid #E2E8F0;
            padding-top: 20px;
        }

        .footer strong { color: #475569; }

        @media (max-width: 480px) {
            .card { padding: 35px 25px; }
            h1    { font-size: 22px; }
        }
    </style>
</head>
<body>
    <div class="card">

        <img src="/dist/img/carabineros.png"
             alt="Logo Carabineros"
             class="logo"
             onerror="this.style.display='none'">

        <div class="icon-wrench">🔧</div>

        <div class="badge">⚠️ &nbsp;Sistema temporalmente no disponible</div>

        <h1>Sistema en Mantención</h1>

        <p class="subtitle">
            El <strong>Sistema Plan Anual de Compras – DILOCAR</strong> se encuentra
            en proceso de mantenimiento o actualización.<br>
            Lamentamos los inconvenientes.
        </p>

        <div class="info-box">
            <strong>📋 ¿Qué está ocurriendo?</strong><br>
            Nuestro equipo técnico está trabajando para mejorar el sistema.<br><br>
            <strong>⏱ ¿Cuánto tiempo tomará?</strong><br>
            Estaremos de vuelta a la brevedad. Intente nuevamente en unos minutos.
        </div>

        <div class="dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>

        <div class="footer">
            <strong>Soporte Técnico:</strong> Suboficial (Sec.) Rivera · Asesoría Técnica Dilocar<br>
            Mesa de ayuda: <strong>IP-26407</strong>
        </div>
    </div>
</body>
</html>
