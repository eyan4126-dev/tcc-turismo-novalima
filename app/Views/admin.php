<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Gerencial - Turismo Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/x-icon" href="<?= base_url('new-logo2.png') ?>">
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
            position: fixed;
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
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
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

        .brand-logo-container img {
            width: 35%;
            height: auto;
            object-fit: contain;
            margin-bottom: 10px;
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
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(93, 70, 210, 0.04);
            padding: 16px 20px;
            height: 100%;
        }

        .card-metric h3 {
            font-size: 1.4rem !important;
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
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .table-container,
        .report-container {
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(93, 70, 210, 0.04);
            padding: 25px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header mb-4">
                <div class="brand-logo-container">
                    <img src="<?= base_url('new-logo2.png') ?>" alt="iNovaTour Logo">
                </div>
                <span class="fw-extrabold text-nl-purple h4 tracking-tight" style="font-weight:800;">
                    iNova<span style="font-weight:400; color:var(--nl-text-dark);">Tour</span>
                </span>
                <p class="text-muted small mb-0 mt-1">Painel da Prefeitura</p>
            </div>

            <ul class="nav flex-column nav-sidebar">
                <li class="nav-item">
                    <a href="<?= base_url('admin') ?>"
                        class="nav-link <?= url_is('admin') || url_is('admin/dashboard') ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> Painel de Controle
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('estabelecimentos') ?>"
                        class="nav-link <?= url_is('estabelecimentos') ? 'active' : '' ?>">
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
                    <h2 class="fw-bold mb-1" style="color: var(--nl-purple);">Painel Gerencial</h2>
                    <p class="text-muted small mb-0">Dados consolidados de estabelecimentos ativos.</p>
                </div>
                <div class="col-md-3">
                    <select name="periodo" class="form-select" onchange="this.form.submit()">
                        <option value="atual" <?= (request()->getGet('periodo') ?? 'atual') === 'atual' ? 'selected' : '' ?>>Mês Corrente (Padrão)</option>
                        <option value="historico" <?= request()->getGet('periodo') === 'historico' ? 'selected' : '' ?>>
                            Todo o Histórico</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <select name="id_evento" class="form-select" onchange="this.form.submit()">
                        <option value="">Filtrar por Evento (Fixo / Todos Correntes)</option>
                        <?php foreach ($eventosFiltro as $ev): ?>
                            <option value="<?= $ev['id_estabelecimento'] ?>"
                                <?= request()->getGet('id_evento') == $ev['id_estabelecimento'] ? 'selected' : '' ?>>
                                <?= esc($ev['razao_social']) ?> (Sazonal)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <div class="report-container">
                <h5 class="fw-bold mb-2" style="color: var(--nl-purple);"><i
                        class="fa-solid fa-file-export me-2"></i>Módulo Fiscal: Exportação de Relatórios Estaduais</h5>
                <p class="text-muted small mb-4">IInsira o intervalo cronológico para exportar as matrizes de dados
                    oficiais exigidas para captação de recursos e inventário turístico municipal.</p>

                <form method="POST" action="" id="formExportadoresFiscais">
                    <?= csrf_field() ?>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-secondary">Data Inicial:</label>
                            <input type="date" name="data_inicio" id="data_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-secondary">Data Final:</label>
                            <input type="date" name="data_fim" id="data_fim" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit"
                                onclick="definirMetodoExportacao('<?= base_url('admin/exportar-icms') ?>')"
                                class="btn btn-success w-100 fw-bold">
                                <i class="fa-solid fa-table me-2"></i>Dossiê de Demanda
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="submit"
                                onclick="definirMetodoExportacao('<?= base_url('admin/exportar-sismapa') ?>')"
                                class="btn btn-dark w-100 fw-bold">
                                <i class="fa-solid fa-map-location-dot me-2"></i>Inventário de Fluxo
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row row-cols-1 row-cols-md-3 row-cols-xl-5 g-3 mb-4">
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Impacto Econômico</span>
                            <h3 class="fw-bold mb-0">R$ <?= number_format($kpis['impacto_economico'], 2, ',', '.') ?>
                            </h3>
                        </div>
                        <div class="metric-icon" style="background: #E6F9ED; color: var(--nl-green-neon);"><i
                                class="fa-solid fa-money-bill-wave"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Volume Turístico</span>
                            <h3 class="fw-bold mb-0"><?= number_format($kpis['volume_turistico'], 0, '', '.') ?></h3>
                        </div>
                        <div class="metric-icon" style="background: var(--nl-purple-light); color: var(--nl-purple);"><i
                                class="fa-solid fa-users"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Ocupação Hoteleira</span>
                            <h3 class="fw-bold mb-0"><?= $kpis['ocupacao_hoteleira'] ?>%</h3>
                        </div>
                        <div class="metric-icon" style="background: #FFF5F5; color: var(--nl-magenta);"><i
                                class="fa-solid fa-bed"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Média de Satisfação</span>
                            <h3 class="fw-bold mb-0"><?= $kpis['satisfacao_media'] ?> <i
                                    class="fa-solid fa-star text-warning fs-6"></i></h3>
                        </div>
                        <div class="metric-icon" style="background: #FFF9E6; color: #FFA800;"><i
                                class="fa-solid fa-star"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">NPS Geral</span>
                            <h3
                                class="fw-bold mb-0 <?= $kpis['nps'] >= 50 ? 'text-success' : ($kpis['nps'] >= 0 ? 'text-warning' : 'text-danger') ?>">
                                <?= $kpis['nps'] ?>
                            </h3>
                        </div>
                        <div class="metric-icon" style="background: #EAF4FF; color: #0099FF;"><i
                                class="fa-solid fa-heart"></i></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Ranking de Cidades de Origem (Top 5)
                        </h6>
                        <div class="chart-container">
                            <canvas id="chartCidades"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Participação por Setor Comercial</h6>
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
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Perfil de Hospedagem (Visitantes que
                            Pernoitam)</h6>
                        <div class="chart-container">
                            <canvas id="chartHospedagem"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <h5 class="fw-bold mb-4" style="color: var(--nl-purple);"><i
                        class="fa-solid fa-clock-rotate-left me-2"></i>Solicitações de Cadastro Pendentes (Onboarding
                    Lojistas)</h5>
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
                            <?php if (!empty($solicitacoes)):
                                foreach ($solicitacoes as $sol): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= esc($sol['nome_responsavel'] ?? 'Não Informado') ?></div>
                                            <span class="text-muted small"><?= esc($sol['email'] ?? '') ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= esc($sol['razao_social']) ?></div>
                                            <span class="text-muted small"><?= esc($sol['cnpj'] ?? 'Sem CNPJ') ?></span>
                                        </td>
                                        <td><span class="badge bg-secondary"><?= esc($sol['setor']) ?></span></td>
                                        <td class="text-end">
                                            <form action="<?= base_url('admin/aprovarLojista/' . $sol['id_usuario']) ?>"
                                                method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-sm btn-success fw-bold me-1"><i
                                                        class="fa-solid fa-check me-1"></i> Ativar e Gerar QR</button>
                                            </form>
                                            <form action="<?= base_url('admin/recusarLojista/' . $sol['id_usuario']) ?>"
                                                method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-sm btn-outline-danger fw-bold"><i
                                                        class="fa-solid fa-xmark"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="4" class="text-muted text-center py-4">Nenhuma solicitação de lojista
                                        pendente no momento.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function definirMetodoExportacao(urlAlvo) {
            const form = document.getElementById('formExportadoresFiscais');
            const dataInicio = document.getElementById('data_inicio').value;
            const dataFim = document.getElementById('data_fim').value;
            if (dataInicio && dataFim) {
                form.action = urlAlvo;
            }
        }

        const configPadrao = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { family: 'Inter' } }
                }
            }
        };

        const ctxCidades = document.getElementById('chartCidades').getContext('2d');
        new Chart(ctxCidades, {
            type: 'bar',
            data: {
                labels: <?= $charts['cidades']['labels'] ?>,
                datasets: [{
                    label: 'Amostras',
                    data: <?= $charts['cidades']['valores'] ?>,
                    backgroundColor: '#5D46D2',
                    borderRadius: 4
                }]
            },
            options: {
                ...configPadrao,
                indexAxis: 'y',
                plugins: { legend: { display: false } }
            }
        });

        const ctxSetores = document.getElementById('chartSetores').getContext('2d');
        new Chart(ctxSetores, {
            type: 'polarArea',
            data: {
                labels: <?= $charts['setores']['labels'] ?>,
                datasets: [{
                    data: <?= $charts['setores']['valores'] ?>,
                    backgroundColor: ['#5D46D2', '#E6007E', '#00D369', '#0099FF', '#FFA800']
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
                    backgroundColor: ['#FF5500', '#5D46D2', '#0099FF', '#6C757D', '#E6007E']
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
                    backgroundColor: ['#E6007E', '#5D46D2', '#00D369', '#FFA800', '#6C757D']
                }]
            },
            options: configPadrao
        });
    </script>
</body>

</html>