<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrigado! - iNovaTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= site_url('new-logo2.png') ?>">
    <style>
        :root {
            --nl-purple: #5D46D2;
            --nl-purple-light: #ECE9FC;
            --nl-magenta: #E6007E;
            --nl-gradient: linear-gradient(90deg, #FF5500 0%, #E6007E 25%, #5D46D2 50%, #0099FF 75%, #00D369 100%);
        }

        body {
            background-color: #F4F6F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
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
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(93, 70, 210, 0.08);
            border: 1px solid #E2E8F0;
            padding: 3.5rem 2.5rem;
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
            box-shadow: 0 8px 20px rgba(0, 211, 105, 0.15);
            border: 3px solid #ffffff;
        }

        .btn-nl-primary {
            background-color: var(--nl-purple);
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: 30px;
            padding: 12px 30px;
            transition: all 0.2s ease;
        }

        .btn-nl-primary:hover {
            background-color: #402cb3;
            transform: translateY(-1px);
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center">
        <div class="success-card animate__animated animate__zoomIn">
            <div class="icon-box animate__animated animate__bounceIn animate__delay-1s">
                <i class="fa-solid fa-circle-check animate__animated animate__pulse animate__infinite"></i>
            </div>

            <h3 class="fw-bold mb-3 text-dark" style="letter-spacing: -0.5px;">Sua pesquisa foi registrada!</h3>

            <p class="text-muted small mb-4" style="line-height: 1.5;">
                Muito obrigado por colaborar com o turismo de Nova Lima. Seus dados nos ajudam a monitorar a qualidade
                dos serviços, planejar melhorias estruturais e trazer benefícios fiscais fundamentais (ICMS Turismo)
                para a nossa cidade.
            </p>

            <!-- SELO DE CONFORMIDADE COM LGPD (Destaque Acadêmico) -->
            <div class="p-3 rounded-3 text-start border mb-4" style="background-color: #FAFAFA;">
                <div class="d-flex align-items-start gap-2">
                    <i class="fa-solid fa-shield-halved text-nl-purple mt-1" style="color: var(--nl-purple);"></i>
                    <div class="small text-muted" style="font-size: 0.72rem; line-height: 1.3;">
                        <strong>Segurança de Dados (LGPD):</strong> Seu CPF e identificadores digitais foram
                        criptografados e serão mantidos estritamente para fins de controle de duplicidade de fluxo.
                    </div>
                </div>
            </div>

            <hr class="my-4" style="color: #E2E8F0;">

            <p class="fw-bold small mb-3 text-nl-purple">Pronto para explorar o município?</p>

            <a href="<?= site_url('guia') ?>" class="btn btn-nl-primary w-100 shadow">
                <i class="fa-solid fa-map-location-dot me-2"></i>Acessar Guia de Nova Lima
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>