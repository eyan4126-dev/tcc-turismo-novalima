<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu QR Code - iNovaTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= site_url('new-logo2.png') ?>">
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
    /* Destaque para o bloco da Rede de Vantagens */
        .benefits-card-box {
            border: 1px dashed var(--nl-purple);
            background-color: #FBFBFF;
            border-radius: 12px;
            padding: 20px;
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
                        class="nav-link <?= url_is('lojista/dashboard') ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> Desempenho
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('lojista/qrcode') ?>"
                        class="nav-link <?= url_is('lojista/qrcode') || url_is('lojista') ? 'active' : '' ?>">
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
            <div class="mb-5">
                <h2 class="fw-bold mb-1" style="color: var(--nl-purple);">Seu QR Code de Identificação</h2>
                <p class="text-muted small mb-0">Disponibilize este código em um local visível para que os visitantes possam escanear.</p>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success fw-bold mb-4 border-0 shadow-sm">
                        <i class="fa-solid fa-circle-check me-2"></i><?= session()->getFlashdata('success') ?>
                    </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="card-qrcode-box">
                        <h5 class="fw-bold mb-4" style="color: var(--nl-purple);">Display de Balcão</h5>

                        <?php
                        // Monta a URL de destino da pesquisa que o turista acessará ao ler o QR Code com a origem do canal
                        $urlPesquisa = site_url("pesquisa?token=" . $estabelecimento['token_qr_code'] . "&origem=qrcode");
                        // Passa a URL encodada para a API pública gerar o gráfico do QR Code dinamicamente
                        $apiQrServer = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($urlPesquisa);
                        ?>

                        <div class="qr-display-zone mb-4">
                            <img src="<?= $apiQrServer ?>" alt="QR Code Dinâmico" class="img-fluid" style="max-width: 200px;">
                        </div>

                        <!-- Botão Unificado para Impressão de Alta Resolução -->
                        <button
                            onclick="imprimirPlacaOficial('<?= esc($estabelecimento['razao_social']) ?>', '<?= $apiQrServer ?>', '<?= $estabelecimento['aceita_desconto'] ?>', '<?= esc($estabelecimento['pin_validacao'] ?? '') ?>')"
                            class="btn btn-primary btn-sm w-100 fw-bold py-2"
                            style="background-color: var(--nl-purple); border: none;">
                            <i class="fa-solid fa-print me-2"></i> Gerar Placa de Impressão (A5)
                        </button>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card-qrcode-box h-100">
                        <h5 class="fw-bold mb-4" style="color: var(--nl-purple);"><i class="fa-solid fa-store me-2"></i>Gerenciamento de Rede & Cadastro</h5>

                        <!-- Configurações de Participação na Rede de Vantagens -->
                        <div class="benefits-card-box mb-4">
                            <form action="<?= site_url('lojista/atualizar-desconto') ?>" method="POST" id="formRedeDesconto">
                                <?= csrf_field() ?>
                                <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between">
                                    <div class="pe-3">
                                        <label class="form-check-label fw-bold text-dark mb-1" for="switchDesconto" style="cursor: pointer;">
                                            <i class="fa-solid fa-ticket text-nl-purple me-1"></i> Participar do Programa DesconTour
                                        </label>
                                        <p class="text-muted mb-0 small" style="font-size: 0.75rem; line-height: 1.3;">
                                            Ofereça incentivo de consumo aos turistas e apareça no topo das buscas do Guia com o Selo Oficial de Nova Lima.
                                        </p>
                                    </div>
                                    <input class="form-check-input ms-0" type="checkbox" role="switch" id="switchDesconto" name="aceita_desconto" value="1" <?= $estabelecimento['aceita_desconto'] == 1 ? 'checked' : '' ?> onchange="document.getElementById('formRedeDesconto').submit();" style="width: 2.8em; height: 1.4em; cursor: pointer;">
                                </div>
                            </form>

                            <?php if ($estabelecimento['aceita_desconto'] == 1): ?>
                                    <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between bg-white p-2 rounded shadow-sm">
                                        <div>
                                            <small class="text-muted d-block fw-bold" style="font-size: 0.7rem;">SEU PIN DE VALIDAÇÃO DE CAIXA:</small>
                                            <span class="text-success font-monospace fw-bold h4 mb-0 tracking-widest" style="letter-spacing: 3px;">
                                                <?= esc($estabelecimento['pin_validacao'] ?? 'Pendente') ?>
                                            </span>
                                        </div>
                                        <span class="badge bg-success py-2 px-3 rounded-pill small"><i class="fa-solid fa-circle-check me-1"></i> Rede Ativa</span>
                                    </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">Razão Social / Nome do Estabelecimento</label>
                            <input type="text" class="form-control bg-light text-dark fw-semibold" value="<?= esc($estabelecimento['razao_social']) ?>" readonly>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold mb-1">CNPJ</label>
                                <input type="text" class="form-control bg-light text-dark" value="<?= esc($estabelecimento['cnpj']) ?>" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold mb-1">Setor de Atuação</label>
                                <div>
                                    <span class="badge bg-secondary p-2 d-inline-block text-capitalize">
                                        <?= str_replace('_', ' ', esc($estabelecimento['setor'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 rounded-3" style="background-color: #FFF5F5; border-left: 4px solid var(--nl-purple);">
                            <p class="small text-muted mb-0"><i class="fa-solid fa-circle-info me-2 text-danger"></i>Qualquer alteração cadastral nestes dados estruturais deve ser solicitada diretamente à Secretaria de Turismo da Prefeitura.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function imprimirPlacaOficial(nomeLocal, urlQrCode, aceitaDesconto, pinValidacao) {
            const janelaImpressao = window.open('', '_blank', 'width=800,height=900');
            
            // Constantes do Layout do iNovaTour para a Placa
            const isAtivo = aceitaDesconto == "1";
            
            const promoBoxHtml = isAtivo 
                ? `<div class="promo-box">
                    <span class="promo-title">GANHE 10% DE DESCONTO!</span>
                    <span class="promo-text">Escaneie o QR Code, responda à nossa pesquisa rápida sobre turismo e ative o seu cupom no nosso caixa.</span>
                   </div>`
                : `<div class="promo-box bg-simple">
                    <span class="promo-title" style="color:#4A5568;">SUA OPINIÃO IMPORTA!</span>
                    <span class="promo-text" style="color:#718096;">Escaneie o QR Code abaixo e nos ajude a mapear e melhorar a experiência turística de Nova Lima.</span>
                   </div>`;

            const stepTresHtml = isAtivo
                ? `<li><span class="step-num">3</span> <div><strong>Resgate:</strong> Digite o PIN do caixa no seu celular e receba os 10%.</div></li>`
                : '';

            const footerHtml = (isAtivo && pinValidacao !== "")
                ? `<div class="placa-footer">
                    <div class="footer-label">Para uso do atendente no fechamento do caixa</div>
                    <div class="pin-badge">
                        <span class="pin-title">PIN DO CAIXA:</span>
                        <span class="pin-number">${pinValidacao}</span>
                    </div>
                   </div>`
                : `<div class="placa-footer bg-light-footer">
                    <div class="footer-label" style="color: #94A3B8; font-weight: 700;">iNovaTour · SECRETARIA DE TURISMO</div>
                    <div class="footer-sublabel" style="font-size: 10px; color: #64748B;">PREFEITURA MUNICIPAL DE NOVA LIMA · MINAS GERAIS</div>
                   </div>`;

            janelaImpressao.document.write(`
                <html>
                <head>
                    <title>Imprimir Placa iNovaTour - ${nomeLocal}</title>
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
                        body { 
                            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
                            background-color: #F1F5F9; 
                            margin: 0; 
                            padding: 20px; 
                            display: flex;
                            justify-content: center;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        .placa-container {
                            width: 148mm;
                            height: 210mm;
                            background-color: #ffffff;
                            border-radius: 24px;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                            overflow: hidden;
                            display: flex;
                            flex-direction: column;
                            justify-content: space-between;
                            border: 1px solid #E2E8F0;
                        }
                        .placa-header {
                            background: linear-gradient(135deg, #5D46D2 0%, #E6007E 100%);
                            color: #ffffff;
                            padding: 24px 16px;
                            text-align: center;
                            border-bottom: 6px solid #00D369;
                            position: relative;
                        }
                        .header-logo {
                            font-weight: 900;
                            font-size: 28px;
                            letter-spacing: -1px;
                            text-transform: uppercase;
                            margin: 0;
                        }
                        .header-subtitle {
                            font-size: 10px;
                            font-weight: 700;
                            letter-spacing: 2px;
                            opacity: 0.9;
                            margin-top: 2px;
                        }
                        .placa-body {
                            padding: 20px 24px;
                            text-align: center;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            flex-grow: 1;
                        }
                        .local-nome {
                            font-size: 20px;
                            font-weight: 900;
                            color: #1A1A1A;
                            margin: 0 0 4px 0;
                        }
                        .local-badge {
                            background-color: #64748B;
                            color: #ffffff;
                            font-size: 10px;
                            font-weight: 700;
                            padding: 4px 12px;
                            border-radius: 50px;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            margin-bottom: 12px;
                        }
                        .promo-box {
                            background-color: #F5F3FF;
                            border: 2px dashed #5D46D2;
                            border-radius: 12px;
                            padding: 10px 16px;
                            margin-bottom: 16px;
                            max-width: 90%;
                        }
                        .promo-box.bg-simple {
                            background-color: #F8FAFC;
                            border-color: #CBD5E1;
                        }
                        .promo-title {
                            display: block;
                            font-size: 15px;
                            font-weight: 900;
                            color: #5D46D2;
                            margin-bottom: 2px;
                        }
                        .promo-text {
                            display: block;
                            font-size: 11px;
                            line-height: 1.4;
                            color: #4A5568;
                        }
                        .qr-frame {
                            border: 3px solid #5D46D2;
                            border-radius: 16px;
                            padding: 8px;
                            background-color: #ffffff;
                            box-shadow: 0 6px 15px rgba(93, 70, 210, 0.08);
                            display: inline-block;
                            margin-bottom: 16px;
                        }
                        .qr-frame img {
                            width: 140px;
                            height: 140px;
                            display: block;
                        }
                        .instrucoes-lista {
                            text-align: left;
                            max-width: 85%;
                            margin: 0 auto;
                            font-size: 11px;
                            color: #4A5568;
                            list-style: none;
                            padding: 0;
                        }
                        .instrucoes-lista li {
                            margin-bottom: 6px;
                            display: flex;
                            align-items: center;
                        }
                        .step-num {
                            background-color: #5D46D2;
                            color: #ffffff;
                            font-weight: 700;
                            width: 18px;
                            height: 18px;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin-right: 10px;
                            font-size: 10px;
                            flex-shrink: 0;
                        }
                        .placa-footer {
                            background-color: #0F172A;
                            color: #ffffff;
                            padding: 14px;
                            text-align: center;
                            border-top: 1px solid #1E293B;
                        }
                        .placa-footer.bg-light-footer {
                            background-color: #1E293B;
                        }
                        .footer-label {
                            text-transform: uppercase;
                            letter-spacing: 1px;
                            font-size: 9px;
                            font-weight: 700;
                            color: #94A3B8;
                            margin-bottom: 6px;
                        }
                        .pin-badge {
                            background-color: #1E293B;
                            border: 1px solid #475569;
                            border-radius: 8px;
                            padding: 4px 12px;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        }
                        .pin-title {
                            font-size: 10px;
                            font-weight: 700;
                            color: #64748B;
                            font-family: monospace;
                        }
                        .pin-number {
                            font-family: monospace;
                            font-weight: 900;
                            font-size: 20px;
                            letter-spacing: 3px;
                            color: #00D369;
                        }
                        @media print {
                            body { background-color: #ffffff; padding: 0; }
                            .placa-container { 
                                margin: 0; 
                                border: none; 
                                box-shadow: none; 
                                width: 148mm; 
                                height: 210mm;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="placa-container">
                        <div class="placa-header">
                            <h1 class="header-logo">iNova<span style="font-weight:300;">Tour</span></h1>
                            <div class="header-subtitle">NOVA LIMA · MINAS GERAIS</div>
                        </div>
                        <div class="placa-body">
                            <h2 class="local-nome">${nomeLocal}</h2>
                            <div class="local-badge">PONTO DE COLETA OFICIAL</div>
                            
                            ${promoBoxHtml}
                            
                            <div class="qr-frame">
                                <img src="${urlQrCode}" alt="QR Code">
                            </div>
                            
                            <ul class="instrucoes-lista">
                                <li><span class="step-num">1</span> <div><strong>Escaneie:</strong> Abra a câmera do seu celular e aponte para o código.</div></li>
                                <li><span class="step-num">2</span> <div><strong>Responda:</strong> Diga-nos sua opinião em menos de 1 minuto.</div></li>
                                ${stepTresHtml}
                            </ul>
                        </div>
                        ${footerHtml}
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