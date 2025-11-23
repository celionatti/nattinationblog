<?php

declare(strict_types=1);

namespace App\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Events Controller
|--------------------------------------------------------------------------
| This controller handles administrative functionalities for events.
*/

use Exception;
use App\Models\User;
use Plugs\View\ErrorMessage;
use Plugs\Utils\FlashMessage;
use Plugs\Paginator\Paginator;
use Plugs\Upload\FileUploader;
use Plugs\Upload\UploadedFile;
use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminEventController extends Controller
{
    private $uploader;

    public function onConstruct()
    {
        $this->uploader = new FileUploader();
        $this->uploader->usePublicFolder("uploads/articles");
        $this->uploader->imagesOnly(5 * 1024 * 1024);
        $this->uploader->disableSecurityFiles();
    }

    public function manage(Request $request)
    {
        try {
            $events = [];
            $paginator = [];

            return $this->view('admin.events.manage', [
                'events' => $events,
                'paginator' => $paginator,
                'page_title' => 'Manage Events'
            ]);
        } catch(Exception $e) {
            FlashMessage::error('Failed to load articles: ' . $e->getMessage());
            return $this->view('admin.events.manage', [
                'events' => [],
                'paginator' => null,
                'page_title' => 'Manage Events'
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            $events = [];

            return $this->view('admin.events.create', [
                'events' => $events,
                'categories' => [],
                'page_title' => 'Create New Event'
            ]);
        } catch(Exception $e) {
            FlashMessage::error('Failed to load articles: ' . $e->getMessage());
            return $this->view('admin.events.manage', [
                'events' => [],
                'categories' => [],
                'page_title' => 'Create New Event'
            ]);
        }
    }
}