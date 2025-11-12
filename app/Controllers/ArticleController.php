<?php

declare(strict_types=1);

namespace App\Controllers;

use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface;

/**
 * ArticleController
 * 
 * @package App\Controllers
 */

class ArticleController extends Controller
{
    /**
     * Display Article Page
     */
    public function index(): ResponseInterface
    {
        $data = [];
        return $this->view('pages.articles', $data);
    }
}