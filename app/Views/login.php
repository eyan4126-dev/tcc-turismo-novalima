<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hub de Turismo</title>
    <link rel="stylesheet" href="<?= base_url('style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('login.css'); ?>">

    <style>
        /* Variáveis de cores fornecidas */
        :root {
            --deep-blue: #0D2137;
            --royal-blue: #1A3F6F;
            --steel-blue: #3A6A9A;
            --light-blue: #6A9CC0;
            --ice-blue: #A8C8E0;
            --bg-main: #F7FAFC;
            --white: #FFFFFF;
            --error-red: #E53E3E;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--deep-blue);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: var(--white);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(13, 33, 55, 0.1);
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: var(--royal-blue);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--steel-blue);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--deep-blue);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--ice-blue);
            border-radius: 8px;
            font-size: 1rem;
            color: var(--deep-blue);
            background-color: var(--bg-main);
            transition: border-color 0.2s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--steel-blue);
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 38px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--steel-blue);
            font-size: 0.85rem;
        }

        .alert-danger {
            background-color: #FED7D7;
            border: 1px solid var(--error-red);
            color: #9B2C2C;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background-color: var(--royal-blue);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-login:hover {
            background-color: var(--deep-blue);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: var(--steel-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-header">
            <h1>iNovaTour</h1>
            <p>Painel de Gestão e Monitoramento</p>
        </div>

        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert-danger">
                <?= implode('<br>', (array) session()->getFlashdata('errors')) ?>
            </div>
        <?php elseif (session()->getFlashdata('error')) : ?>
            <div class="alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php elseif (service('request')->getGet('erro') === 'pendente') : ?>
            <div class="alert-danger">
                Seu cadastro de lojista ainda está em análise pela prefeitura.
            </div>
        <?php endif; ?>

        <form action="<?= base_url('api/login'); ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">E-mail Corporativo</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" required placeholder="exemplo@empresa.com">
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Sua senha segura">
                <button type="button" class="password-toggle" id="togglePassword">Mostrar</button>
            </div>

            <button type="submit" class="btn-login">Entrar no Sistema</button>
        </form>

        <div class="login-footer">
            <p>É um novo lojista? <a href="<?= base_url('cadastro'); ?>">Solicitar Pré-Cadastro</a></p>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('senha');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.textContent = 'Ocultar';
            } else {
                passwordInput.type = 'password';
                this.textContent = 'Mostrar';
            }
        });
    </script>
</body>

</html>