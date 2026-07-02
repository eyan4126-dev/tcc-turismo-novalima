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
        'allowedOrigins'         => [],
        'allowedOriginsPatterns' => ['#^http://localhost(:[0-9]+)?$#'], // Captura localhost com ou sem porta
        'allowedHeaders'         => ['*'],
        'allowedMethods'         => ['*'],
        'exposedHeaders'         => [],
        'maxAge'                 => 7200,
        'supportsCredentials'    => false, // <-- Mude para false aqui para matar o erro de vez no ambiente local
    ];
}
