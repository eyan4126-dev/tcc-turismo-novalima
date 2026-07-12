<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guia Turístico Oficial - Nova Lima</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= site_url('new-logo2.png') ?>">
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
            color: var(--nl-text-dark);
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

        .cta-research-banner {
            background: linear-gradient(135deg, var(--nl-purple) 0%, #301b9e 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(93, 70, 210, 0.15);
            margin-bottom: 2rem;
        }

        .cta-research-banner::after {
            content: "\f0a1";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: -10px;
            bottom: -20px;
            font-size: 8rem;
            color: rgba(255, 255, 255, 0.05);
            transform: rotate(-15deg);
        }

        .category-badge {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 6px 12px;
            border-radius: 30px;
        }

        .badge-hospedagem {
            background-color: var(--nl-purple-light);
            color: var(--nl-purple);
        }

        .badge-alimentacao_comercio {
            background-color: #FFE3F0;
            color: var(--nl-magenta);
        }

        .badge-natural {
            background-color: #E8F9EE;
            color: var(--nl-green-neon);
        }

        .badge-cultural {
            background-color: #E0F2FE;
            color: #0099FF;
        }

        .filter-btn {
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 18px;
            border: 1px solid #E2E8F0;
            background-color: #ffffff;
            color: #4A5568;
            transition: all 0.2s ease;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background-color: var(--nl-purple);
            border-color: var(--nl-purple);
            color: #ffffff;
        }

        .atrativo-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        .atrativo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(93, 70, 210, 0.06);
            border-color: var(--nl-purple-light);
        }

        .badge-highlight-premium {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, var(--nl-magenta) 0%, var(--nl-purple) 100%);
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 50px;
            box-shadow: 0 4px 10px rgba(230, 0, 126, 0.25);
            z-index: 10;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .atrativo-img-placeholder {
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 3rem;
            position: relative;
        }

        .atrativo-img-placeholder.placeholder-hospedagem {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .atrativo-img-placeholder.placeholder-alimentacao_comercio {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
            color: var(--nl-magenta);
        }

        .atrativo-img-placeholder.placeholder-natural {
            background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        }

        .atrativo-img-placeholder.placeholder-cultural {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        .btn-nl-primary {
            background-color: var(--nl-purple);
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: 30px;
            padding: 8px 20px;
            transition: background-color 0.2s;
        }

        .btn-nl-primary:hover {
            background-color: var(--nl-purple-dark);
            color: #ffffff;
        }

        .btn-nl-outline-purple {
            background-color: transparent;
            border: 1px solid var(--nl-purple);
            color: var(--nl-purple);
            font-weight: 600;
            border-radius: 30px;
            padding: 7px 18px;
            transition: all 0.2s;
        }

        .btn-nl-outline-purple:hover {
            background-color: var(--nl-purple);
            color: #ffffff;
        }

        .btn-nl-success {
            background-color: var(--nl-green-neon);
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: 30px;
            padding: 8px 24px;
            transition: background-color 0.2s;
        }

        .btn-nl-success:hover {
            background-color: #00b358;
            color: #ffffff;
        }

        .floating-voucher-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: linear-gradient(135deg, var(--nl-green-neon) 0%, var(--nl-purple) 100%);
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 14px 28px;
            font-weight: 800;
            box-shadow: 0 10px 25px rgba(0, 211, 105, 0.35);
            z-index: 2000;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .floating-voucher-btn:hover {
            transform: scale(1.06) translateY(-2px);
            color: #ffffff;
            box-shadow: 0 12px 30px rgba(0, 211, 105, 0.45);
        }

        @keyframes waveGradiente {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .voucher-active-card-modal {
            background: linear-gradient(-45deg, #FF5500, #E6007E, #5D46D2, #00D369);
            background-size: 400% 400%;
            animation: waveGradiente 4s ease infinite;
            border-radius: 16px;
            color: #ffffff;
        }

        .pin-box-input-modal {
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 12px;
            text-align: center;
            color: var(--nl-purple);
            max-width: 200px;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
        <div class="container d-flex align-items-center position-fixed">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="<?= site_url('new-logo2.png') ?>" alt="iNovaTour" height="40" class="me-2"
                    onerror="this.style.display='none'">
                <span class="fw-bold text-nl-purple" style="letter-spacing: -0.5px; color: var(--nl-purple);">iNova<span
                        style="font-weight: 400; color: var(--nl-text-dark);">Tour</span></span>
            </a>

            <span
                class="navbar-text small fw-bold text-uppercase tracking-wider d-none d-md-inline-block position-absolute start-50 translate-middle-x"
                style="color: #718096; font-size: 0.75rem; letter-spacing: 1px; white-space: nowrap;">
                Guia Oficial de Nova Lima
            </span>

            <div class="ms-auto">
                <a href="<?= site_url('login') ?>"
                    class="btn btn-nl-outline rounded-pill px-3 py-2 fw-bold text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-store me-1"></i> Área do Lojista
                </a>
            </div>
        </div>
    </nav>

    <!-- BOTÃO FLUTUANTE DE RESGATE DO VOUCHER DO LOCALSTORAGE (Sincronizado com DesconTour) -->
    <button id="floatingVoucherRecovery" class="floating-voucher-btn d-none animate__animated animate__bounceIn"
        onclick="abrirModalVoucher()">
        <i class="fa-solid fa-ticket fa-lg animate__animated animate__swing animate__infinite animate__slower"></i>
        <div class="text-start">
            <small class="d-block text-uppercase"
                style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px; opacity: 0.9;">Você tem 1 Desconto
                Ativo!</small>
            <span class="d-block" style="font-size: 0.85rem; line-height: 1.1;" id="floatingVoucherLabel">Resgatar Cupom
                (10% OFF)</span>
        </div>
    </button>

    <div class="container my-4">

        <!-- BANNER DE ENGAJAMENTO INTEGRADO COM DESCONTOUR -->
        <div class="cta-research-banner">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <span class="badge mb-2"
                        style="background-color: var(--nl-magenta); font-weight: 700; font-size: 0.75rem;">PROGRAMA
                        DESCONTOUR</span>
                    <h4 class="fw-bold mb-2 text-white">Sua opinião fomenta o turismo de Nova Lima!</h4>
                    <p class="mb-0 text-white-50 small">
                        Ao avaliar os atrativos de Nova Lima em 1 minuto, você destrava de forma instantânea **descontos
                        flexíveis de 5% a 20%** para utilizar nos melhores restaurantes, cervejarias e pousadas
                        credenciadas da cidade!
                    </p>
                </div>
                <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                    <button class="btn btn-nl-success btn-lg px-4 shadow" data-bs-toggle="modal"
                        data-bs-target="#modalSelecionarLocal">
                        <i class="fa-solid fa-clipboard-question me-2"></i>Responder Pesquisa
                    </button>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Explore Nova Lima</h3>
                <p class="text-muted small mb-0">Encontre pousadas, gastronomia e roteiros naturais incríveis.</p>
            </div>
        </div>

        <!-- FILTROS DE ATUAÇÃO -->
        <div class="d-flex gap-2 mb-4 overflow-x-auto pb-2" style="white-space: nowrap;">
            <button class="filter-btn active" onclick="filtrarSetor('todos')">Todos</button>
            <button class="filter-btn" onclick="filtrarSetor('hospedagem')"><i class="fa-solid fa-bed me-1"></i>
                Hospedagem</button>
            <button class="filter-btn" onclick="filtrarSetor('alimentacao_comercio')"><i
                    class="fa-solid fa-utensils me-1"></i> Alimentação & Cervejarias</button>
            <button class="filter-btn" onclick="filtrarSetor('natural')"><i class="fa-solid fa-mountain-sun me-1"></i>
                Turismo de Natureza</button>
            <button class="filter-btn" onclick="filtrarSetor('cultural')"><i class="fa-solid fa-masks-theater me-1"></i>
                Cultura & Eventos</button>
        </div>

        <!-- LISTA DE ESTABELECIMENTOS DO GUIA -->
        <div class="row g-4" id="cardsContainer">
            <?php if (!empty($estabelecimentos)): ?>
                <?php foreach ($estabelecimentos as $est): ?>
                    <div class="col-md-6 col-lg-4 card-item" data-setor="<?= $est['setor'] ?>">
                        <div class="atrativo-card d-flex flex-column h-100">

                            <!-- BADGE DE DESTAQUE PREMIUM DINÂMICO CONFORME DESCONTO DO ESTABELECIMENTO -->
                            <?php if ($est['aceita_desconto'] == 1): ?>
                                <div class="badge-highlight-premium">
                                    <i class="fa-solid fa-certificate me-1"></i> DesconTour:
                                    <?= esc($est['desconto_percentagem'] ?? '10') ?>% OFF
                                </div>
                            <?php endif; ?>

                            <div class="atrativo-img-placeholder placeholder-<?= $est['setor'] ?>">
                                <?php if ($est['setor'] === 'hospedagem'): ?>
                                    <i class="fa-solid fa-hotel"></i>
                                <?php elseif ($est['setor'] === 'alimentacao_comercio'): ?>
                                    <i class="fa-solid fa-beer-mug-empty"></i>
                                <?php elseif ($est['setor'] === 'natural'): ?>
                                    <i class="fa-solid fa-tree"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-calendar-days"></i>
                                <?php endif; ?>
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <div class="mb-2">
                                    <span class="category-badge badge-<?= $est['setor'] ?>">
                                        <?= str_replace('_', ' ', $est['setor']) ?>
                                    </span>
                                </div>
                                <h5 class="fw-bold mb-1" style="color: var(--nl-text-dark);"><?= $est['razao_social'] ?></h5>
                                <p class="text-muted small mb-3 flex-grow-1">
                                    <?php if ($est['tipo'] === 'evento'): ?>
                                        <i class="fa-solid fa-calendar-check text-nl-magenta me-1"></i> Evento:
                                        <?= date('d/m/Y', strtotime($est['data_inicio'])) ?> a
                                        <?= date('d/m/Y', strtotime($est['data_fim'])) ?>
                                    <?php else: ?>
                                        <i class="fa-solid fa-circle-check text-success me-1"></i> Ponto Turístico Ativo
                                    <?php endif; ?>
                                </p>
                                <div class="pt-3 border-top d-flex gap-2 justify-content-between align-items-center">
                                    <a href="<?= site_url('pesquisa?token=' . $est['token_qr_code'] . '&origem=guia') ?>"
                                        class="btn btn-nl-outline-purple btn-sm flex-grow-1 text-center">
                                        <i class="fa-solid fa-star me-1"></i> Avaliar
                                    </a>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode(esc($est['razao_social']) . ', Nova Lima - MG') ?>"
                                        target="_blank" rel="noopener noreferrer"
                                        class="btn btn-nl-primary btn-sm px-3 d-inline-flex align-items-center gap-1">
                                        <i class="fa-solid fa-map-location-dot"></i> Rota
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">Nenhum estabelecimento ou atrativo cadastrado no momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- MODAL DE SELEÇÃO DE LOCAL PARA AVALIAÇÃO -->
    <div class="modal fade" id="modalSelecionarLocal" tabindex="-1" aria-labelledby="modalSelecionarLocalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-header border-0 bg-light py-3"
                    style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title fw-bold text-nl-purple" id="modalSelecionarLocalLabel">
                        <i class="fa-solid fa-building-circle-check me-2"></i>Onde você esteve?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Selecione o estabelecimento, comércio ou atrativo que você visitou
                        recentemente para responder à pesquisa de opinião:</p>

                    <div class="mb-3">
                        <label for="selectTokenLocal" class="form-label small fw-bold text-muted uppercase">Escolha o
                            Local</label>
                        <select class="form-select border-2 py-2" id="selectTokenLocal" style="border-radius: 10px;">
                            <option value="" disabled selected>-- Selecione um atrativo --</option>
                            <?php if (!empty($estabelecimentos)): ?>
                                <?php foreach ($estabelecimentos as $est): ?>
                                    <option value="<?= $est['token_qr_code'] ?>">
                                        <?= $est['razao_social'] ?> (<?= ucfirst(str_replace('_', ' ', $est['setor'])) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light"
                    style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-nl-primary rounded-pill px-4"
                        onclick="redirecionarParaPesquisa()">Iniciar Pesquisa</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE RESGATE DE VOUCHER (DINÂMICO CONTRA PRINTS INTEGRADO AO DESCONTOUR) -->
    <div class="modal fade" id="modalAtivacaoVoucher" tabindex="-1" aria-labelledby="modalAtivacaoVoucherLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 bg-light py-3">
                    <h5 class="modal-title fw-bold text-nl-purple" id="modalAtivacaoVoucherLabel">
                        <i class="fa-solid fa-gift me-2 text-nl-magenta"></i>Programa DesconTour
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btnCloseVoucherModal"></button>
                </div>
                <div class="modal-body p-4 text-center">

                    <div id="modalVoucherDisplay" class="voucher-active-card-modal p-4 shadow-sm text-white mb-4">
                        <i class="fa-solid fa-ticket fa-3x mb-2 animate__animated animate__swing animate__infinite"></i>
                        <h4 class="fw-bold m-0" id="modalVoucherTitle">Desconto de 10% Ativo!</h4>
                        <p class="small opacity-90 m-0 mt-1" id="modalVoucherSubtitle">Carregado no seu navegador</p>
                    </div>

                    <!-- Sessão do PIN de Ativação (Público ou Local) -->
                    <div id="modalPinActivationZone">
                        <p class="small text-muted mb-3" id="modalVoucherExplainer">Selecione onde você está consumindo
                            para digitar o PIN de validação do caixa:</p>

                        <!-- Dropdown de seleção de estabelecimento da rede ativa -->
                        <div class="mb-4" id="modalPartnerSelectWrapper">
                            <label class="form-label small fw-bold text-muted text-uppercase d-block text-start">Onde
                                você está agora?</label>
                            <select class="form-select border-2" id="modalSelectTokenLocal"
                                onchange="atualizarPorcentagemModal(this)">
                                <option value="" disabled selected>-- Escolha o lojista para desconto --</option>
                                <?php if (!empty($estabelecimentos)): ?>
                                    <?php foreach ($estabelecimentos as $est): ?>
                                        <?php if ($est['aceita_desconto'] == 1): ?>
                                            <!-- SALVA A PORCENTAGEM EXCLUSIVA NO ATRIBUTO DATA-PERCENT -->
                                            <option value="<?= $est['token_qr_code'] ?>"
                                                data-percent="<?= esc($est['desconto_percentagem'] ?? '10') ?>">
                                                <?= $est['razao_social'] ?> (<?= esc($est['desconto_percentagem'] ?? '10') ?>% OFF)
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <input type="text" id="modalCaixaPinInput" class="form-control pin-box-input-modal mb-4"
                            maxlength="4" placeholder="0000" inputmode="numeric">

                        <button type="button" class="btn btn-nl-success btn-lg w-100 rounded-pill py-3 fw-bold"
                            onclick="validarPinModal()">
                            <i class="fa-solid fa-bolt me-2"></i> <span id="btnAtivarDescontoModalText">Ativar
                                Desconto</span>
                        </button>
                    </div>

                    <!-- Cronômetro Regressivo Animado (Oculto por padrão) -->
                    <div id="modalCountdownZone" class="d-none">
                        <div class="alert alert-success border-0 shadow-sm p-3 mb-4 rounded-4">
                            <h6 class="alert-heading fw-bold mb-1"><i
                                    class="fa-solid fa-circle-check me-2 animate__animated animate__pulse animate__infinite"></i>Desconto
                                Autorizado no Caixa!</h6>
                            <p class="small text-muted mb-0">Mostre esta tela piscando ao atendente para aplicar o
                                desconto na sua conta agora.</p>
                        </div>

                        <div class="my-4">
                            <div class="display-1 fw-bold text-nl-purple font-monospace mb-2" id="modalRegressiveClock"
                                style="font-size: 4.5rem; letter-spacing: -2px;">02:00</div>
                            <div class="progress" style="height: 12px; background-color: #E2E8F0; border-radius: 50px;">
                                <div id="modalTimerProgressBar"
                                    class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 100%; background: linear-gradient(90deg, #00D369 0%, #5D46D2 100%);">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 text-muted small border">
                            <i class="fa-solid fa-circle-info me-2 text-nl-purple"></i>Esta tela é dinâmica e expira em
                            2 minutos. Capturas de tela e prints são inválidos.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let voucherAtivoLocal = null;
        let ativacaoModalInstanciado = null;

        document.addEventListener("DOMContentLoaded", function () {
            ativacaoModalInstanciado = new bootstrap.Modal(document.getElementById('modalAtivacaoVoucher'));

            // RECUPERAÇÃO AUTOMÁTICA DE VOUCHER DO LOCALSTORAGE (Sincronizado com o DesconTour dinâmico)
            const voucherObj = localStorage.getItem('inovatour_voucher');
            if (voucherObj) {
                try {
                    voucherAtivoLocal = JSON.parse(voucherObj);
                    if (voucherAtivoLocal && voucherAtivoLocal.status === 'pendente') {
                        // Resgata o desconto customizado do JSON
                        const desc = voucherAtivoLocal.desconto || 10;

                        if (voucherAtivoLocal.token === 'rede_parceira') {
                            document.getElementById('floatingVoucherLabel').innerText = `DesconTour Geral Ativo!`;
                        } else {
                            document.getElementById('floatingVoucherLabel').innerText = `${voucherAtivoLocal.local}: ${desc}% OFF!`;
                        }

                        document.getElementById('floatingVoucherRecovery').classList.remove('d-none');
                    }
                } catch (e) {
                    console.error("Erro na leitura do LocalStorage do Turista", e);
                }
            }
        });

        // Atualiza dinamicamente o texto do botão do modal com a porcentagem ao selecionar no dropdown
        function atualizarPorcentagemModal(select) {
            const selectedOption = select.options[select.selectedIndex];
            const percent = selectedOption.getAttribute('data-percent') || 10;
            document.getElementById('modalVoucherTitle').innerText = `Desconto de ${percent}% Autorizado!`;
            document.getElementById('btnAtivarDescontoModalText').innerText = `Ativar Desconto de ${percent}%`;
        }

        // Configura o modal de resgate dinamicamente dependendo de onde o turista avaliou
        function abrirModalVoucher() {
            if (!voucherAtivoLocal) return;

            const desc = voucherAtivoLocal.desconto || 10;

            if (voucherAtivoLocal.token === 'rede_parceira') {
                document.getElementById('modalVoucherTitle').innerText = `DesconTour Geral Autorizado!`;
                document.getElementById('modalVoucherSubtitle').innerText = "Válido em qualquer parceiro credenciado";
                document.getElementById('modalVoucherExplainer').innerText = "Selecione o estabelecimento onde você está fisicamente agora para digitar o PIN do balcão deles:";
                document.getElementById('modalPartnerSelectWrapper').classList.remove('d-none');
                document.getElementById('btnAtivarDescontoModalText').innerText = `Ativar Desconto`;
            } else {
                document.getElementById('modalVoucherTitle').innerText = `Desconto de ${desc}% Ativo!`;
                document.getElementById('modalVoucherSubtitle').innerText = `Válido exclusivamente no(a) ${voucherAtivoLocal.local}`;
                document.getElementById('modalVoucherExplainer').innerText = `Apresente este celular ao atendente do(a) ${voucherAtivoLocal.local} e digite o PIN de balcão deles abaixo:`;
                document.getElementById('modalPartnerSelectWrapper').classList.add('d-none');
                document.getElementById('btnAtivarDescontoModalText').innerText = `Ativar Desconto de ${desc}%`;
            }

            ativacaoModalInstanciado.show();
        }

        async function validarPinModal() {
            const pinDigitado = document.getElementById('modalCaixaPinInput').value.trim();

            if (pinDigitado.length < 4) {
                alert("Por favor, digite o código de 4 dígitos do balcão.");
                return;
            }

            let tokenParaValidar = "";
            if (voucherAtivoLocal.token === 'rede_parceira') {
                tokenParaValidar = document.getElementById('modalSelectTokenLocal').value;
                if (!tokenParaValidar) {
                    alert("Por favor, selecione onde você está consumindo para resgatar.");
                    return;
                }
            } else {
                tokenParaValidar = voucherAtivoLocal.token;
            }

            try {
                const response = await fetch(`<?= site_url('api/pesquisa/validar-pin') ?>`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        token: tokenParaValidar,
                        pin: pinDigitado
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    document.getElementById('btnCloseVoucherModal').classList.add('d-none');
                    document.getElementById('modalPinActivationZone').classList.add('d-none');
                    document.getElementById('modalCountdownZone').classList.remove('d-none');

                    dispararCronometroRegressivoModal(120);

                    localStorage.removeItem('inovatour_voucher');
                    document.getElementById('floatingVoucherRecovery').classList.add('d-none');
                } else {
                    alert("Código PIN inválido para este estabelecimento.");
                }
            } catch (e) {
                alert("Erro ao validar PIN.");
            }
        }

        function dispararCronometroRegressivoModal(segundosTotais) {
            const clockText = document.getElementById('modalRegressiveClock');
            const pBar = document.getElementById('modalTimerProgressBar');
            let tempoRestante = segundosTotais;

            const intervalo = setInterval(() => {
                tempoRestante--;

                let minutos = Math.floor(tempoRestante / 60);
                let segundos = tempoRestante % 60;

                clockText.innerText = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;

                let porcentagem = (tempoRestante / segundosTotais) * 100;
                pBar.style.width = `${porcentagem}%`;

                if (tempoRestante <= 0) {
                    clearInterval(intervalo);
                    document.getElementById('modalCountdownZone').innerHTML = `
                        <div class="alert alert-danger border-0 shadow-sm p-4 rounded-4">
                            <i class="fa-solid fa-clock-rotate-left fa-3x mb-2 text-danger"></i>
                            <h5 class="fw-bold">Cupom Expirado!</h5>
                            <p class="small text-muted mb-0">Este voucher foi devidamente consumido ou expirou o prazo limite de permanência de validação no caixa.</p>
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill mt-4 px-4 fw-bold" data-bs-dismiss="modal" onclick="window.location.reload();">Fechar Guia</button>
                        </div>
                    `;
                    document.getElementById('btnCloseVoucherModal').classList.remove('d-none');
                }
            }, 1000);
        }

        function filtrarSetor(setor) {
            const botoes = document.querySelectorAll('.filter-btn');
            botoes.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const cards = document.querySelectorAll('.card-item');
            cards.forEach(card => {
                if (setor === 'todos' || card.getAttribute('data-setor') === setor) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function redirecionarParaPesquisa() {
            const tokenSelected = document.getElementById('selectTokenLocal').value;
            if (!tokenSelected) {
                alert('Por favor, escolha um estabelecimento na lista antes de prosseguir.');
                return;
            }
            window.location.href = "<?= site_url('pesquisa?token=') ?>" + tokenSelected + "&origem=guia";
        }
    </script>
</body>

</html>