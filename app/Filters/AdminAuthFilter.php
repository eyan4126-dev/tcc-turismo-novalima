<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLogged') || session()->get('role_usuario') !== 'admin') {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['status' => 'error', 'error' => 'Acesso negado. Apenas administradores da prefeitura.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
