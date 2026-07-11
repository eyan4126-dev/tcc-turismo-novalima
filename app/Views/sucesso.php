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
            --nl-green-neon: #00D369;
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
            padding: 3rem 2.5rem;
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
            margin: 0 auto 1.5rem auto;
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

        /* Card especial de recompensa de voucher geral da rede */
        .reward-badge-card {
            background: linear-gradient(135deg, var(--nl-purple-light) 0%, #FAFAFA 100%);
            border: 2px dashed var(--nl-purple);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
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
                Muito obrigado por colaborar com o turismo de Nova Lima. Seus dados ajudam o município a monitorar a
                qualidade dos serviços públicos e trazem benefícios fiscais fundamentais (ICMS Turismo).
            </p>

            <!-- NOVO CARD: Revelado dinamicamente para turistas que avaliaram pontos sem desconto imediato -->
            <div id="voucherRewardBox" class="reward-badge-card d-none text-start">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="bg-white rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 45px; height: 40px;">
                        <i class="fa-solid fa-gift text-nl-magenta fs-4" style="color: var(--nl-magenta);"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark m-0">Você conquistou 10% OFF!</h6>
                        <small class="text-muted">Recompensa da Rede de Vantagens</small>
                    </div>
                </div>
                <p class="small text-muted mb-0 mt-2" style="font-size:0.75rem; line-height: 1.4;">
                    Como você avaliou um atrativo natural/cultural, seu desconto de 10% está salvo no celular e pode ser
                    usado em **qualquer restaurante, pousada ou cervejaria parceira** listada no Guia!
                </p>
            </div>

            <div class="p-3 rounded-3 text-start border mb-4" style="background-color: #FAFAFA;">
                <div class="d-flex align-items-start gap-2">
                    <i class="fa-solid fa-shield-halved text-nl-purple mt-1" style="color: var(--nl-purple);"></i>
                    <div class="small text-muted" style="font-size: 0.72rem; line-height: 1.3;">
                        <strong>Segurança de Dados (LGPD):</strong> Seu CPF e assinatura digital foram convertidos em
                        hash seguro unicamente para controle de duplicidade de fluxo.
                    </div>
                </div>
            </div>

            <hr class="my-4" style="color: #E2E8F0;">

            <p class="fw-bold small mb-3 text-nl-purple" style="color: var(--nl-purple);">Pronto para explorar o
                município?</p>

            <a href="<?= site_url('guia') ?>" class="btn btn-nl-primary w-100 shadow">
                <i class="fa-solid fa-map-location-dot me-2"></i>Acessar Guia de Nova Lima
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Verifica se existe um voucher pendente de uso na rede no LocalStorage do celular
            const voucherObj = localStorage.getItem('inovatour_voucher');
            if (voucherObj) {
                try {
                    const voucher = JSON.parse(voucherObj);
                    // Se o voucher for do tipo 'rede_parceira' (geral), parabeniza na tela
                    if (voucher && voucher.status === 'pendente') {
                        document.getElementById('voucherRewardBox').classList.remove('d-none');
                    }
                } catch (e) {
                    console.error("Erro ao interpretar voucher local", e);
                }
            }
        });
    </script>
</body>

</html>