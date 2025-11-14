<?php

declare(strict_types=1);

namespace App\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Controller
|--------------------------------------------------------------------------
| This controller handles administrative functionalities.
| It includes methods for managing users, content, and site settings.
*/

use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface;

/**
 * AdminController
 * 
 * @package App\Controllers
 */

class AdminController extends Controller
{
    public function adminDashboard()
    {
        $data = [];
        return $this->view('admin.dashboard', $data);
    }
}