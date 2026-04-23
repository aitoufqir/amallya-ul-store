<?php
require __DIR__ . '/config.php';

// 🔐 login protection
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 🔍 check id
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// 🛒 create panier if not exists
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if ($id > 0) {
    if (!isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id] = 0;
    }

    $_SESSION['panier'][$id]++;
}

// 🔁 redirect
header("Location: panier.php");
exit;
?>
