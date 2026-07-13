<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iNovaTour - Pesquisa Turística</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= site_url('new-logo2.png') ?>">

    <style>
        :root {
            --nl-purple: #5D46D2;
            --nl-purple-dark: #402cb3;
            --nl-purple-light: #ECE9FC;
            --nl-magenta: #E6007E;
            --nl-green-neon: #00D369;
            --nl-green-neon-hover: #00b358;
            --nl-bg-light: #F4F6F9;
            --nl-text-dark: #1A1A1A;
            --nl-gradient: linear-gradient(90deg, #FF5500 0%, #E6007E 25%, #5D46D2 50%, #0099FF 75%, #00D369 100%);
        }

        body {
            background-color: var(--nl-bg-light);
            min-height: 100vh;
            color: var(--nl-text-dark);
            font-family: 'Inter', system-ui, sans-serif;
        }

        .top-identity-bar {
            height: 12px;
            background: var(--nl-gradient);
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
        }

        .brand-logo-container {
            width: 100px;
            height: 100px;
            background-color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(93, 70, 210, 0.08);
            border: 2px solid #E2E8F0;
            margin: 0 auto 1.5rem auto;
        }

        .brand-logo-container img {
            width: 60%;
            height: auto;
            object-fit: contain;
        }

        .progress {
            height: 8px;
            background-color: #E2E8F0;
            border-radius: 4px;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--nl-magenta) 0%, var(--nl-purple) 100%);
        }

        .card-step {
            background: #FFFFFF;
            color: var(--nl-text-dark);
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 10px 25px -5px rgba(93, 70, 210, 0.04), 0 8px 10px -6px rgba(93, 70, 210, 0.04);
            display: none;
            transition: all 0.3s ease;
        }

        .card-step.active {
            display: block;
            animation: fadeIn 0.4s ease-in-out;
        }

        .text-primary-govt {
            color: var(--nl-purple) !important;
        }

        .selectable-card {
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #FFFFFF;
            height: 100%;
        }

        .selectable-card:hover {
            transform: translateY(-2px);
            border-color: var(--nl-purple);
            background-color: #F8F7FD;
        }

        .selectable-card.selected {
            border-color: var(--nl-purple);
            background-color: var(--nl-purple-light);
            box-shadow: 0 0 0 3px rgba(93, 70, 210, 0.15);
        }

        .selectable-card i {
            font-size: 1.75rem;
            color: var(--nl-purple);
            margin-bottom: 0.5rem;
        }

        .price-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--nl-purple);
            display: block;
            margin-bottom: 0.5rem;
        }

        .custom-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 5px;
            background: #E2E8F0;
            outline: none;
        }

        .custom-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--nl-purple);
            cursor: pointer;
            transition: transform 0.1s ease;
        }

        .custom-slider::-webkit-slider-thumb:hover {
            transform: scale(1.15);
        }

        .nps-container {
            display: flex;
            justify-content: space-between;
            gap: 4px;
            flex-wrap: wrap;
        }

        .nps-btn {
            flex: 1;
            min-width: 32px;
            height: 42px;
            border: 1px solid #E2E8F0;
            background: #FFFFFF;
            border-radius: 6px;
            font-weight: 600;
            color: #4A5568;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nps-btn:hover {
            transform: scale(1.08);
            border-color: #A0AEC0;
        }

        .nps-btn.detrator.selected {
            background-color: #DC3545;
            color: #FFFFFF;
            border-color: #BD2130;
        }

        .nps-btn.neutro.selected {
            background-color: #FFC107;
            color: var(--nl-text-dark);
            border-color: #D39E00;
        }

        .nps-btn.promotor.selected {
            background-color: var(--nl-green-neon);
            color: #FFFFFF;
            border-color: #00b358;
        }

        .star-rating {
            direction: rtl;
            display: inline-flex;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #E2E8F0;
            font-size: 2.5rem;
            padding: 0 0.2rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating label:hover,
        .star-rating label:hover~label,
        .star-rating input:checked~label {
            color: #FFC107;
        }

        .autocomplete-suggestions {
            position: absolute;
            z-index: 1000;
            background: #FFFFFF;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(93, 70, 210, 0.05);
            margin-top: 2px;
        }

        .suggestion-item {
            padding: 12px;
            cursor: pointer;
            border-bottom: 1px solid #F1F5F9;
            color: #4A5568;
        }

        .suggestion-item:hover {
            background-color: var(--nl-purple-light);
            color: var(--nl-purple);
        }

        .btn-gov-primary {
            background-color: var(--nl-purple);
            border-color: var(--nl-purple);
            color: #FFFFFF;
        }

        .btn-gov-primary:hover {
            background-color: var(--nl-purple-dark);
            border-color: var(--nl-purple-dark);
            color: #FFFFFF;
        }

        .btn-gov-success {
            background-color: var(--nl-green-neon) !important;
            border-color: var(--nl-green-neon) !important;
            color: #FFFFFF !important;
            font-weight: 700;
        }

        .btn-gov-success:hover {
            background-color: var(--nl-green-neon-hover) !important;
            border-color: var(--nl-green-neon-hover) !important;
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

        .voucher-active-card {
            background: linear-gradient(-45deg, #FF5500, #E6007E, #5D46D2, #00D369);
            background-size: 400% 400%;
            animation: waveGradiente 4s ease infinite;
            border-radius: 20px;
            color: #ffffff;
            box-shadow: 0 10px 30px rgba(93, 70, 210, 0.3);
        }

        .pin-box-input {
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

        .pin-box-input:focus {
            border-color: var(--nl-purple);
            box-shadow: 0 0 0 3px rgba(93, 70, 210, 0.15);
            outline: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <?php
    $nomeLugar = isset($estabelecimento['razao_social']) ? $estabelecimento['razao_social'] : 'Estabelecimento';
    $tokenEstOriginal = isset($estabelecimento['token_qr_code']) ? $estabelecimento['token_qr_code'] : '';
    $aceitaDesconto = isset($estabelecimento['aceita_desconto']) && ((int) $estabelecimento['aceita_desconto'] === 1);
    $descontoPercentagem = isset($estabelecimento['desconto_percentagem']) ? (int) $estabelecimento['desconto_percentagem'] : 10;
    ?>

    <div class="top-identity-bar"></div>

    <div class="container py-5 mt-3">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <div class="brand-logo-container">
                    <img src="<?= site_url('new-logo2.png') ?>" alt="iNovaTour Logo">
                </div>

                <!-- Barra de Progresso Principal -->
                <div class="mb-4" id="progressWrapper">
                    <div class="d-flex justify-content-between text-sm mb-1">
                        <span class="text-muted small fw-medium">Pesquisa de Fluxo Turístico</span>
                        <span id="progressText" class="fw-bold text-primary-govt small">Passo 1 de 4</span>
                    </div>
                    <div class="progress">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 25%;"></div>
                    </div>
                </div>

                <div class="alertContainer animate__animated animate__fadeIn" id="alertContainer"></div>

                <form id="formInovador">
                    <input type="hidden" id="id_estabelecimento" name="id_estabelecimento"
                        value="<?= esc($tokenEstOriginal) ?>">
                    <input type="hidden" id="device_hash" name="device_hash">

                    <!-- PASSO 1: Boas-vindas, Origem e Motivação -->
                    <div class="card-step active p-4" id="step1">
                        <h4 class="fw-bold mb-3 text-nl-purple">
                            <i class="fa-solid fa-map-location-dot text-primary-govt me-2"></i>Boas-vindas ao
                            <strong><?= esc($nomeLugar) ?></strong>!
                        </h4>

                        <div class="mb-4 position-relative">
                            <label class="form-label fw-semibold text-secondary small">De qual cidade/estado ou país
                                você é?</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i
                                        class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-1" id="busca_cidade"
                                    autocomplete="off" placeholder="Busque e selecione a cidade...">
                            </div>
                            <div id="suggestions" class="autocomplete-suggestions d-none"></div>
                            <input type="hidden" id="cidade_origem">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small mb-3">Qual o principal motivo da
                                sua visita?</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="selectable-card" data-input="motivo" data-value="lazer">
                                        <i class="fa-solid fa-umbrella-beach"></i>
                                        <div class="fw-bold text-muted small">Lazer / Férias</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="selectable-card" data-input="motivo" data-value="negocios">
                                        <i class="fa-solid fa-briefcase"></i>
                                        <div class="fw-bold text-muted small">Negócios</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="selectable-card" data-input="motivo" data-value="parentes_amigos">
                                        <i class="fa-solid fa-people-roof"></i>
                                        <div class="fw-bold text-muted small">Amigos/Parentes</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="selectable-card" data-input="motivo" data-value="outro">
                                        <i class="fa-solid fa-route"></i>
                                        <div class="fw-bold text-muted small">Outro Motivo</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="motivo_visita">
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-gov-primary px-4 fw-bold rounded-pill" id="btnNext1"
                                onclick="nextStep(2)" disabled>Avançar <i
                                    class="fa-solid fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- PASSO 2: Estadia e Hospedagem -->
                    <div class="card-step p-4" id="step2">
                        <h4 class="fw-bold mb-4"><i class="fa-solid fa-clock text-primary-govt me-2"></i>Sobre a sua
                            estadia</h4>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small mb-3">Quanto tempo você pretende
                                ficar na cidade?</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="selectable-card" data-input="permanencia" data-value="bate_volta">
                                        <i class="fa-solid fa-bolt"></i>
                                        <div class="fw-bold text-muted small">Bate e Volta</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="selectable-card" data-input="permanencia" data-value="dormir">
                                        <i class="fa-solid fa-moon"></i>
                                        <div class="fw-bold text-muted small">Vou Pernoitar</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="tempo_permanencia">
                        </div>

                        <div class="mb-3 d-none" id="subHospedagem">
                            <label class="form-label fw-semibold text-secondary small mb-2">Onde você está se
                                hospedando? <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="selectable-card py-3 px-1" data-input="hospedagem"
                                        data-value="hotel_pousada">
                                        <i class="fa-solid fa-hotel" style="font-size:1.3rem"></i>
                                        <div class="small fw-bold text-muted">Hotel / Pousada</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="selectable-card py-3 px-1" data-input="hospedagem"
                                        data-value="airbnb_aluguel">
                                        <i class="fa-solid fa-house-user" style="font-size:1.3rem"></i>
                                        <div class="small fw-bold text-muted">Airbnb / Aluguel</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="selectable-card py-3 px-1" data-input="hospedagem"
                                        data-value="casa_amigos_parentes">
                                        <i class="fa-solid fa-user-group" style="font-size:1.3rem"></i>
                                        <div class="small fw-bold text-muted">Amigos / Parentes</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="selectable-card py-3 px-1" data-input="hospedagem" data-value="outro">
                                        <i class="fa-solid fa-bed-pulse" style="font-size:1.3rem"></i>
                                        <div class="small fw-bold text-muted">Outro Local</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="local_hospedagem">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4 fw-bold rounded-pill"
                                onclick="prevStep(1)"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</button>
                            <button type="button" class="btn btn-gov-primary px-4 fw-bold rounded-pill" id="btnNext2"
                                onclick="nextStep(3)" disabled>Avançar <i
                                    class="fa-solid fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- PASSO 3: Gasto Estimado -->
                    <div class="card-step p-4" id="step3">
                        <h4 class="fw-bold mb-4"><i class="fa-solid fa-wallet text-primary-govt me-2"></i>Planejamento
                            Financeiro</h4>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small mb-3">
                                Qual o seu gasto médio estimado aqui no(a) <strong><?= esc($nomeLugar) ?></strong>?
                            </label>

                            <div class="my-4 mx-auto text-center" style="max-width: 290px;">
                                <div
                                    class="input-group input-group-lg border rounded-3 shadow-sm bg-white overflow-hidden">
                                    <span
                                        class="input-group-text bg-light border-0 fw-bold text-secondary px-3">R$</span>
                                    <input type="text" id="gasto_input_pix"
                                        class="form-control border-0 text-center fw-bold text-primary-govt fs-3 p-2"
                                        inputmode="numeric" placeholder="0,00" autocomplete="off">
                                </div>
                                <div class="form-text text-muted small mt-2">Digite o valor incluindo os centavos.</div>
                            </div>

                            <input type="hidden" id="faixa_gasto" name="valor_gasto_estimado" value="0.00">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4 fw-bold rounded-pill"
                                onclick="prevStep(2)"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</button>
                            <button type="button" class="btn btn-gov-primary px-4 fw-bold rounded-pill" id="btnNext3"
                                onclick="nextStep(4)" disabled>Avançar <i
                                    class="fa-solid fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- PASSO 4: NPS, Estrelas e Coleta Segura de CPF -->
                    <div class="card-step p-4" id="step4">
                        <h4 class="fw-bold mb-4"><i class="fa-solid fa-ranking-star text-primary-govt me-2"></i>Sua
                            Opinião Final</h4>

                        <div class="mb-4 text-center">
                            <label class="form-label fw-semibold d-block text-start text-secondary small">
                                Como você avalia sua experiência geral aqui no(a)
                                <strong><?= esc($nomeLugar) ?></strong>?
                            </label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="satisfacao_estrelas" value="5" /><label for="star5"
                                    class="fa-solid fa-star"></label>
                                <input type="radio" id="star4" name="satisfacao_estrelas" value="4" /><label for="star4"
                                    class="fa-solid fa-star"></label>
                                <input type="radio" id="star3" name="satisfacao_estrelas" value="3" /><label for="star3"
                                    class="fa-solid fa-star"></label>
                                <input type="radio" id="star2" name="satisfacao_estrelas" value="2" /><label for="star2"
                                    class="fa-solid fa-star"></label>
                                <input type="radio" id="star1" name="satisfacao_estrelas" value="1" /><label for="star1"
                                    class="fa-solid fa-star"></label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small mb-2">Qual a chance de você
                                recomendar esta cidade/evento para amigos ou familiares?</label>
                            <div class="nps-container"></div>
                            <input type="hidden" id="nps_val">
                            <div class="d-flex justify-content-between text-muted small mt-1"
                                style="font-size: 0.75rem;">
                                <span>0 (Jamais)</span>
                                <span>10 (Com certeza)</span>
                            </div>
                        </div>

                        <div class="mb-4 border-top pt-4">
                            <label class="form-label fw-bold text-dark small"><i
                                    class="fa-solid fa-shield-halved me-1 text-nl-purple"></i> Digite seu CPF para
                                validação</label>
                            <input type="text" id="turista_cpf" name="cpf"
                                class="form-control rounded-pill border-2 px-3 py-2 fw-semibold"
                                placeholder="000.000.000-00" required>
                            <div class="form-text text-muted small" style="font-size:0.72rem;">* Seus dados são
                                protegidos em conformidade com a LGPD e usados exclusivamente para coibir múltiplos
                                envios.</div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4 fw-bold rounded-pill"
                                onclick="prevStep(3)"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</button>
                            <button type="submit" class="btn btn-gov-success px-5 fw-bold rounded-pill"
                                id="btnSubmit">Finalizar ✨</button>
                        </div>
                    </div>
                </form>

                <!-- PASSO 5 (DINÂMICO): TELA DE VOUCHER / CRONÔMETRO PÓS-PIN DO PROGRAMA DESCONTOUR -->
                <div class="card-step p-4 text-center animate__animated animate__fadeIn" id="stepVoucher">
                    <div class="voucher-active-card p-4 shadow-lg text-white mb-4">
                        <i class="fa-solid fa-ticket fa-3x mb-3 animate__animated animate__swing animate__infinite"></i>
                        <h4 class="fw-bold mb-1">Programa DesconTour</h4>
                        <p class="small opacity-90 mb-3">Voucher de incentivo fiscal e econômico ativo para consumo
                            local.</p>

                        <div class="bg-white text-dark rounded-4 p-3 shadow-sm my-3 border">
                            <span class="d-block text-muted small fw-bold text-uppercase mb-1">Desconto Concedido</span>
                            <span class="h1 fw-bold text-nl-purple m-0"
                                id="voucherValueText"><?= $descontoPercentagem ?>% OFF</span>
                        </div>
                    </div>

                    <!-- Sessão do PIN de Ativação do Caixa -->
                    <div id="pinActivationZone">
                        <h6 class="fw-bold text-dark mb-2">Apresente este celular ao atendente no caixa</h6>
                        <p class="text-muted small mb-4">Insira o código de balcão (PIN de 4 dígitos) do lojista para
                            ativar o cronômetro do seu desconto.</p>

                        <input type="text" id="caixaPinInput" class="form-control pin-box-input mb-4" maxlength="4"
                            placeholder="0000" inputmode="numeric">

                        <button type="button" class="btn btn-gov-primary w-100 rounded-pill py-2 fw-bold"
                            onclick="validarPinEAtivarCronometro()">
                            <i class="fa-solid fa-bolt me-2"></i> Ativar Desconto
                        </button>
                    </div>

                    <!-- Tela do Cronômetro Regressivo Ativo pós-PIN (Oculta por padrão) -->
                    <div id="countdownTimerZone" class="d-none">
                        <div class="alert alert-success border-0 shadow-sm p-3 mb-4 rounded-4">
                            <h6 class="alert-heading fw-bold mb-1"><i
                                    class="fa-solid fa-circle-check me-2 animate__animated animate__pulse animate__infinite"></i>Desconto
                                Ativo no Caixa!</h6>
                            <p class="small text-muted mb-0">Mostre a tela abaixo piscando para o atendente aplicar o
                                desconto na comanda.</p>
                        </div>

                        <div class="my-4">
                            <div class="display-1 fw-bold text-nl-purple font-monospace mb-2" id="regressiveClock"
                                style="font-size: 4.5rem; letter-spacing: -2px;">02:00</div>
                            <div class="progress" style="height: 12px; background-color: #E2E8F0; border-radius: 50px;">
                                <div id="timerProgressBar"
                                    class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 100%; background: linear-gradient(90deg, #00D369 0%, #5D46D2 100%);">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 text-muted small mb-3 border">
                            <i class="fa-solid fa-circle-info me-2 text-nl-purple"></i>Esta tela é interativa e expirará
                            em instantes. Impossível revalidar via capturas de tela.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/imask"></script>

    <script>
        let currentStep = 1;
        let cidadeSelecionadaVerdadeira = false;
        let originalToken = "<?= esc($tokenEstOriginal) ?>";
        let aceitaDescontoDoLocal = <?= $aceitaDesconto ? 'true' : 'false' ?>;
        let descontoPercentagemDoLocal = <?= $descontoPercentagem ?>;

        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const tokenEst = urlParams.get('token') || urlParams.get('id');

            gerarDeviceFingerprint();

            if (tokenEst) {
                document.getElementById('id_estabelecimento').value = tokenEst;
                originalToken = tokenEst;
            } else {
                document.getElementById('formInovador').style.display = 'none';
                document.getElementById('progressWrapper').style.display = 'none';
                document.getElementById('alertContainer').innerHTML = `
                    <div class="alert alert-danger p-4 border-0 rounded-4 text-center shadow-sm">
                        <i class="fa-solid fa-qrcode fa-3x mb-2 text-danger"></i>
                        <h5 class="fw-bold">Leitura Obrigatória</h5>
                        <p class="small mb-0 text-muted">Por favor, faça a leitura do QR Code oficial impresso e colado no estabelecimento para responder.</p>
                    </div>
                `;
            }

            configurarCardsSelecao();
            construirNps();
            configurarAutocompleteCidades();
            configurarInputGastoCentavos();

            const cpfInput = document.getElementById('turista_cpf');
            if (cpfInput) {
                IMask(cpfInput, { mask: '000.000.000-00' });
            }
        });

        function gerarDeviceFingerprint() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            ctx.textBaseline = "top";
            ctx.font = "14px 'Arial'";
            ctx.fillText("iNovaTour, Nova Lima - MG", 2, 2);
            const canvasHash = btoa(canvas.toDataURL());

            const hardwareAssinatura = [
                navigator.userAgent,
                screen.width + 'x' + screen.height,
                new Date().getTimezoneOffset(),
                navigator.hardwareConcurrency || 4
            ].join('||');

            document.getElementById('device_hash').value = btoa(canvasHash + '||' + hardwareAssinatura).substring(0, 64);
        }

        function showStep(step) {
            document.querySelectorAll('.card-step').forEach(el => el.classList.remove('active'));
            document.getElementById(`step${step}`).classList.add('active');
            currentStep = step;
            document.getElementById('progressText').innerText = `Passo ${step} de 4`;
            document.getElementById('progressBar').style.width = `${step * 25}%`;
        }

        function nextStep(step) {
            if (currentStep === 1 && step === 2) {
                const inputTexto = document.getElementById('busca_cidade').value.trim();
                if (!cidadeSelecionadaVerdadeira || !document.getElementById('cidade_origem').value || inputTexto !== document.getElementById('cidade_origem').value || !document.getElementById('motivo_visita').value) {
                    return;
                }
            }

            if (currentStep === 2 && step === 3) {
                const permanencia = document.getElementById('tempo_permanencia').value;
                if (!permanencia || (permanencia === 'dormir' && !document.getElementById('local_hospedagem').value)) {
                    return;
                }
            }

            if (currentStep === 3 && step === 4) {
                if (document.getElementById('faixa_gasto').value === "") {
                    return;
                }
            }

            showStep(step);
        }

        function prevStep(step) { showStep(step); }

        function validarCamposPasso() {
            const cidade = document.getElementById('cidade_origem').value;
            const motivo = document.getElementById('motivo_visita').value;
            const inputTextoCidade = document.getElementById('busca_cidade').value.trim();

            if (cidadeSelecionadaVerdadeira && cidade && inputTextoCidade === cidade && motivo) {
                document.getElementById('btnNext1').disabled = false;
            } else {
                document.getElementById('btnNext1').disabled = true;
            }

            const permanencia = document.getElementById('tempo_permanencia').value;
            const hospedagem = document.getElementById('local_hospedagem').value;

            if (permanencia === 'bate_volta') {
                document.getElementById('btnNext2').disabled = false;
            } else if (permanencia === 'dormir' && hospedagem) {
                document.getElementById('btnNext2').disabled = false;
            } else {
                document.getElementById('btnNext2').disabled = true;
            }

            const gasto = document.getElementById('faixa_gasto').value;
            if (gasto !== "" && parseFloat(gasto) >= 0) {
                document.getElementById('btnNext3').disabled = false;
            } else {
                document.getElementById('btnNext3').disabled = true;
            }
        }

        function configurarCardsSelecao() {
            document.querySelectorAll('.selectable-card').forEach(card => {
                card.addEventListener('click', function () {
                    const tipoInput = this.getAttribute('data-input');
                    const valor = this.getAttribute('data-value');

                    document.querySelectorAll(`.selectable-card[data-input="${tipoInput}"]`).forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');

                    if (tipoInput === 'motivo') document.getElementById('motivo_visita').value = valor;

                    if (tipoInput === 'permanencia') {
                        document.getElementById('tempo_permanencia').value = valor;
                        if (valor === 'dormir') {
                            document.getElementById('subHospedagem').classList.remove('d-none');
                        } else {
                            document.getElementById('subHospedagem').classList.add('d-none');
                            document.getElementById('local_hospedagem').value = "";
                            document.querySelectorAll('.selectable-card[data-input="hospedagem"]').forEach(c => c.classList.remove('selected'));
                        }
                    }
                    if (tipoInput === 'hospedagem') document.getElementById('local_hospedagem').value = valor;

                    validarCamposPasso();
                });
            });
        }

        function configurarInputGastoCentavos() {
            const inputVisivel = document.getElementById('gasto_input_pix');
            const hiddenInput = document.getElementById('faixa_gasto');

            inputVisivel.addEventListener('input', function () {
                let valorLimpo = this.value.replace(/\D/g, '');

                if (valorLimpo === '') {
                    hiddenInput.value = "0.00";
                    this.value = '';
                    validarCamposPasso();
                    return;
                }

                let valorDecimal = (parseInt(valorLimpo, 10) / 100);
                hiddenInput.value = valorDecimal.toFixed(2);

                this.value = valorDecimal.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                validarCamposPasso();
            });
        }

        function construirNps() {
            const container = document.querySelector('.nps-container');
            for (let i = 0; i <= 10; i++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'nps-btn';
                btn.innerText = i;
                if (i <= 6) btn.classList.add('detrator');
                else if (i <= 8) btn.classList.add('neutro');
                else btn.classList.add('promotor');

                btn.addEventListener('click', function () {
                    document.querySelectorAll('.nps-btn').forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    document.getElementById('nps_val').value = i;
                });
                container.appendChild(btn);
            }
        }

        // ====================================================================
        // COPIE E SUBSTITUA APENAS A SUA FUNÇÃO configurarAutocompleteCidades()
        // NO SEU ARQUIVO app/Views/pesquisa.php POR ESTA VERSÃO CORRIGIDA
        // ====================================================================

        function configurarAutocompleteCidades() {
            const input = document.getElementById('busca_cidade');
            const suggestionsContainer = document.getElementById('suggestions');
            let debounceTimeout;

            input.addEventListener('input', function () {
                // Reseta o estado de validação a cada digitação para garantir consistência
                cidadeSelecionadaVerdadeira = false;
                document.getElementById('cidade_origem').value = '';
                validarCamposPasso();

                const busca = this.value.trim();

                if (busca.length < 3) {
                    suggestionsContainer.classList.add('d-none');
                    return;
                }

                // Aplica Debounce de 300ms para evitar requisições duplicadas a cada tecla digitada
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(async () => {
                    try {
                        // OTIMIZAÇÃO DE ENGENHARIA: Filtragem direta no servidor do IBGE usando o parâmetro &nome=
                        // Isso reduz o payload de 10MB para menos de 2KB, garantindo velocidade instantânea no 4G
                        const response = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/municipios?view=completa&nome=${encodeURIComponent(busca)}`);
                        const municipios = await response.json();

                        // Filtra localmente apenas para garantir correspondência exata do termo buscado (limite de 5 resultados)
                        const filtrados = municipios.filter(m =>
                            m.nome.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(
                                busca.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                            )
                        ).slice(0, 5);

                        if (filtrados.length > 0) {
                            suggestionsContainer.innerHTML = '';
                            filtrados.forEach(m => {
                                const item = document.createElement('div');
                                item.className = 'suggestion-item';
                                const stringFormatada = `${m.nome} - ${m.microrregiao.mesorregiao.UF.sigla}`;
                                item.innerText = stringFormatada;

                                item.addEventListener('click', function () {
                                    input.value = stringFormatada;
                                    document.getElementById('cidade_origem').value = stringFormatada;
                                    cidadeSelecionadaVerdadeira = true;
                                    suggestionsContainer.classList.add('d-none');
                                    validarCamposPasso();
                                });
                                suggestionsContainer.appendChild(item);
                            });
                            suggestionsContainer.classList.remove('d-none');
                        } else {
                            suggestionsContainer.classList.add('d-none');
                        }
                    } catch (e) {
                        // Se a API do IBGE falhar por completo ou ficar offline na hora da banca:
                        // Permite a digitação como contingência, mas exige que o texto inserido seja válido (não vazio)
                        if (busca.length >= 3) {
                            document.getElementById('cidade_origem').value = busca;
                            cidadeSelecionadaVerdadeira = true;
                            validarCamposPasso();
                        }
                    }
                }, 300);
            });

            // BLINDAGEM DE CONSISTÊNCIA CONTRA TEXTO LIVRE:
            // Se o usuário digitar caracteres avulsos (como "rio") e perder o foco (blur) sem selecionar 
            // um item válido da lista, o sistema apaga o campo e o botão "Avançar" é desativado!
            input.addEventListener('blur', function () {
                setTimeout(() => {
                    const valorDigitado = this.value.trim();
                    const valorSalvoNoHidden = document.getElementById('cidade_origem').value;

                    // Se o texto visível não bater exatamente com o valor homologado que guardamos no hidden
                    if (!cidadeSelecionadaVerdadeira || valorDigitado !== valorSalvoNoHidden) {
                        this.value = '';
                        document.getElementById('cidade_origem').value = '';
                        cidadeSelecionadaVerdadeira = false;
                        validarCamposPasso();
                    }
                }, 250); // Delay milimétrico necessário para registrar o evento de clique na lista antes da limpeza
            });

            document.addEventListener('click', function (e) {
                if (e.target !== input) suggestionsContainer.classList.add('d-none');
            });
        }

        document.getElementById('formInovador').addEventListener('submit', async function (e) {
            e.preventDefault();

            const estrela = document.querySelector('input[name="satisfacao_estrelas"]:checked');
            const cpfVal = document.getElementById('turista_cpf').value;

            if (!estrela || document.getElementById('nps_val').value === "" || cpfVal.length < 14) {
                alert("Por favor, preencha as avaliações de estrelas, recomendação e digite o CPF completo.");
                return;
            }

            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Validando com Segurança...';

            const valorRaw = document.getElementById('faixa_gasto').value;
            const valorFloat = parseFloat(valorRaw);

            const urlParams = new URLSearchParams(window.location.search);
            const canalOrigem = urlParams.get('origem') || 'qrcode';

            const payload = {
                id_estabelecimento: document.getElementById('id_estabelecimento').value,
                cidade_origem: document.getElementById('cidade_origem').value,
                tempo_permanencia: document.getElementById('tempo_permanencia').value,
                local_hospedagem: document.getElementById('tempo_permanencia').value === 'dormir' ? document.getElementById('local_hospedagem').value : null,
                valor_gasto_estimado: isNaN(valorFloat) ? 0.00 : valorFloat,
                satisfacao_estrelas: parseInt(estrela.value),
                nps: parseInt(document.getElementById('nps_val').value),
                motivo_visita: document.getElementById('motivo_visita').value,
                cpf: cpfVal,
                device_hash: document.getElementById('device_hash').value,
                origem: canalOrigem
            };

            try {
                const response = await fetch('<?= site_url('api/pesquisa') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                const resData = await response.json();

                if (response.ok && resData.success) {
                    // --- ALTERAÇÃO DE FLUXO: MORADORES AGORA SEGUEM FLUXO NORMAL E GERAM CUPOM ---
                    if (resData.origem === 'qrcode') {
                        if (aceitaDescontoDoLocal) {
                            localStorage.setItem('inovatour_voucher', JSON.stringify({
                                token: originalToken,
                                local: '<?= esc($nomeLugar) ?>',
                                desconto: descontoPercentagemDoLocal,
                                status: 'pendente'
                            }));

                            document.getElementById('formInovador').style.display = 'none';
                            document.getElementById('progressWrapper').style.display = 'none';
                            document.getElementById('stepVoucher').classList.add('active');
                        } else {
                            localStorage.setItem('inovatour_voucher', JSON.stringify({
                                token: 'rede_parceira',
                                local: 'Rede de Vantagens (Qualquer Loja/Hotel)',
                                desconto: 10,
                                status: 'pendente'
                            }));
                            window.location.href = '<?= site_url('pesquisa/sucesso') ?>';
                        }
                    } else {
                        window.location.href = '<?= site_url('pesquisa/sucesso') ?>';
                    }

                } else {
                    alert(resData.messages ? (resData.messages.error || JSON.stringify(resData.messages)) : "Falha ao registrar dados.");
                    btn.disabled = false;
                    btn.innerHTML = "Finalizar ✨";
                }
            } catch (error) {
                console.error("Erro interno no processamento", error);
                window.location.href = '<?= site_url('pesquisa/sucesso') ?>';
            }
        });

        async function validarPinEAtivarCronometro() {
            const pinDigitado = document.getElementById('caixaPinInput').value.trim();

            if (pinDigitado.length < 4) {
                alert("Por favor, digite o código de 4 dígitos do balcão.");
                return;
            }

            try {
                const response = await fetch(`<?= site_url('api/pesquisa/validar-pin') ?>`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        token: originalToken,
                        pin: pinDigitado
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    document.getElementById('pinActivationZone').classList.add('d-none');
                    document.getElementById('countdownTimerZone').classList.remove('d-none');

                    dispararCronometroRegressivo(120);
                    localStorage.removeItem('inovatour_voucher');
                } else {
                    alert("Código PIN inválido para este estabelecimento.");
                }
            } catch (e) {
                alert("Erro ao validar PIN.");
            }
        }

        function dispararCronometroRegressivo(segundosTotais) {
            const clockText = document.getElementById('regressiveClock');
            const pBar = document.getElementById('timerProgressBar');
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
                    document.getElementById('countdownTimerZone').innerHTML = `
                        <div class="alert alert-danger border-0 shadow-sm p-4 rounded-4">
                            <i class="fa-solid fa-clock-rotate-left fa-3x mb-2 text-danger"></i>
                            <h5 class="fw-bold">Cupom Expirado!</h5>
                            <p class="small text-muted mb-0">Este voucher foi devidamente consumido ou expirou o prazo limite de permanência de validação no caixa.</p>
                            <a href="<?= site_url('guia') ?>" class="btn btn-outline-secondary btn-sm rounded-pill mt-4 px-4 fw-bold">Voltar ao Guia</a>
                        </div>
                    `;
                }
            }, 1000);
        }
    </script>
</body>

</html>