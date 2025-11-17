<?php

declare(strict_types=1);

namespace App\Middlewares;

/*
|--------------------------------------------------------------------------
| GuestMiddleware Class
|--------------------------------------------------------------------------
|
| Middleware class for handling authentication.
*/

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Plugs\Http\ResponseFactory;

class GuestMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Check if user is authenticated
        if (isset($_SESSION['auth_user_id'])) {
            if ($this->isApiRequest($request)) {
                return ResponseFactory::json(['error' => 'Already authenticated', 'message' => 'You are already logged in'], 403);
            }

            // Redirect to login for web requests
            return ResponseFactory::redirect('/');
        }

        // Add user to request
        // $request = $request->withAttribute('auth_user_id', $_SESSION['auth_user_id']);

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
}