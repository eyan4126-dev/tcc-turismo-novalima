<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LojistaAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Se não estiver logado ou se não for lojista, barra
        if (!session()->get('isLogged') || session()->get('role') !== 'lojista') {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Acesso negado. Área restrita ao lojista.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
