<?php

declare(strict_types=1);

namespace App\Middlewares;

/*
|--------------------------------------------------------------------------
| AdminMiddleware Class
|--------------------------------------------------------------------------
|
| Middleware class for handling authentication.
*/

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Plugs\Http\ResponseFactory;

class AdminMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // First check if user is authenticated
        if (!auth_check()) {
            if ($this->isApiRequest($request)) {
                return ResponseFactory::json(['error' => 'Unauthorized'], 401);
            }
            return ResponseFactory::redirect('/login');
        }

        $user = auth_user();

        // Check if user is admin - choose one method below:

        // Method 1: Check role column
        if (property_exists($user, 'role') && $user->role !== 'admin') {
            return $this->denyAccess($request);
        }

        // Method 2: Check is_admin flag (uncomment if you use this)
        // if (property_exists($user, 'is_admin') && !$user->is_admin) {
        //     return $this->denyAccess($request);
        // }

        // Method 3: Check status and role (more strict)
        if ($user->status !== 'active' || $user->role !== 'admin') {
            return $this->denyAccess($request);
        }

        return $handler->handle($request);
    }

    /**
     * Check if the request is an API request
     */
    private function isApiRequest(ServerRequestInterface $request): bool
    {
        $path = $request->getUri()->getPath();
        $acceptHeader = $request->getHeaderLine('Accept');

        // Check if path starts with /api or accepts JSON
        return str_starts_with($path, '/api') ||
            str_contains($acceptHeader, 'application/json');
    }

     private function denyAccess(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->isApiRequest($request)) {
            return ResponseFactory::json([
                'error' => 'Forbidden',
                'message' => 'Admin access required'
            ], 403);
        }
        
        // Redirect to home or show error page for web requests
        return ResponseFactory::redirect('/');
    }
}