<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LojistaAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ajustado para 'role' e alterado o retorno para redirecionar para a tela de login
        if (!session()->get('isLogged') || session()->get('role') !== 'lojista') {
            session()->setFlashdata('error', 'Acesso negado. Por favor, faça login no painel.');
            return redirect()->to(base_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}