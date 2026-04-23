<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../app/helpers/catalog.php';
require __DIR__ . '/../app/helpers/ui.php';

$produits = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$totalProduits = count($produits);
$totalValeur = 0;
$categories = [];

foreach ($produits as $produit) {
    $totalValeur += (float) $produit['prix'];
    $cat = $produit['categorie'] ?? 'autres';
    $categories[$cat] = ($categories[$cat] ?? 0) + 1;
}
?>
<?php renderPageStart('Dashboard Admin', '..'); ?>
<?php renderTopNav(
    [
        ['href' => 'admin/gestion.php', 'label' => 'Dashboard'],
        ['href' => 'admin/ajouter.php', 'label' => 'Ajouter'],
        ['href' => 'index.php', 'label' => 'Boutique'],
    ],
    [['href' => 'admin/ajouter.php', 'label' => 'Ajouter un produit', 'class' => 'btn-primary']],
    '..'
); ?>

<div class="admin-container">
    <div class="section-intro">
        <p class="eyebrow">Dashboard</p>
        <h1 class="page-title">Gestion du catalogue</h1>
        <p class="section-copy">Ajoute, modifie et suis la valeur globale de tous les articles du site.</p>
    </div>

    <div class="admin-stats">
        <div class="admin-stat">
            <span>Total produits</span>
            <strong><?= $totalProduits ?></strong>
        </div>
        <div class="admin-stat">
            <span>Valeur catalogue</span>
            <strong><?= number_format($totalValeur, 2, '.', ' ') ?> DH</strong>
        </div>
        <div class="admin-stat">
            <span>Catégories actives</span>
            <strong><?= count($categories) ?></strong>
        </div>
    </div>

    <?php if (!empty($categories)): ?>
        <section class="catalog-summary" style="margin-bottom: 24px;">
            <?php foreach ($categories as $category => $count): ?>
                <div class="summary-card">
                    <span class="summary-label"><?= htmlspecialchars(ucfirst($category)) ?></span>
                    <strong><?= $count ?> article<?= $count > 1 ? 's' : '' ?></strong>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <div class="table-container">
        <table class="pro-table">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= (int) $p['id'] ?></td>
                    <td><img src="../<?= htmlspecialchars(adminMainImage($p['image'] ?? '')) ?>" class="product-img"></td>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><span class="category-chip"><?= htmlspecialchars($p['categorie']) ?></span></td>
                    <td class="price"><?= number_format((float) $p['prix'], 2, '.', ' ') ?> DH</td>
                    <td>
                        <a href="modifier.php?id=<?= (int) $p['id'] ?>" class="btn-edit">Modifier</a>
                        <a href="supprimer.php?id=<?= (int) $p['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php renderPageEnd('..'); ?>
