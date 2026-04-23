<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../app/helpers/catalog.php';

header('Content-Type: application/json; charset=UTF-8');

$cat = isset($_GET['cat']) ? trim((string) $_GET['cat']) : '';

if ($cat !== '') {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie = ? ORDER BY id DESC");
    $stmt->execute([$cat]);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $produits = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}

$payload = array_map(static function (array $product): array {
    $images = getProductImages($product['image'] ?? '');
    $extras = getProductExtras($product['nom'] ?? '', $product['categorie'] ?? '');

    return [
        'id' => (int) ($product['id'] ?? 0),
        'name' => (string) ($product['nom'] ?? ''),
        'description' => !empty($product['description']) ? mb_strimwidth((string) $product['description'], 0, 88, '...') : '',
        'category' => (string) ($product['categorie'] ?? 'Mode'),
        'price' => (float) ($product['prix'] ?? 0),
        'views' => !empty($product['visites']) ? (int) $product['visites'] : rand(40, 480),
        'image' => $images[0],
        'size' => (string) $extras['taille'],
        'brand' => (string) $extras['marque'],
        'addToCartUrl' => 'ajouter_panier.php?id=' . (int) ($product['id'] ?? 0),
    ];
}, $produits);

echo json_encode([
    'category' => $cat,
    'count' => count($payload),
    'products' => $payload,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
