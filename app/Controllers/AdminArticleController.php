<?php

declare(strict_types=1);

namespace App\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Article Controller
|--------------------------------------------------------------------------
| This controller handles administrative functionalities.
| It includes methods for managing articles, create, edit, and delete articles.
*/

use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminArticleController extends Controller
{
    public function manage(): Response
    {
        $data = [];
        return $this->view('admin.articles.manage', $data);
    }

    public function newArticle(Request $request)
    {
        $data = [];
        return $this->view('admin.articles.create', $data);
    }
}