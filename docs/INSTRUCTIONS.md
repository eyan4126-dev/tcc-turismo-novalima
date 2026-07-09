# Instruções para Executar o Projeto (InovaTour)

Este projeto é desenvolvido utilizando o framework PHP **CodeIgniter 4**. Siga os passos abaixo para configurá-lo e executá-lo no seu ambiente local.

## 1. Pré-requisitos

Certifique-se de ter os seguintes componentes instalados em sua máquina:
- **PHP** 8.2 ou superior (com as extensões `intl`, `mbstring`, `json`, `mysqlnd` habilitadas).
- **Composer** (gerenciador de dependências do PHP).
- **Servidor MySQL** (ou MariaDB).

## 2. Instalação de Dependências

Abra o terminal na raiz do projeto (`c:\laragon\www\yan-project`) e execute o comando abaixo para instalar as bibliotecas necessárias:

```bash
composer install
```

## 3. Configuração do Ambiente (.env)

O projeto já contém um arquivo `.env` na raiz. 
Abra o arquivo `.env` e configure as credenciais de acesso ao seu banco de dados local. Procure pelas seguintes variáveis na seção de banco de dados e ajuste conforme necessário:

```env
database.default.hostname = localhost
database.default.database = nome_do_banco
database.default.username = seu_usuario
database.default.password = sua_senha
database.default.DBDriver = MySQLi
```

## 4. Importação do Banco de Dados

Há um dump do banco de dados na raiz do projeto. 
1. Crie um banco de dados no seu servidor MySQL.
2. Importe o arquivo `inovatour-database.sql` para dentro deste novo banco de dados.
*(Dica: Você pode usar ferramentas como phpMyAdmin, DBeaver, ou importar diretamente via linha de comando do MySQL).*

## 5. Executando o Servidor Local

Após configurar o `.env` e importar o banco de dados, você pode iniciar o servidor embutido do CodeIgniter usando o `spark`. No terminal, ainda na raiz do projeto, execute:

```bash
php spark serve
```

O servidor será iniciado. Acesse o projeto no seu navegador, geralmente através do endereço:
[http://localhost:8080](http://localhost:8080)

## 6. Solução de Problemas (Troubleshooting)

### Erro: The openssl extension is required for SSL/TLS protection

Se ao executar o `composer install` você receber este erro, significa que a extensão OpenSSL do PHP está desabilitada.

**Solução recomendada (Habilitar OpenSSL):**
1. Abra o arquivo `php.ini` da sua instalação do PHP.
2. Procure pela linha `;extension=openssl` (ou `;extension=php_openssl.dll`).
3. Remova o ponto e vírgula (`;`) no início da linha para ativá-la.
4. Salve o arquivo e tente rodar o `composer install` novamente.

**Solução alternativa (Não recomendada):**
Caso não consiga habilitar a extensão, você pode desativar temporariamente a verificação de TLS no Composer, mas isso remove a segurança da conexão:
```bash
composer config -g disable-tls true
```
