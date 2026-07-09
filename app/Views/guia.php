<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guia Turístico Oficial - Nova Lima</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
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

        /* Banner de Engajamento para Pesquisa */
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
            /* Icon de Megafone FontAwesome */
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
        }

        .atrativo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(93, 70, 210, 0.06);
            border-color: var(--nl-purple-light);
        }

        /* CARD DE CAPA CATEGÓRICA (Estilizados e Modernizados) */
        .atrativo-img-placeholder {
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 3rem;
            position: relative;
        }

        /* Gradientes premium baseados na identidade de Nova Lima para cada categoria */
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
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="<?= base_url('new-logo2.png') ?>" alt="iNovaTour" height="40" class="me-2"
                    onerror="this.style.display='none'">
                <span class="fw-bold text-nl-purple" style="letter-spacing: -0.5px; color: var(--nl-purple);">iNova<span
                        style="font-weight: 400; color: var(--nl-text-dark);">Tour</span></span>
            </a>
            <span class="navbar-text small fw-bold text-uppercase tracking-wider d-none d-sm-inline-block"
                style="color: #718096; font-size: 0.75rem; letter-spacing: 1px;">
                Guia Oficial de Nova Lima
            </span>
        </div>
    </nav>

    <div class="container my-4">

        <!-- BANNER DE ENGAJAMENTO (Atualizado para acionar o Modal de Seleção de Local) -->
        <div class="cta-research-banner">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <span class="badge mb-2"
                        style="background-color: var(--nl-magenta); font-weight: 700; font-size: 0.75rem;">COLABORE COM
                        O TURISMO</span>
                    <h4 class="fw-bold mb-2 text-white">Sua opinião vale prêmios e melhorias em Nova Lima!</h4>
                    <p class="mb-0 text-white-50 small">
                        Ao responder nossa pesquisa de satisfação turística de 1 minuto, você ajuda o município a captar
                        recursos estaduais e aprimorar nossa infraestrutura, atrativos e serviços.
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
                            <!-- Div de capa categórica com a classe correta de estilo baseada no tipo de comércio -->
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
                                    <!-- Botão de avaliar enviando o token específico do local via GET -->
                                    <a href="<?= base_url('pesquisa?token=' . $est['token_qr_code']) ?>"
                                        class="btn btn-nl-outline-purple btn-sm flex-grow-1 text-center">
                                        <i class="fa-solid fa-star me-1"></i> Avaliar
                                    </a>
                                    <button class="btn btn-nl-primary btn-sm px-3"
                                        onclick="alert('Visite nosso estabelecimento oficial em Nova Lima!')">Como
                                        chegar</button>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filtrarSetor(setor) {
            // Atualiza classe ativa dos botões
            const botoes = document.querySelectorAll('.filter-btn');
            botoes.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Filtra os cards
            const cards = document.querySelectorAll('.card-item');
            cards.forEach(card => {
                if (setor === 'todos' || card.getAttribute('data-setor') === setor) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Script para redirecionar o usuário do Modal para o formulário correto usando o token selecionado
        function redirecionarParaPesquisa() {
            const tokenSelected = document.getElementById('selectTokenLocal').value;
            if (!tokenSelected) {
                alert('Por favor, escolha um estabelecimento na lista antes de prosseguir.');
                return;
            }
            // Redireciona enviando o token dinâmico na URL
            window.location.href = "<?= base_url('pesquisa?token=') ?>" + tokenSelected;
        }
    </script>
</body>

</html>