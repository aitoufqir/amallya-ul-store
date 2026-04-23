<?php
require __DIR__ . '/../config.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("DELETE FROM produits WHERE id=?");
$stmt->execute([$id]);

header("Location: gestion.php");
exit();
