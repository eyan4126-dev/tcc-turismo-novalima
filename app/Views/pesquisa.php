<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iNovaTour - Pesquisa Turística</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #F8F9FA;
            /* Fundo Governamental Ultraclaro */
            min-height: 100vh;
            color: #1A1D20;
            /* Texto Alfa em Grafite de Alto Contraste */
            font-family: 'Inter', sans-serif;
        }

        /* Faixa com o degradê arco-íris característico da identidade visual de Nova Lima */
        .top-identity-bar {
            height: 12px;
            background: linear-gradient(90deg, #FF5722 0%, #7B1FA2 35%, #0288D1 70%, #2E7D32 100%);
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
        }

        /* Container da Logo do iNovaTour */
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

        .progress {
            height: 8px;
            background-color: #E2E8F0;
            border-radius: 4px;
        }

        .progress-bar {
            background: linear-gradient(90deg, #0288D1 0%, #0091EA 100%);
            /* Azul digital da prefeitura */
        }

        /* Estilo dos Blocos de Serviço Flutuantes (Portal 156 Style) */
        .card-step {
            background: #FFFFFF;
            color: #1A1D20;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            display: none;
            transition: all 0.3s ease;
        }

        .card-step.active {
            display: block;
            animation: fadeIn 0.4s ease-in-out;
        }

        /* Títulos de seção baseados na paleta do município */
        .text-primary-govt {
            color: #0288D1 !important;
        }

        /* Cards de Seleção Visual (Gamificação Clean) */
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
            border-color: #0288D1;
            background-color: #F0F9FF;
        }

        .selectable-card.selected {
            border-color: #0288D1;
            background-color: #E0F2FE;
            box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.2);
        }

        .selectable-card i {
            font-size: 1.75rem;
            color: #0288D1;
            /* Ícones em Azul Digital */
            margin-bottom: 0.5rem;
        }

        /* Estilos da Régua de Gasto Arrastável */
        .price-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0288D1;
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
            background: #0288D1;
            cursor: pointer;
            transition: transform 0.1s ease;
        }

        .custom-slider::-webkit-slider-thumb:hover {
            transform: scale(1.15);
        }

        /* Régua do NPS Interativa Clean */
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

        /* Estados ativos do NPS com tons institucionais nítidos */
        .nps-btn.detrator.selected {
            background-color: #DC3545;
            color: #FFFFFF;
            border-color: #BD2130;
        }

        .nps-btn.neutro.selected {
            background-color: #FFC107;
            color: #1A1D20;
            border-color: #D39E00;
        }

        .nps-btn.promotor.selected {
            background-color: #00C853;
            /* Verde Vibrante dos botões municipais */
            color: #FFFFFF;
            border-color: #00A644;
        }

        /* Estrelas de Evaluation */
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
            /* Amarelo ouro de avaliação */
        }

        /* Autocomplete da Cidade (Clean Mode) */
        .autocomplete-suggestions {
            position: absolute;
            z-index: 1000;
            background: #FFFFFF;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            margin-top: 2px;
        }

        .suggestion-item {
            padding: 12px;
            cursor: pointer;
            border-bottom: 1px solid #F1F5F9;
            color: #4A5568;
        }

        .suggestion-item:hover {
            background-color: #E0F2FE;
            color: #0288D1;
        }

        /* Customizações de Botões de Ação Avançar/Voltar */
        .btn-gov-primary {
            background-color: #0288D1;
            border-color: #0288D1;
            color: #FFFFFF;
        }

        .btn-gov-primary:hover {
            background-color: #0071B1;
            border-color: #0071B1;
            color: #FFFFFF;
        }

        /* Call To Action Principal (Verde Vibrante Oficial) */
        .btn-gov-success {
            background-color: #00C853 !important;
            border-color: #00C853 !important;
            color: #FFFFFF !important;
        }

        .btn-gov-success:hover {
            background-color: #00A644 !important;
            border-color: #00A644 !important;
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
    ?>

    <div class="top-identity-bar"></div>

    <div class="container py-5 mt-3">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <div class="brand-logo-container">
                    <img src="public/logo.png" alt="iNovaTour Logo">
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between text-sm mb-1">
                        <span class="text-muted small fw-medium">Pesquisa de Fluxo Turístico</span>
                        <span id="progressText" class="fw-bold text-primary-govt small">Passo 1 de 4</span>
                    </div>
                    <div class="progress">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 25%;"></div>
                    </div>
                </div>

                <div class="alertContainer" id="alertContainer"></div>

                <form id="formInovador">
                    <input type="hidden" id="id_estabelecimento" name="id_estabelecimento">

                    <div class="card-step active p-4" id="step1">
                        <h4 class="fw-bold mb-3">
                            <i class="fa-solid fa-map-location-dot text-primary-govt me-2"></i>Boas-vindas ao
                            <strong><?= esc($nomeLugar) ?></strong>! Vamos começar?
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

                    <div class="card-step p-4" id="step3">
                        <h4 class="fw-bold mb-4"><i class="fa-solid fa-wallet text-primary-govt me-2"></i>Planejamento
                            Financeiro</h4>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small mb-3">
                                Qual o seu gasto médio estimado aqui no(a)
                                <strong><?= esc($nomeLugar) ?></strong>?
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

                            <input type="hidden" id="faixa_gasto" name="valor_gasto_estimado" value="0.00"><input
                                type="hidden" id="faixa_gasto" value="0.00">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4 fw-bold rounded-pill"
                                onclick="prevStep(2)"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</button>
                            <button type="button" class="btn btn-gov-primary px-4 fw-bold rounded-pill" id="btnNext3"
                                onclick="nextStep(4)" disabled>Avançar <i
                                    class="fa-solid fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

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

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4 fw-bold rounded-pill"
                                onclick="prevStep(3)"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</button>
                            <button type="submit" class="btn btn-gov-success px-5 fw-bold rounded-pill"
                                id="btnSubmit">Finalizar ✨</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let cidadeSelecionadaVerdadeira = false;

        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const tokenEst = urlParams.get('token') || urlParams.get('id');

            if (tokenEst) {
                document.getElementById('id_estabelecimento').value = tokenEst;
            }
            else {
                document.getElementById('formInovador').style.display = 'none';
                document.getElementById('alertContainer').innerHTML = `
                    <div class="alert alert-danger p-4 border-0 rounded-4 text-center">
                        <i class="fa-solid fa-qrcode fa-3x mb-2 text-danger"></i>
                        <h5>QR Code Inválido ou Ausente</h5>
                        <p class="small mb-0">Por favor, faça a leitura do QR Code oficial impresso no estabelecimento.</p>
                    </div>
                `;
            }

            configurarCardsSelecao();
            construirNps();
            configurarAutocompleteCidades();
            configurarInputGastoCentavos();
        });

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

        // Função de máscara monetária dinâmica para tratar centavos à direita
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

                // Transforma a string numérica em um valor com 2 casas decimais (ex: "150" vira 1.50)
                let valorDecimal = (parseInt(valorLimpo, 10) / 100);
                hiddenInput.value = valorDecimal.toFixed(2);

                // Formata visualmente para exibição: "1.250,50"
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

        function configurarAutocompleteCidades() {
            const input = document.getElementById('busca_cidade');
            const suggestionsContainer = document.getElementById('suggestions');

            input.addEventListener('input', async function () {
                cidadeSelecionadaVerdadeira = false;
                const busca = this.value.trim();
                validarCamposPasso();

                if (busca.length < 3) { suggestionsContainer.classList.add('d-none'); return; }

                try {
                    const response = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/municipios?view=completa`);
                    const municipios = await response.json();
                    const filtrados = municipios.filter(m => m.nome.toLowerCase().includes(busca.toLowerCase())).slice(0, 5);

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
                    cidadeSelecionadaVerdadeira = true;
                    document.getElementById('cidade_origem').value = busca;
                    validarCamposPasso();
                }
            });

            document.addEventListener('click', function (e) {
                if (e.target !== input) suggestionsContainer.classList.add('d-none');
            });
        }

        document.getElementById('formInovador').addEventListener('submit', async function (e) {
            e.preventDefault();

            const estrela = document.querySelector('input[name="satisfacao_estrelas"]:checked');
            if (!estrela || document.getElementById('nps_val').value === "") {
                alert("Por favor, preencha as avaliações de estrelas e a nota de recomendação.");
                return;
            }

            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerText = "Registrando...";

            // Forçamos a captura do valor do elemento de forma limpa e direta
            const valorRaw = document.getElementById('faixa_gasto').value;
            const valorFloat = parseFloat(valorRaw);

            const payload = {
                id_estabelecimento: document.getElementById('id_estabelecimento').value,
                cidade_origem: document.getElementById('cidade_origem').value,
                tempo_permanencia: document.getElementById('tempo_permanencia').value,
                local_hospedagem: document.getElementById('tempo_permanencia').value === 'dormir' ? document.getElementById('local_hospedagem').value : null,

                // Enviamos estritamente a chave que a validação do Back-end exige
                valor_gasto_estimado: isNaN(valorFloat) ? 0.00 : valorFloat,

                satisfacao_estrelas: parseInt(estrela.value),
                nps: parseInt(document.getElementById('nps_val').value),
                motivo_visita: document.getElementById('motivo_visita').value
            };

            try {
                // Como o back-end exige JSON, mantemos a estrutura original limpa
                const response = await fetch('<?= base_url('api/pesquisa') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (response.ok) {
                    document.getElementById('alertContainer').innerHTML = `<div class="alert alert-success p-4 border-0 rounded-4 text-center"><i class="fa-solid fa-circle-check fa-3x mb-2 text-success"></i><h5>Experiência Registrada!</h5><p class="small mb-0">Obrigado por colaborar com o monitoramento turístico.</p></div>`;
                    document.getElementById('formInovador').innerHTML = '';
                } else {
                    const erroDetalhado = await response.json();
                    console.error("Erros do Back-end:", erroDetalhado);
                    alert("Erro de validação: " + (erroDetalhado.messages ? JSON.stringify(erroDetalhado.messages) : "Verifique os dados"));
                    btn.disabled = false;
                    btn.innerText = "Finalizar ✨";
                }
            } catch (error) {
                alert("Erro de conexão ao salvar os dados.");
                btn.disabled = false;
                btn.innerText = "Finalizar ✨";
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>