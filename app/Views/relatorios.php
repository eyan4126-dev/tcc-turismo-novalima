<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestação de Contas Fiscais - Turismo Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --nl-purple: #5D46D2;
            --nl-purple-light: #ECE9FC;
            --nl-bg-light: #F4F6F9;
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

        /* Componentes de Estilo Específicos para a Área Fiscal */
        .fiscal-container {
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(93, 70, 210, 0.04);
            padding: 25px;
        }

        .card-fiscal {
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            background: #FFFFFF;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-fiscal:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(93, 70, 210, 0.06);
            border-color: var(--nl-purple-light);
        }

        .icon-box-fiscal {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            background-color: var(--nl-purple-light);
            color: var(--nl-purple);
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
                <p class="text-muted small mb-0 mt-1">Painel da Prefeitura</p>
            </div>

            <ul class="nav flex-column nav-sidebar">
                <li class="nav-item">
                    <a href="<?= base_url('admin') ?>"
                        class="nav-link <?= url_is('admin') || url_is('admin/dashboard') ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('estabelecimentos') ?>"
                        class="nav-link <?= url_is('estabelecimentos') ? 'active' : '' ?>">
                        <i class="fa-solid fa-store"></i> Estabelecimentos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/qrcodes') ?>"
                        class="nav-link <?= url_is('admin/qrcodes') || url_is('qrcodes') ? 'active' : '' ?>">
                        <i class="fa-solid fa-qrcode"></i> QR Codes Gerados
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/prestacao-contas') ?>"
                        class="nav-link <?= url_is('admin/prestacao-contas') ? 'active' : '' ?>">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Prestação de Contas
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
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--nl-purple);">Compilação de Relatórios Estaduais</h2>
                    <p class="text-muted small mb-0">Exportador de dados unificados em total conformidade regulatória
                        fiscal.</p>
                </div>
            </div>

            <div class="fiscal-container mb-4">
                <h5 class="fw-bold mb-3 mb-4" style="color: var(--nl-purple);">
                    <i class="fa-solid fa-calendar-days me-2"></i>Período de Competência
                </h5>
                <form id="formFiltroFiscal" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Data de Início</label>
                        <input type="date" id="data_inicio" class="form-control rounded-3" value="<?= date('Y-m-01') ?>"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Data de Término</label>
                        <input type="date" id="data_fim" class="form-control rounded-3" value="<?= date('Y-m-t') ?>"
                            required>
                    </div>
                    <div class="col-md-4 text-md-end text-start">
                        <span class="text-muted small d-inline-block"><i class="fa-solid fa-circle-info me-1"></i>Válido
                            para ambos os leiautes</span>
                    </div>
                </form>
            </div>

            <div class="fiscal-container">
                <h5 class="fw-bold mb-4" style="color: var(--nl-purple);"><i
                        class="fa-solid fa-file-export me-2"></i>Formatos Disponíveis para Homologação</h5>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card card-fiscal p-4 h-100 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-box-fiscal">
                                    <i class="fa-solid fa-landmark"></i>
                                </div>
                                <span class="badge rounded-pill px-3 py-2 fw-bold"
                                    style="background-color: #E6F4EA; color: #137333;">Critério ICMS</span>
                            </div>
                            <h4 class="fw-bold text-dark fs-5">ICMS Turismo</h4>
                            <p class="text-muted small flex-grow-1">
                                Consolida os indicadores de fluxo de demanda regionalizada, ticket real com apuração de
                                centavos do gasto estimado por pessoa e dados de amostragem turística municipal.
                            </p>
                            <button type="button" onclick="baixarRelatorioFiscal('icms-turismo')"
                                class="btn btn-dark w-100 fw-bold rounded-3 mt-3">
                                <i class="fa-solid fa-download me-2"></i> Exportar Base ICMS
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-fiscal p-4 h-100 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-box-fiscal" style="background-color: #E8F0FE; color: #1A73E8;">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </div>
                                <span class="badge rounded-pill px-3 py-2 fw-bold"
                                    style="background-color: #E8F0FE; color: #1A73E8;">Matriz Secult</span>
                            </div>
                            <h4 class="fw-bold text-dark fs-5">Mapeamento Sismapa</h4>
                            <p class="text-muted small flex-grow-1">
                                Matriz geradora para o Inventário de Fluxo do Turismo Estadual. Agrupa as correlações de
                                tempo de pernoite/estadia e motivações do fluxo de visitantes ativos no município.
                            </p>
                            <button type="button" onclick="baixarRelatorioFiscal('sismapa')"
                                class="btn btn-outline-dark w-100 fw-bold rounded-3 mt-3" style="border-color: #DDD;">
                                <i class="fa-solid fa-file-csv me-2"></i> Exportar Dados Sismapa
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function baixarRelatorioFiscal(tipo) {
            const dataInicio = document.getElementById('data_inicio').value;
            const dataFim = document.getElementById('data_fim').value;

            if (!dataInicio || !dataFim) {
                alert("Por favor, selecione ambas as datas para o fechamento da competência.");
                return;
            }

            const urlFinal = `<?= base_url('api/admin/exportar/') ?>${tipo}?data_inicio=${dataInicio}&data_fim=${dataFim}`;

            const btn = event.currentTarget;
            const textoOriginal = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Compilando...`;

            // Executa o download nativo via browser da chamada da API
            window.location.href = urlFinal;

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = textoOriginal;
            }, 2500);
        }
    </script>
</body>

</html>