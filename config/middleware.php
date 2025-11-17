<?php

declare(strict_types=1);

use App\Middlewares\AdminMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;

/*
|--------------------------------------------------------------------------
| Middleware Aliases
|--------------------------------------------------------------------------
|
| Here you can define aliases for your middleware classes.
| This allows you to use short names like 'auth' instead of the full class name.
*/

return [
    'aliases' => [
        'auth' => AuthMiddleware::class,
        'guest' => GuestMiddleware::class,
        'admin' => AdminMiddleware::class,
        // Add more aliases here
        // 'admin' => AdminMiddleware::class,
        // 'guest' => GuestMiddleware::class,
        // 'verified' => VerifiedMiddleware::class,
    ],
];