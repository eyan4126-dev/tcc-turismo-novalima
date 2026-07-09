<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Lima - Turismo Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --nl-purple: #5D46D2;
            --nl-purple-dark: #402cb3;
            --nl-magenta: #E6007E;
            --nl-green-neon: #00D369;
            --nl-green-neon-hover: #00b358;
            --nl-bg-light: #F8F9FA;
            --nl-text-dark: #1A1A1A;
            --nl-gradient: linear-gradient(90deg, #FF5500 0%, #E6007E 25%, #5D46D2 50%, #0099FF 75%, #00D369 100%);
        }

        body {
            background-color: var(--nl-bg-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 12px;
            background: var(--nl-gradient);
            z-index: 10;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(93, 70, 210, 0.1);
            overflow: hidden;
            border: none;
        }

        .brand-logo-container {
            width: 130px;
            height: 130px;
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
            width: 60%;
            height: auto;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .brand-section {
            background-color: var(--nl-purple);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem 2rem;
            position: relative;
        }

        .brand-section::after {
            content: "";
            position: absolute;
            right: -50px;
            bottom: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .nav-pills {
            background-color: #ECE9FC;
            padding: 4px;
            border-radius: 30px;
        }

        .nav-pills .nav-link {
            color: var(--nl-purple);
            font-weight: 600;
            border-radius: 26px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .nav-pills .nav-link.active {
            background-color: var(--nl-purple);
            color: #ffffff;
        }

        .form-label {
            color: #4A4A4A;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .form-control,
        .form-select {
            border-radius: 50px;
            padding: 0.65rem 1.2rem;
            border: 1px solid #D1D1D1;
            background-color: #ffffff;
            color: var(--nl-text-dark);
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--nl-purple);
            box-shadow: 0 0 0 3px rgba(93, 70, 210, 0.15);
        }

        .btn-primary {
            background-color: var(--nl-green-neon);
            border: none;
            border-radius: 50px;
            padding: 0.75rem;
            font-weight: 700;
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--nl-green-neon-hover);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .text-nl-purple {
            color: var(--nl-purple) !important;
        }

        .prefeitura-logo-text {
            font-weight: 800;
            font-size: 1.4rem;
            line-height: 1;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="card login-card">
                    <div class="row g-0">

                        <div class="col-md-5 brand-section text-center">
                            <div class="brand-logo-container">
                                <img src="<?= site_url('../public/new-logo2.png') ?>" alt="iNovaTour Logo">
                            </div>
                            <div class="prefeitura-logo-text mb-1">
                                iNova<br><span style="font-weight: 400;">Tour</span>
                            </div>
                            <p class="small opacity-75 mb-0 px-3 mt-2" style="font-size: 0.8rem;">
                                O futuro mora aqui
                            </p>
                            <div class="mt-4 pt-2" style="border-top: 1px solid rgba(255,255,255,0.2); width: 60%;">
                            </div>
                            <span class="mt-3 small fw-bold tracking-wider"
                                style="font-size: 0.75rem; letter-spacing: 1px;">NOVA LIMA</span>
                        </div>

                        <div class="col-md-7 p-4 p-lg-5 d-flex flex-column justify-content-center">

                            <ul class="nav nav-pills nav-justified mb-4" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link <?= (session()->getFlashdata('active_tab') !== 'cadastro') ? 'active' : '' ?>"
                                        id="tab-login" data-bs-toggle="pill" data-bs-target="#content-login"
                                        type="button" role="tab">
                                        Entrar
                                    </button>
                                    </td>
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link <?= (session()->getFlashdata('active_tab') === 'cadastro') ? 'active' : '' ?>"
                                        id="tab-cadastro" data-bs-toggle="pill" data-bs-target="#content-cadastro"
                                        type="button" role="tab">
                                        Cadastrar estabelecimento
                                    </button>
                                    </td>
                            </ul>

                            <div id="alertContainer">
                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger alert-dismissible fade show small fw-bold mt-2"
                                        role="alert" style="border-radius:30px; padding-left:20px;">
                                        <?= session()->getFlashdata('error') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (session()->getFlashdata('success')): ?>
                                    <div class="alert alert-success alert-dismissible fade show small fw-bold mt-2"
                                        role="alert" style="border-radius:30px; padding-left:20px;">
                                        <?= session()->getFlashdata('success') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="tab-content" id="pills-tabContent">

                                <div class="tab-pane fade <?= (session()->getFlashdata('active_tab') !== 'cadastro') ? 'show active' : '' ?>"
                                    id="content-login" role="tabpanel">
                                    <h5 class="fw-bold mb-1 text-nl-purple">Área do Prestador</h5>
                                    <p class="text-muted small mb-4">Acesse o painel de inteligência turística.</p>

                                    <form id="formLogin" method="POST" action="<?= site_url('api/login') ?>">
                                        <div class="mb-3">
                                            <label class="form-label">E-mail corporativo</label>
                                            <input type="email" class="form-control" id="login_email" name="email"
                                                required placeholder="Seu e-mail" value="<?= old('email') ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Senha</label>
                                            <input type="password" class="form-control" id="login_senha" name="senha"
                                                required placeholder="Sua senha">
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Entrar no sistema</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade <?= (session()->getFlashdata('active_tab') === 'cadastro') ? 'show active' : '' ?>"
                                    id="content-cadastro" role="tabpanel">
                                    <h5 class="fw-bold mb-1 text-nl-purple">Solicitar credenciamento</h5>
                                    <p class="text-muted small mb-4">Cadastre seu negócio para receber o QR Code oficial
                                        de turismo.</p>

                                    <form id="formCadastro" method="POST"
                                        action="<?= site_url('api/registrar-lojista') ?>">
                                        <div class="row g-2">
                                            <div class="col-md-12 mb-2">
                                                <label class="form-label">Nome do responsável</label>
                                                <input type="text" class="form-control" id="cad_nome"
                                                    name="nome_responsavel" required placeholder="Nome completo"
                                                    value="<?= old('nome_responsavel') ?>">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">E-mail para login</label>
                                                <input type="email" class="form-control" id="cad_email" name="email"
                                                    required placeholder="exemplo@empresa.com"
                                                    value="<?= old('email') ?>">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">Senha de acesso</label>
                                                <input type="password" class="form-control" id="cad_senha" name="senha"
                                                    required placeholder="Mínimo 6 dígitos">
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label class="form-label">Razão Social / Nome do Evento</label>
                                                <input type="text" class="form-control" id="cad_razao"
                                                    name="razao_social" required placeholder="Nome comercial"
                                                    value="<?= old('razao_social') ?>">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">CNPJ</label>
                                                <input type="text" class="form-control" id="cad_cnpj" name="cnpj"
                                                    required placeholder="00.000.000/0001-00"
                                                    value="<?= old('cnpj') ?>">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">Telefone de contato</label>
                                                <input type="text" class="form-control" id="cad_telefone"
                                                    name="telefone" required placeholder="(31) 99999-0000"
                                                    value="<?= old('telefone') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Setor de atuação</label>
                                                <select class="form-select" id="cad_setor" name="setor" required>
                                                    <option value="" disabled <?= !old('setor') ? 'selected' : '' ?>>
                                                        Selecione...</option>
                                                    <option value="hospedagem" <?= old('setor') == 'hospedagem' ? 'selected' : '' ?>>Hospedagem (Hotel / Pousada)</option>
                                                    <option value="alimentacao_comercio"
                                                        <?= old('setor') == 'alimentacao_comercio' ? 'selected' : '' ?>>
                                                        Alimentação / Comércio Local</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tipo de operação</label>
                                                <select class="form-select" id="cad_tipo" name="tipo" required>
                                                    <option value="fixo" <?= old('tipo') == 'fixo' ? 'selected' : '' ?>>
                                                        Estabelecimento Fixo</option>
                                                    <option value="evento" <?= old('tipo') == 'evento' ? 'selected' : '' ?>>Evento Temporário</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Enviar solicitação</button>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/imask"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // 1. Aplica a máscara de CNPJ em tempo real na tela
            const cnpjInput = document.getElementById('cad_cnpj');
            if (cnpjInput) {
                IMask(cnpjInput, {
                    mask: '00.000.000/0000-00'
                });
            }

            // 2. Aplica a máscara dinâmica de Telefone (Fixo/Celular)
            const telInput = document.getElementById('cad_telefone');
            if (telInput) {
                IMask(telInput, {
                    mask: [
                        { mask: '(00) 0000-0000' },
                        { mask: '(00) 00000-0000' }
                    ]
                });
            }
        });
    </script>
</body>

</html>