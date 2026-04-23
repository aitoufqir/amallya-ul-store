<?php
require __DIR__ . '/config.php';
require __DIR__ . '/app/helpers/catalog.php';
require __DIR__ . '/app/helpers/ui.php';

$cat = isset($_GET['cat']) ? trim($_GET['cat']) : '';
$bestCategories = [
    ['label' => 'Robes', 'copy' => 'Pieces elegantes et fluides.', 'value' => 'robes'],
    ['label' => 'Vestes', 'copy' => 'Looks forts pour tous les jours.', 'value' => 'vestes'],
    ['label' => 'Chaussures', 'copy' => 'Finitions propres et style net.', 'value' => 'chaussures'],
    ['label' => 'Jeans', 'copy' => 'Coupes modernes et faciles.', 'value' => 'jeans'],
];

if ($cat !== '') {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie = ? ORDER BY id DESC");
    $stmt->execute([$cat]);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $produits = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}

$serializedProducts = array_map(static function (array $product): array {
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

$bootstrap = [
    'initialCategory' => $cat,
    'products' => $serializedProducts,
    'categories' => $bestCategories,
];
?>
<?php renderPageStart('Produits - amallya_ul'); ?>
<?php renderTopNav(
    [
        ['href' => 'index.php', 'label' => 'Accueil'],
        ['href' => 'categories.php', 'label' => 'Categories'],
        ['href' => 'produits.php', 'label' => 'Produits'],
        ['href' => 'admin/gestion.php', 'label' => 'Admin'],
    ],
    [['href' => 'panier.php', 'label' => 'Panier', 'class' => 'btn-outline']]
); ?>

<div id="catalogApp"></div>
<script>
window.__CATALOG_BOOTSTRAP__ = <?= json_encode($bootstrap, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
</script>
<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
<script type="text/babel" src="js/catalog-app.js"></script>

<?php renderPageEnd(); ?>
