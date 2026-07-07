<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= esc($estabelecimento['razao_social']) ?> - Turismo Nova Lima
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: sans-serif;
        }

        .card-turista {
            border-radius: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .badge-tipo {
            background-color: #6f42c1;
            color: white;
            border-radius: 50px;
            padding: 6px 16px;
        }
    </style>
</head>

<body>

    <div class="container py-5 text-center" style="max-width: 500px;">
        <div class="mb-4">
            <span style="font-size: 64px;">✅</span>
        </div>

        <div class="card card-turista p-4 bg-white text-center">
            <span class="badge badge-tipo align-self-center mb-3">
                Local Credenciado
            </span>

            <h2 class="fw-bold text-dark mb-2">
                <?= esc($estabelecimento['razao_social']) ?>
            </h2>
            <p class="text-muted mb-4">Setor: <strong>
                    <?= ucfirst(esc($estabelecimento['setor'])) ?>
                </strong></p>

            <hr>

            <div class="p-3 bg-light rounded-3 my-3">
                <p class="small text-secondary mb-1">Contato do Estabelecimento</p>
                <p class="fw-semibold text-dark mb-0">
                    <?= esc($estabelecimento['telefone']) ?>
                </p>
            </div>

            <p class="text-success small mt-2">
                ✨ Você está em um ponto turístico oficial regulamentado de Nova Lima.
            </p>
        </div>
    </div>

</body>

</html>