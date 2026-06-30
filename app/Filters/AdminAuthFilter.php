<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Se não estiver logado ou se não for admin, barra
        if (!session()->get('isLogged') || session()->get('role') !== 'admin') {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Acesso negado. Apenas administradores da prefeitura.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
