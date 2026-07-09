<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrigado! - iNovaTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= base_url('new-logo2.png') ?>">
    <style>
        :root {
            --nl-purple: #5D46D2;
            --nl-gradient: linear-gradient(90deg, #FF5500 0%, #E6007E 25%, #5D46D2 50%, #0099FF 75%, #00D369 100%);
        }

        body {
            background-color: #F4F6F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            padding-top: 12px;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 12px;
            background: var(--nl-gradient);
            z-index: 1050;
        }

        .success-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(93, 70, 210, 0.08);
            border: none;
            padding: 3rem 2rem;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .icon-box {
            width: 90px;
            height: 90px;
            background-color: #E8F9EE;
            color: #00D369;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 2rem auto;
            box-shadow: 0 4px 15px rgba(0, 211, 105, 0.15);
        }

        .btn-nl-primary {
            background-color: var(--nl-purple);
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: 30px;
            padding: 12px 30px;
            transition: all 0.2s;
        }

        .btn-nl-primary:hover {
            background-color: #402cb3;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center">
        <div class="success-card">
            <div class="icon-box">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="fw-bold mb-3" style="letter-spacing: -0.5px;">Sua pesquisa foi registrada!</h3>
            <p class="text-muted small mb-4">
                Muito obrigado por colaborar com o turismo de Nova Lima. Seus dados nos ajudam a monitorar a qualidade
                dos serviços e trazem benefícios fiscais para a cidade.
            </p>
            <hr class="my-4" style="color: #E2E8F0;">
            <p class="fw-bold small mb-3 text-nl-purple" style="color: var(--nl-purple);">Pronto para explorar o
                município?</p>
            <a href="<?= base_url('guia') ?>" class="btn btn-nl-primary w-100 shadow">
                <i class="fa-solid fa-map-location-dot me-2"></i>Acessar Guia de Nova Lima
            </a>
        </div>
    </div>
</body>

</html>