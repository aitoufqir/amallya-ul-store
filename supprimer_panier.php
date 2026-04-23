<?php
session_start();

$id = (int) ($_GET['id'] ?? 0);
$action = $_GET['action'] ?? 'remove';

if (isset($_SESSION['panier'][$id])) {
    if ($action === 'increase') {
        $_SESSION['panier'][$id]++;
    } elseif ($action === 'decrease') {
        $_SESSION['panier'][$id]--;

        if ($_SESSION['panier'][$id] <= 0) {
            unset($_SESSION['panier'][$id]);
        }
    } else {
        unset($_SESSION['panier'][$id]);
    }
}

header("Location: panier.php");
exit;
?>
