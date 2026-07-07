<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ajustado para 'role' para coincidir com o que é salvo no AuthController
        if (!session()->get('isLogged') || session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Acesso negado. Área restrita a administradores.');
            return redirect()->to(base_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}