<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LojistaAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLogged') || session()->get('role_usuario') !== 'lojista') {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['status' => 'error', 'error' => 'Acesso negado. Área restrita ao lojista ativo.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
