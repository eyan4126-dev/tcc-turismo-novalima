<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Cross-Origin Resource Sharing (CORS) Configuration
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
 */
class Cors extends BaseConfig
{
    /**
     * The default CORS configuration.
     */
    public array $default = [
        /**
         * Origins autorizadas a consumir a API.
         * Usamos '*' para desenvolvimento local para o Waron não ter dor de cabeça.
         */
        'allowedOrigins' => ['*'],

        'allowedOriginsPatterns' => [],

        /**
         * CRÍTICO PARA O TCC: Definido como true para permitir que a sessão nativa 
         * do PHP (Session Cookies) funcione entre o front-end e o back-end.
         */
        'supportsCredentials' => true,

        /**
         * Cabeçalhos permitidos nas requisições AJAX.
         * Liberamos todos os padrões e os de autenticação.
         */
        'allowedHeaders' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept', 'Origin'],

        'exposedHeaders' => [],

        /**
         * Métodos HTTP que a API aceita (Conforme nossas rotas do Épico 4)
         */
        'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

        /**
         * Tempo em segundos que o navegador pode cachear a resposta do preflight (OPTIONS)
         */
        'maxAge' => 7200,
    ];
}
