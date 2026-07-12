<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Lojista - iNovaTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/x-icon" href="<?= site_url('new-logo2.png') ?>">
    <style>
        :root {
            --nl-purple: #5D46D2;
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

        .card-chart-data {
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(93, 70, 210, 0.04);
            padding: 25px;
        }

        .alert-promo-banner {
            background: linear-gradient(135deg, #F5F3FF 0%, #ECE9FC 100%);
            border-left: 4px solid var(--nl-purple);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(93, 70, 210, 0.03);
        }

        /* Foto de Preview do Perfil */
        .preview-photo-box {
            width: 130px;
            height: 130px;
            border-radius: 16px;
            background-color: #F1F5F9;
            border: 2px dashed #CBD5E1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .preview-photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-photo-box:hover {
            border-color: var(--nl-purple);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header mb-4">
                <div class="brand-logo-container">
                    <img src="<?= site_url('new-logo2.png') ?>" alt="iNovaTour Logo">
                </div>
                <span class="fw-extrabold text-nl-purple h4 tracking-tight" style="font-weight:800;">
                    iNova<span style="font-weight:400; color:var(--nl-text-dark);">Tour</span>
                </span>
                <p class="text-muted small mb-0 mt-1">Portal do Lojista</p>
            </div>

            <ul class="nav flex-column nav-sidebar">
                <li class="nav-item">
                    <a href="<?= site_url('lojista/dashboard') ?>"
                        class="nav-link <?= url_is('lojista/dashboard') || url_is('lojista') ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> Desempenho
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('lojista/qrcode') ?>"
                        class="nav-link <?= url_is('lojista/qrcode') ? 'active' : '' ?>">
                        <i class="fa-solid fa-qrcode"></i> Meu QR Code
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a href="<?= site_url('logout') ?>" class="nav-link text-danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
            <form method="GET" action="<?= site_url('lojista/dashboard') ?>" class="row g-3 mb-4 align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-1" style="color: var(--nl-purple);"><?= esc($razaoSocial) ?></h2>
                    <p class="text-muted small mb-0">Métricas estratégicas reais e comportamento de consumo dos seus
                        clientes.</p>
                </div>
                <div class="col-md-4">
                    <select name="periodo" class="form-select" onchange="this.form.submit()">
                        <option value="atual" <?= (request()->getGet('periodo') ?? 'atual') === 'atual' ? 'selected' : '' ?>>Mês Corrente (Padrão)</option>
                        <option value="historico" <?= request()->getGet('periodo') === 'historico' ? 'selected' : '' ?>>
                            Todo o Histórico</option>
                    </select>
                </div>
            </form>

            <!-- NOVO BANNER ESTRATÉGICO DE COMPLIANCE NO DASHBOARD -->
            <div
                class="alert-promo-banner d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center border"
                        style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="fa-solid fa-ticket text-nl-purple fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Status da Rede de Vantagens:
                            <span class="<?= $aceitaDesconto ? 'text-success' : 'text-secondary' ?>">
                                <?= $aceitaDesconto ? 'Ativo (10% de Desconto)' : 'Inativo' ?>
                            </span>
                        </h6>
                        <p class="text-muted mb-0 small" style="line-height: 1.3;">
                            <?php if ($aceitaDesconto): ?>
                                Seu estabelecimento está qualificado para receber o <strong>Selo de Destaque</strong> e
                                possui o PIN <strong><?= esc($pinValidacao) ?></strong> ativo para validações no balcão.
                            <?php else: ?>
                                Ative a sua adesão à rede para garantir destaque algorítmico privilegiado no topo do Guia
                                Turístico Municipal.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div>
                    <!-- Link transformado em Botão Acionador do Painel Collapse de Configuração de Foto -->
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold text-nowrap"
                        style="color: var(--nl-purple); border-color: var(--nl-purple);" data-bs-toggle="collapse"
                        data-bs-target="#collapseConfiguracoes">
                        <i class="fa-solid fa-gears me-1"></i> Configurações do Perfil
                    </button>
                </div>
            </div>

            <?php if (session()->getFlashdata('sucesso')): ?>
                <div class="alert alert-success fw-bold mb-4 border-0 shadow-sm"><i
                        class="fa-solid fa-circle-check me-2"></i><?= session()->getFlashdata('sucesso') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('erro')): ?>
                <div class="alert alert-danger fw-bold mb-4 border-0 shadow-sm"><i
                        class="fa-solid fa-circle-exclamation me-2"></i><?= session()->getFlashdata('erro') ?></div>
            <?php endif; ?>

            <!-- COLLAPSE CONTAINER: ZONA DE CONFIGURAÇÃO DO PERFIL E PREVIEW DA FOTO -->
            <div class="collapse mb-4" id="collapseConfiguracoes">
                <div class="card card-body border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold mb-3 text-nl-purple"><i class="fa-solid fa-store me-2"></i>Configurações do Seu
                        Perfil Público</h5>
                    <p class="text-muted small mb-4">Adicione uma foto real do seu comércio, pousada ou atrativo para
                        exibi-la com exclusividade no Guia Turístico de Nova Lima.</p>

                    <!-- Formulário configurado com multipart/form-data obrigatório para o upload funcionar -->
                    <form action="<?= site_url('lojista/atualizar-desconto') ?>" method="POST"
                        enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row g-4 align-items-center">
                            <div class="col-auto">
                                <div class="preview-photo-box" id="photoPreviewContainer">
                                    <?php if (!empty($fotoAtual)): ?>
                                        <img src="<?= site_url('uploads/estabelecimentos/' . $fotoAtual) ?>"
                                            alt="Foto do Estabelecimento" id="imgElementPreview">
                                    <?php else: ?>
                                        <div class="text-center p-2 text-muted" id="placeholderIconPreview">
                                            <i class="fa-solid fa-camera fa-2x mb-1 text-slate-300"></i>
                                            <small class="d-block" style="font-size:0.65rem;">Sem Foto</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Escolha a Foto
                                        Oficial:</label>
                                    <input type="file" name="foto_estabelecimento" id="fotoInput" class="form-control"
                                        accept="image/*" onchange="previewImagemFisica(this)">
                                    <div class="form-text text-muted small" style="font-size:0.7rem;">Suporta JPG, PNG
                                        ou JPEG. Resolução recomendada: 800x600 pixels (Max: 4MB).</div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch pt-2">
                                            <input class="form-switch form-check-input" type="checkbox" role="switch"
                                                id="switchDesconto" name="aceita_desconto" value="1" <?= $aceitaDesconto ? 'checked' : '' ?>>
                                            <label class="form-check-label small fw-bold text-secondary"
                                                for="switchDesconto">Participar da Rede de Vantagens (10% OFF)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-nl-primary w-100 fw-bold rounded-pill">
                                            <i class="fa-solid fa-floppy-disk me-1"></i> Salvar Alterações
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-chart-data mb-5">
                <h6 class="fw-bold mb-2" style="color: var(--nl-purple);">
                    <i class="fa-solid fa-hotel me-2"></i>Lançamento de Desempenho e Fluxo Operacional
                </h6>
                <p class="text-muted small mb-4">
                    Informe o fechamento estatístico do mês para fins de consolidação dos indicadores turísticos do
                    município.
                </p>

                <form action="<?= site_url('api/painel/ocupacao') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-secondary">Mês de Referência:</label>
                            <input type="month" name="data_referencia" class="form-control" required
                                value="<?= date('Y-m') ?>">
                        </div>

                        <?php if ($setorLojista === 'hospedagem'): ?>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-secondary">Total de Hóspedes:</label>
                                <input type="number" name="volume_clientes" class="form-control" min="0" required
                                    placeholder="Ex: 350">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-secondary">Quartos Ocupados (Noites):</label>
                                <input type="number" name="quartos_ocupados" class="form-control" min="0" required
                                    placeholder="Ex: 120">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-secondary">Capacidade de Quartos:</label>
                                <input type="number" name="capacidade_maxima_quartos" class="form-control" min="0" required
                                    placeholder="Ex: 200">
                            </div>

                        <?php elseif ($setorLojista === 'alimentacao_comercio'): ?>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-secondary">Total de Clientes Atendidos:</label>
                                <input type="number" name="volume_clientes" class="form-control" min="0" required
                                    placeholder="Ex: 850">
                            </div>
                            <input type="hidden" name="quartos_ocupados" value="0">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-secondary">Capacidade Máxima de
                                    Lugares/Vendas:</label>
                                <input type="number" name="capacidade_maxima_quartos" class="form-control" min="0" required
                                    placeholder="Ex: 1500">
                            </div>

                        <?php else: // Recursos Naturais, Culturais ou Outros ?>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-secondary">Volume Total de Visitantes:</label>
                                <input type="number" name="volume_clientes" class="form-control" min="0" required
                                    placeholder="Ex: 400">
                            </div>
                            <input type="hidden" name="quartos_ocupados" value="0">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-secondary">Capacidade Máxima de
                                    Carga/Dia:</label>
                                <input type="number" name="capacidade_maxima_quartos" class="form-control" min="0" required
                                    placeholder="Ex: 500">
                            </div>
                        <?php endif; ?>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                <i class="fa-solid fa-paper-plane me-2"></i>Enviar Dados
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 g-3 mb-5">
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Seu Faturamento</span>
                            <h3 class="fw-bold mb-0">R$ <?= number_format($kpis['faturamento_estimado'], 2, ',', '.') ?>
                            </h3>
                        </div>
                        <div class="metric-icon" style="background: #E6F9ED; color: var(--nl-green-neon);"><i
                                class="fa-solid fa-money-bill-wave"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Clientes Capturados</span>
                            <h3 class="fw-bold mb-0"><?= number_format($kpis['volume_clientes'], 0, '', '.') ?></h3>
                        </div>
                        <div class="metric-icon" style="background: var(--nl-purple-light); color: var(--nl-purple);"><i
                                class="fa-solid fa-users"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Ticket Médio</span>
                            <h3 class="fw-bold mb-0">R$ <?= number_format($kpis['ticket_medio'], 2, ',', '.') ?></h3>
                        </div>
                        <div class="metric-icon" style="background: #EAF4FF; color: #0099FF;"><i
                                class="fa-solid fa-calculator"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Satisfação Clientes</span>
                            <h3 class="fw-bold mb-0"><?= $kpis['satisfacao_exclusiva'] ?> <i
                                    class="fa-solid fa-star text-warning fs-6"></i></h3>
                        </div>
                        <div class="metric-icon" style="background: #FFF9E6; color: #FFA800;"><i
                                class="fa-solid fa-star"></i></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-metric d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-semibold mb-1">Seu NPS</span>
                            <h3
                                class="fw-bold mb-0 <?= $kpis['nps_proprio'] >= 50 ? 'text-success' : ($kpis['nps_proprio'] >= 0 ? 'text-warning' : 'text-danger') ?>">
                                <?= $kpis['nps_proprio'] ?>
                            </h3>
                        </div>
                        <div class="metric-icon" style="background: #FFF5F5; color: var(--nl-magenta);"><i
                                class="fa-solid fa-heart"></i></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Motivo da Visita dos Seus Clientes
                        </h6>
                        <div class="chart-container">
                            <canvas id="chartMotivosLojista"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Principais Cidades de Origem Emissoras
                        </h6>
                        <div class="chart-container">
                            <canvas id="chartOrigemLojista"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Perfil de Estadia (Dorme na cidade?)
                        </h6>
                        <div class="chart-container">
                            <canvas id="chartPermanenciaLojista"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-chart">
                        <h6 class="fw-bold mb-3" style="color: var(--nl-purple);">Onde o Seu Cliente se Hospeda</h6>
                        <div class="chart-container">
                            <canvas id="chartHospedagemLojista"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função de Preview instantâneo do upload da Imagem no Navegador
        function previewImagemFisica(input) {
            const container = document.getElementById('photoPreviewContainer');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    container.innerHTML = `<img src="${e.target.result}" alt="Preview Foto">`;
                };
                reader.readAsDataURL(input.files[0]);
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

        const ctxMotivos = document.getElementById('chartMotivosLojista').getContext('2d');
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

        const ctxOrigem = document.getElementById('chartOrigemLojista').getContext('2d');
        new Chart(ctxOrigem, {
            type: 'bar',
            data: {
                labels: <?= $charts['cidades']['labels'] ?>,
                datasets: [{
                    label: 'Clientes',
                    data: <?= $charts['cidades']['valores'] ?>,
                    backgroundColor: '#E6007E',
                    borderRadius: 4
                }]
            },
            options: {
                ...configPadrao,
                plugins: { legend: { display: false } }
            }
        });

        const ctxPermanencia = document.getElementById('chartPermanenciaLojista').getContext('2d');
        new Chart(ctxPermanencia, {
            type: 'pie',
            data: {
                labels: <?= $charts['permanencia']['labels'] ?>,
                datasets: [{
                    data: <?= $charts['permanencia']['valores'] ?>,
                    backgroundColor: ['#00D369', '#FFA800']
                }]
            },
            options: configPadrao
        });

        const ctxHospedagem = document.getElementById('chartHospedagemLojista').getContext('2d');
        new Chart(ctxHospedagem, {
            type: 'polarArea',
            data: {
                labels: <?= $charts['hospedagem']['labels'] ?>,
                datasets: [{
                    data: <?= $charts['hospedagem']['valores'] ?>,
                    backgroundColor: ['#5D46D2', '#E6007E', '#00D369', '#0099FF', '#6C757D']
                }]
            },
            options: configPadrao
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>