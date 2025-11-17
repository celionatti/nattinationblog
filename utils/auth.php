<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Basic Authentication Functions
|--------------------------------------------------------------------------
*/

use App\Models\User;

/**
 * Get the authenticated user's ID from session
 */
function auth_id(): ?int
{
    return $_SESSION['auth_user_id'] ?? null;
}

/**
 * Check if user is authenticated and valid
 */
function auth_check(): bool
{
    $userId = auth_id();
    
    if (!$userId) {
        return false;
    }

    // Check if user exists in database
    $user = User::find($userId);
    
    if (!$user) {
        return false;
    }

    // Check if user status is 'active' - access via attribute
    if ($user->status !== 'active') {
        return false;
    }

    return true;
}

/**
 * Get the authenticated user object
 */
function auth_user(): ?User
{
    $userId = auth_id();
    
    if (!$userId) {
        return null;
    }

    $user = User::find($userId);
    
    // Also check status when getting user
    if (!$user || $user->status !== 'active') {
        return null;
    }

    return $user;
}

/**
 * Check if no user is authenticated
 */
function auth_guest(): bool
{
    return !auth_check();
}