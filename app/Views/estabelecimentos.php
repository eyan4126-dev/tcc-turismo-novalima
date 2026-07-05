<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estabelecimentos e Pontos - Turismo Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
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

        .table-container,
        .form-container {
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--nl-purple);">Base Unificada de Estabelecimentos</h2>
                    <p class="text-muted small">Gerenciamento de lojistas privados ativos e cadastro direto de patrimônios públicos.</p>
                </div>
            </div>

            <div class="form-container">
                <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-plus-circle me-2"></i>Cadastrar Ponto Natural, Cultural ou Evento Próprio (Prefeitura)</h5>
                <form action="<?= base_url('estabelecimentos/salvar-direto') ?>" method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Nome do Ponto/Evento (Razão Social)</label>
                        <input type="text" name="razao_social" class="form-select-sm form-control" required placeholder="Ex: Cachoeira de Santo Antônio">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Setor Oficial</label>
                        <select name="setor" class="form-select" required>
                            <option value="natural">Natural (Atrativo)</option>
                            <option value="cultural">Cultural (Patrimônio)</option>
                            <option value="hospedagem">Hospedagem</option>
                            <option value="alimentacao_comercio">Alimentação / Comércio</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Tipo</label>
                        <select name="tipo" class="form-select" id="tipoCadastro" onchange="toggleDatas(this.value)" required>
                            <option value="fixo">Fixo Permanente</option>
                            <option value="evento">Evento Sazonal</option>
                        </select>
                    </div>
                    <div class="col-md-2" id="divInicio" style="display:none;">
                        <label class="form-label small fw-bold">Data Início</label>
                        <input type="date" name="data_inicio" class="form-control">
                    </div>
                    <div class="col-md-2" id="divFim" style="display:none;">
                        <label class="form-label small fw-bold">Data Fim</label>
                        <input type="date" name="data_fim" class="form-control">
                    </div>
                    <input type="hidden" name="telefone" value="3135414334">
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa-solid fa-save me-1"></i> Gravar e Ativar</button>
                    </div>
                </form>
            </div>

            <div class="table-container">
                <h5 class="fw-bold mb-4" style="color: var(--nl-purple);">Registros Ativos no Hub</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nome do Local / Razão Social</th>
                                <th>Setor</th>
                                <th>Tipo de Operação</th>
                                <th>Vínculo / Origem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($estabelecimentos)): foreach ($estabelecimentos as $est): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= esc($est['razao_social']) ?></div><span class="text-muted small"><?= esc($est['cnpj'] ?? 'Isento (Prefeitura)') ?></span>
                                        </td>
                                        <td><span class="badge bg-light text-dark text-uppercase"><?= esc($est['setor']) ?></span></td>
                                        <td>
                                            <?php if ($est['tipo'] === 'evento'): ?>
                                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-calendar-day me-1"></i> Sazonal (<?= date('d/m', strtotime($est['data_inicio'])) ?> a <?= date('d/m', strtotime($est['data_fim'])) ?>)</span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><i class="fa-solid fa-building me-1"></i> Fixo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($est['role_usuario'] === 'admin' ? 'Prefeitura (Direto)' : 'Lojista Credenciado') ?></td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold">Matriz de Nossa Senhora do Pilar</div><span class="text-muted small">Isento (Prefeitura)</span>
                                    </td>
                                    <td><span class="badge bg-light text-dark">CULTURAL</span></td>
                                    <td><span class="badge bg-success">Fixo</span></td>
                                    <td>Prefeitura (Direto)</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleDatas(val) {
            document.getElementById('divInicio').style.display = val === 'evento' ? 'block' : 'none';
            document.getElementById('divFim').style.display = val === 'evento' ? 'block' : 'none';
        }
    </script>
</body>

</html>