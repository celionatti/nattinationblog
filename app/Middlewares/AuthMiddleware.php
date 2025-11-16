<?php

declare(strict_types=1);

namespace App\Middlewares;

/*
|--------------------------------------------------------------------------
| AuthMiddleware Class
|--------------------------------------------------------------------------
|
| Middleware class for handling authentication.
*/

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Plugs\Http\ResponseFactory;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Check if user is authenticated
        if (!isset($_SESSION['auth_user_id'])) {
            return ResponseFactory::json(['error' => 'Unauthorized'], 401);
        }

        // Add user to request
        $request = $request->withAttribute('auth_user_id', $_SESSION['auth_user_id']);

        return $handler->handle($request);
    }
}