<?php

namespace App\Controllers;

use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface;

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
    public function index(): ResponseInterface
    {
        $data = [];
        return $this->view('home.index', $data);
    }
}
