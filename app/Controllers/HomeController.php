<?php

namespace App\Controllers;

use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * HomeController
 * 
 * @package App\Controllers
 */
class HomeController extends Controller
{
    /**
     * Display Home Page
     */
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $data = [
            'auth' => $request->getAttribute('auth_user_id'),
        ];
        return $this->view('pages.index', $data);
    }
}
