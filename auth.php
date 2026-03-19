<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

function current_user(): ?array
{
    $user = $_SESSION['user'] ?? null;

    // Handle stale/corrupted session values from older app states.
    if (!is_array($user)) {
        unset($_SESSION['user']);
        return null;
    }

    if (!isset($user['id'], $user['username'], $user['full_name'])) {
        unset($_SESSION['user']);
        return null;
    }

    return [
        'id' => (int) $user['id'],
        'username' => (string) $user['username'],
        'full_name' => (string) $user['full_name'],
    ];
}

function require_login(): void
{
    if (!current_user()) {
        set_flash('error', 'Please log in first.');
        redirect('login.php');
    }
}
