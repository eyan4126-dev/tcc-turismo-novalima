<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes Unificados - Turismo Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= site_url('new-logo2.png') ?>">
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

        .table-container {
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(93, 70, 210, 0.04);
            padding: 25px;
        }

        .qr-token-box {
            font-family: 'Courier New', Courier, monospace;
            background: #F8F9FA;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            color: #333;
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
                <p class="text-muted small mb-0 mt-1">Painel da Prefeitura</p>
            </div>

            <ul class="nav flex-column nav-sidebar">
                <li class="nav-item">
                    <a href="<?= site_url('admin') ?>" class="nav-link <?= url_is('admin') ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> Painel Gerencial
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('estabelecimentos') ?>"
                        class="nav-link <?= url_is('estabelecimentos') ? 'active' : '' ?>">
                        <i class="fa-solid fa-store"></i> Estabelecimentos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/qrcodes') ?>"
                        class="nav-link <?= url_is('admin/qrcodes') || url_is('qrcodes') ? 'active' : '' ?>">
                        <i class="fa-solid fa-qrcode"></i> QR Codes Gerados
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a href="<?= site_url('logout') ?>" class="nav-link text-danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair do Sistema
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--nl-purple);">Tokens e Distribuição de Mídias</h2>
                    <p class="text-muted small mb-0">Segurança de identificação por token não sequencial ativo nas URLs
                        de pesquisa.</p>
                </div>
            </div>

            <div class="table-container">
                <h5 class="fw-bold mb-4" style="color: var(--nl-purple);"><i class="fa-solid fa-qrcode me-2"></i>Mídias
                    de Coleta Ativas</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Local Vinculado</th>
                                <th>Token de Identificação Seguro (Anti-exposição de ID)</th>
                                <th>URL Segura Gerada</th>
                                <th class="text-end">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($qrcodes)): ?>
                                <?php foreach ($qrcodes as $qr): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= esc($qr['razao_social']) ?></div>
                                        </td>
                                        <td>
                                            <span class="qr-token-box"><?= esc($qr['token_qr_code']) ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php $fullUrl = site_url('pesquisa?token=' . $qr['token_qr_code']); ?>
                                                <a href="<?= $fullUrl ?>" target="_blank" class="text-decoration-none">
                                                    <span class="text-muted small text-break"><?= $fullUrl ?></span>
                                                </a>

                                                <button type="button" class="btn btn-link p-0 text-secondary btn-copy-url"
                                                    data-url="<?= $fullUrl ?>" title="Copiar URL"
                                                    onclick="copiarUrlParaTransferencia(this)">
                                                    <i class="fa-regular fa-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <?php
                                            $urlDestinoTurista = site_url('pesquisa?token=' . $qr['token_qr_code']);
                                            $apiLinkQr = "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=" . urlencode($urlDestinoTurista);
                                            ?>
                                            <button
                                                onclick="imprimirTag('<?= esc($qr['razao_social']) ?>', '<?= $apiLinkQr ?>')"
                                                class="btn btn-sm btn-dark fw-bold">
                                                <i class="fa-solid fa-print me-1"></i> Imprimir Tag
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold">Reserva Particular do Patrimônio Natural (RPPN)</div>
                                    </td>
                                    <td><span class="qr-token-box">a8f92b7c4e13d96e5fa1</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-muted small">.../pesquisa?token=a8f92b7c4e13d96e5fa1</span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-dark fw-bold" disabled>Imprimir Tag</button>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <script>
                        function copiarUrlParaTransferencia(botao) {
                            const url = botao.getAttribute('data-url');

                            if (!url) return;

                            // Utiliza a API nativa do navegador para colar na área de transferência
                            navigator.clipboard.writeText(url).then(() => {
                                // Altera o ícone para feedback visual positivo
                                const icone = botao.querySelector('i');
                                icone.className = 'fa-solid fa-check text-success';
                                botao.setAttribute('title', 'Copiado!');

                                // Retorna ao estado original após 2 segundos
                                setTimeout(() => {
                                    icone.className = 'fa-regular fa-copy';
                                    botao.setAttribute('title', 'Copiar URL');
                                }, 2000);
                            }).catch(err => {
                                console.error('Erro ao copiar a URL: ', err);
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <script>
        function imprimirTag(nomeLocal, urlQrCode) {
            const janelaImpressao = window.open('', '_blank', 'width=800,height=600');
            janelaImpressao.document.write(`
        <html>
        <head>
            <title>Imprimir QR Code - iNovaTour</title>
            <style>
                body { font-family: 'Inter', sans-serif; text-align: center; padding: 40px; color: #1A1A1A; }
                .tag-container { border: 4px solid #5D46D2; border-radius: 24px; padding: 40px; max-width: 450px; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                .logo { font-weight: 800; font-size: 28px; color: #5D46D2; margin-bottom: 10px; }
                .logo span { font-weight: 400; color: #1A1A1A; }
                .subtitle { font-size: 14px; color: #6C757D; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 1px; }
                .qr-code { max-width: 280px; margin: 20px auto; display: block; border: 1px solid #E2E8F0; padding: 10px; border-radius: 12px; }
                .local-nome { font-size: 22px; font-weight: 700; margin-top: 25px; color: #1A1A1A; }
                .instrucao { font-size: 14px; color: #5D46D2; font-weight: 600; margin-top: 15px; }
                @media print {
                    body { padding: 0; }
                    .tag-container { box-shadow: none; border-color: #5D46D2; }
                }
            </style>
        </head>
        <body>
            <div class="tag-container">
                <div class="logo">iNova<span>Tour</span></div>
                <div class="subtitle">Guia Turístico Oficial</div>
                <img class="qr-code" src="${urlQrCode}" alt="QR Code">
                <div class="local-nome">${nomeLocal}</div>
                <div class="instrucao">Abra a câmera do celular para escanear e avaliar</div>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 500);
                };
            <\/script>
        </body>
        </html>
    `);
            janelaImpressao.document.close();
        }
    </script>
</body>

</html>