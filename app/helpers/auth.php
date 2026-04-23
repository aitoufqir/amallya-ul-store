<?php

declare(strict_types=1);

function requireLogin(string $redirect = 'login.php'): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . $redirect);
        exit;
    }
}
