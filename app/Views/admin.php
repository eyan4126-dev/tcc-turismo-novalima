<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gerencial - Turismo Hub</title>
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
            width: 80px;
            height: 80px;
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

        /* Container criado para limitar o crescimento desenfreado dos gráficos */
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

        .table-container {
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(93, 70, 210, 0.04);
            padding: 25px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header mb-4">
                <div class="brand-logo-container">
                    <img src="public\logo.png" alt="iNovaTour Logo">
                </div>
                <span class="fw-extrabold text-nl-purple h4 tracking-tight" style="font-weight:800;">
                    iNova<span style="font-weight:400; color:var(--nl-text-dark);">Tour</span>
                </span>
                <p class="text-muted small mb-0 mt-1">Painel da Prefeitura</p>
            </div>

            <ul class="nav flex-column nav-sidebar">
                <li class="nav-item">
                    <a href="<?= base_url('admin') ?>" class="nav-link <?= url_is('admin') ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('estabelecimentos') ?>" class="nav-link <?= url_is('estabelecimentos') ? 'active' : '' ?>">
                        <i class="fa-solid fa-store"></i> Estabelecimentos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('qrcodes') ?>" class="nav-link <?= url_is('qrcodes') ? 'active' : '' ?>">
                        <i class="fa-solid fa-qrcode"></i> QR Codes Gerados
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair do Sistema
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
            <form method="GET" action="<?= base_url('admin') ?>" class="row g-3 mb-5 align-items-center">
                <div class="col-md-4">
                    <h2 class="fw-bold mb-1" style="color: var(--nl-purple);">Dashboard Gerencial</h2>
                    <p class="text-muted small mb-0">Dados consolidados de estabelecimentos ativos.</p>
                </div>
                <div class="col-md-3">
                    <select name="periodo" class="form-select" onchange="this.form.submit()">
                        <option value="atual">Mês Corrente (Padrão)</option>
                        <option value="historico">Todo o Histórico</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <select name="id_evento" class="form-select" onchange="this.form.submit()">
                        <option value="">Filtrar por Evento (Fixo / Todos Correntes)</option>
                        <?php foreach ($eventosFiltro as $ev): ?>
                            <option value="<?= $ev['id_estabelecimento'] ?>" <?= request()->getGet('id_evento') == $ev['id_estabelecimento'] ? 'selected' : '' ?>>
                                <?= esc($ev['razao_social']) ?> (Sazonal)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <div class="row g-4 mb-4">
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Impacto Econômico</span>
                            <h3 class="fw-bold mb-0">R$ <?= number_format($kpis['impacto_economico'], 2, ',', '.') ?></h3>
                        </div>
                        <div class="metric-icon" style="background: #E6F9ED; color: var(--nl-green-neon);"><i class="fa-solid fa-money-bill-wave"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Volume Turístico</span>
                            <h3 class="fw-bold mb-0"><?= $kpis['volume_turistico'] ?></h3>
                        </div>
                        <div class="metric-icon" style="background: var(--nl-purple-light); color: var(--nl-purple);"><i class="fa-solid fa-users"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Ocupação Hoteleira</span>
                            <h3 class="fw-bold mb-0"><?= $kpis['ocupacao_hoteleira'] ?>%</h3>
                        </div>
                        <div class="metric-icon" style="background: #FFF5F5; color: var(--nl-magenta);"><i class="fa-solid fa-bed"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Média de Satisfação</span>
                            <h3 class="fw-bold mb-0"><?= $kpis['satisfacao_media'] ?> <i class="fa-solid fa-star text-warning fs-5"></i></h3>
                        </div>
                        <div class="metric-icon" style="background: #FFF9E6; color: #FFA800;"><i class="fa-solid fa-star"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">NPS Geral</span>
                            <h3 class="fw-bold mb-0 <?= $kpis['nps'] >= 50 ? 'text-success' : 'text-warning' ?>"><?= $kpis['nps'] ?></h3>
                        </div>
                        <div class="metric-icon" style="background: #EAF4FF; color: #0099FF;"><i class="fa-solid fa-heart"></i></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Ranking de Cidades de Origem (Top 5)</h6>
                        <div class="chart-container">
                            <canvas id="chartCidades"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Distribuição Financeira por Setor</h6>
                        <div class="chart-container">
                            <canvas id="chartSetores"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Motivo da Visita</h6>
                        <div class="chart-container">
                            <canvas id="chartMotivos"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Perfil de Hospedagem (Visitantes que Pernoitam)</h6>
                        <div class="chart-container">
                            <canvas id="chartHospedagem"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <h5 class="fw-bold mb-4" style="color: var(--nl-purple);"><i class="fa-solid fa-clock-rotate-left me-2"></i>Solicitações de Cadastro Pendentes (Onboarding Lojistas)</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Responsável</th>
                                <th>Razão Social / CNPJ</th>
                                <th>Setor</th>
                                <th class="text-end">Ações de Controle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($solicitacoes)): foreach ($solicitacoes as $sol): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= esc($sol['nome_responsavel']) ?></div><span class="text-muted small"><?= esc($sol['email']) ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= esc($sol['razao_social']) ?></div><span class="text-muted small"><?= esc($sol['cnpj']) ?></span>
                                        </td>
                                        <td><span class="badge bg-secondary"><?= esc($sol['setor']) ?></span></td>
                                        <td class="text-end">
                                            <form action="<?= base_url('admin/aprovar/' . $sol['id_usuario']) ?>" method="POST" class="d-inline">
                                                <button class="btn btn-sm btn-success fw-bold me-1"><i class="fa-solid fa-check me-1"></i> Ativar e Gerar QR</button>
                                            </form>
                                            <form action="<?= base_url('admin/recusar/' . $sol['id_usuario']) ?>" method="POST" class="d-inline">
                                                <button class="btn btn-sm btn-outline-danger fw-bold"><i class="fa-solid fa-xmark"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="4" class="text-muted text-center py-4">Nenhuma solicitação de lojista pendente no momento.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Opção padrão para travar e respeitar as dimensões da div container externa
        const configPadrao = {
            responsive: true,
            maintainAspectRatio: false
        };

        const ctxCidades = document.getElementById('chartCidades').getContext('2d');
        new Chart(ctxCidades, {
            type: 'bar',
            data: {
                labels: <?= $charts['cidades']['labels'] ?>,
                datasets: [{
                    indexAxis: 'y',
                    data: <?= $charts['cidades']['valores'] ?>,
                    backgroundColor: '#5D46D2'
                }]
            },
            options: {
                ...configPadrao,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const ctxSetores = document.getElementById('chartSetores').getContext('2d');
        new Chart(ctxSetores, {
            type: 'polarArea',
            data: {
                labels: <?= $charts['setores']['labels'] ?>,
                datasets: [{
                    data: <?= $charts['setores']['valores'] ?>,
                    backgroundColor: ['#5D46D2', '#E6007E', '#00D369', '#0099FF']
                }]
            },
            options: configPadrao
        });

        const ctxMotivos = document.getElementById('chartMotivos').getContext('2d');
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

        const ctxHospedagem = document.getElementById('chartHospedagem').getContext('2d');
        new Chart(ctxHospedagem, {
            type: 'pie',
            data: {
                labels: <?= $charts['hospedagem']['labels'] ?>,
                datasets: [{
                    data: <?= $charts['hospedagem']['valores'] ?>,
                    backgroundColor: ['#E6007E', '#5D46D2', '#00D369', '#FFA800']
                }]
            },
            options: configPadrao
        });
    </script>
</body>

</html>