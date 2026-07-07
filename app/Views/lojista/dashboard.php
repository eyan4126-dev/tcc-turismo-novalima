<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Lojista - iNovaTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --nl-purple: #5D46D2;
            --nl-purple-dark: #402cb3;
            --nl-purple-light: #ECE9FC;
            --nl-magenta: #E6007E;
            --nl-green-neon: #00D369;
            --nl-bg-light: #F4F6F9;
            --nl-text-dark: #1A1A1A;
            --nl-gradient: linear-gradient(90deg, #FF5500 0%, #E6007E 25%, #5D46D2 50%, #0099FF 75%, #00D369 100%);
        }

        body {
            background-color: var(--nl-bg-light);
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 12px;
            background: var(--nl-gradient);
            z-index: 1050;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            min-width: 280px;
            max-width: 280px;
            background-color: #FFFFFF;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.03);
            z-index: 100;
            padding-top: 30px;
        }

        #content {
            width: 100%;
            padding: 40px 30px;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #F0F0F0;
        }

        .brand-logo-container {
            width: 130px;
            height: 130px;
            background-color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 2px solid #E2E8F0;
            margin: 0 auto 1.5rem auto;
        }

        .brand-logo-container img {
            width: 75%;
            height: auto;
            object-fit: contain;
        }

        .nav-sidebar .nav-link {
            color: #6C757D;
            font-weight: 600;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        .nav-sidebar .nav-link:hover,
        .nav-sidebar .nav-link.active {
            color: var(--nl-purple);
            background-color: var(--nl-purple-light);
            border-left-color: var(--nl-purple);
        }

        .card-metric {
            background: #FFFFFF;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(93, 70, 210, 0.04);
            padding: 24px;
        }

        .card-chart {
            background: #FFFFFF;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(93, 70, 210, 0.04);
            padding: 24px;
            height: 100%;
        }

        .chart-container {
            position: relative;
            height: 260px;
            width: 100%;
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header mb-4">
                <div class="brand-logo-container">
                    <img src="<?= base_url('public/logo.png') ?>" alt="iNovaTour Logo">
                </div>
                <span class="fw-extrabold text-nl-purple h4 tracking-tight" style="font-weight:800;">
                    iNova<span style="font-weight:400; color:var(--nl-text-dark);">Tour</span>
                </span>
                <p class="text-muted small mb-0 mt-1">Portal do Lojista</p>
            </div>

            <ul class="nav flex-column nav-sidebar">
                <li class="nav-item">
                    <a href="<?= base_url('lojista/dashboard') ?>"
                        class="nav-link <?= url_is('lojista/dashboard') || url_is('lojista') ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> Desempenho
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('lojista/qrcode') ?>"
                        class="nav-link <?= url_is('lojista/qrcode') ? 'active' : '' ?>">
                        <i class="fa-solid fa-qrcode"></i> Meu QR Code
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
            <div class="mb-5">
                <h2 class="fw-bold mb-1" style="color: var(--nl-purple);">Seu Estabelecimento</h2>
                <p class="text-muted small mb-0">Métricas e insights gerados através dos escaneamentos do seu QR Code.
                </p>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Impacto Estimado</span>
                            <h3 class="fw-bold mb-0">R$
                                <?= number_format($kpis['impacto_economico'], 2, ',', '.') ?>
                            </h3>
                        </div>
                        <div class="metric-icon" style="background: #E6F9ED; color: var(--nl-green-neon);"><i
                                class="fa-solid fa-money-bill-wave"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Total de Visitas</span>
                            <h3 class="fw-bold mb-0">
                                <?= $kpis['volume_turistico'] ?>
                            </h3>
                        </div>
                        <div class="metric-icon" style="background: var(--nl-purple-light); color: var(--nl-purple);"><i
                                class="fa-solid fa-users"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Média de Satisfação</span>
                            <h3 class="fw-bold mb-0">
                                <?= $kpis['satisfacao_media'] ?> <i class="fa-solid fa-star text-warning fs-5"></i>
                            </h3>
                        </div>
                        <div class="metric-icon" style="background: #FFF9E6; color: #FFA800;"><i
                                class="fa-solid fa-star"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">NPS do Local</span>
                            <h3 class="fw-bold mb-0 text-success">
                                <?= $kpis['nps'] ?>
                            </h3>
                        </div>
                        <div class="metric-icon" style="background: #EAF4FF; color: #0099FF;"><i
                                class="fa-solid fa-heart"></i></div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Motivo da Visita dos Clientes</h6>
                        <div class="chart-container">
                            <canvas id="chartMotivosLojista"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Principais Cidades de Origem</h6>
                        <div class="chart-container">
                            <canvas id="chartOrigemLojista"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const configPadrao = {
            responsive: true,
            maintainAspectRatio: false
        };

        const ctxMotivos = document.getElementById('chartMotivosLojista').getContext('2d');
        new Chart(ctxMotivos, {
            type: 'doughnut',
            data: {
                labels: <?= $charts['motivos']['labels'] ?>,
                datasets: [{
                    data: <?= $charts['motivos']['valores'] ?>,
                    backgroundColor: ['#FF5500', '#5D46D2', '#0099FF', '#6C757D']
                }]
            },
            options: configPadrao
        });

        const ctxOrigem = document.getElementById('chartOrigemLojista').getContext('2d');
        new Chart(ctxOrigem, {
            type: 'bar',
            data: {
                labels: <?= $charts['origem']['labels'] ?>,
                datasets: [{
                    label: 'Visitantes',
                    data: <?= $charts['origem']['valores'] ?>,
                    backgroundColor: '#E6007E'
                }]
            },
            options: configPadrao
        });
    </script>
</body>

</html>