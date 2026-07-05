<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu QR Code - iNovaTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --nl-purple: #5D46D2;
            --nl-purple-light: #ECE9FC;
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

        .card-qrcode-box {
            background: #FFFFFF;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(93, 70, 210, 0.04);
            padding: 35px;
        }

        .qr-display-zone {
            border: 2px dashed #E2E8F0;
            border-radius: 12px;
            padding: 20px;
            background: #FAFAFA;
            display: inline-block;
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
                        class="nav-link <?= url_is('lojista/dashboard') ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> Desempenho
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('lojista/qrcode') ?>"
                        class="nav-link <?= url_is('lojista/qrcode') || url_is('lojista') ? 'active' : '' ?>">
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
                <h2 class="fw-bold mb-1" style="color: var(--nl-purple);">Seu QR Code de Identificação</h2>
                <p class="text-muted small mb-0">Disponibilize este código em um local visível para que os visitantes
                    possam escanear.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="card-qrcode-box">
                        <h5 class="fw-bold mb-4" style="color: var(--nl-purple);">Display de Balcão</h5>
                        <div class="qr-display-zone mb-4">
                            <img src="<?= base_url($estabelecimento['qr_code_url']) ?>" alt="QR Code" class="img-fluid"
                                style="max-width: 200px;">
                        </div>
                        <a href="<?= base_url($estabelecimento['qr_code_url']) ?>" download="qrcode-inovatour.png"
                            class="btn btn-primary btn-sm w-100 fw-bold py-2"
                            style="background-color: var(--nl-purple); border: none;">
                            <i class="fa-solid fa-download me-2"></i> Baixar Imagem (PNG)
                        </a>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card-qrcode-box h-100">
                        <h5 class="fw-bold mb-4" style="color: var(--nl-purple);"><i
                                class="fa-solid fa-store me-2"></i>Dados Cadastrais Homologados</h5>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">Razão Social / Nome do
                                Estabelecimento</label>
                            <input type="text" class="form-control bg-light text-dark fw-semibold"
                                value="<?= esc($estabelecimento['razao_social']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">CNPJ</label>
                            <input type="text" class="form-control bg-light text-dark"
                                value="<?= esc($estabelecimento['cnpj']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">Setor de Atuação</label>
                            <span class="badge bg-secondary p-2 d-inline-block">
                                <?= esc($estabelecimento['setor']) ?>
                            </span>
                        </div>

                        <div class="mt-4 p-3 rounded-3"
                            style="background-color: #FFF5F5; border-left: 4px solid var(--nl-purple);">
                            <p class="small text-muted mb-0"><i
                                    class="fa-solid fa-circle-info me-2 text-danger"></i>Qualquer alteração cadastral
                                nestes dados estruturais deve ser solicitada diretamente à Secretaria de Turismo da
                                Prefeitura.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>