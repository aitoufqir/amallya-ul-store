<?php
require __DIR__ . '/config.php';
require __DIR__ . '/app/helpers/catalog.php';
require __DIR__ . '/app/helpers/ui.php';

$produits = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$featuredProducts = array_slice($produits, 0, 3);
$totalProducts = count($produits);
$categories = ['robes', 'vestes', 'chaussures', 'jeans'];
?>
<?php renderPageStart('Accueil - amallya_ul'); ?>
<?php renderTopNav(
    [
        ['href' => 'index.php', 'label' => 'Accueil'],
        ['href' => 'categories.php', 'label' => 'Categories'],
        ['href' => 'produits.php', 'label' => 'Produits'],
        ['href' => 'admin/gestion.php', 'label' => 'Admin'],
    ],
    [['href' => 'panier.php', 'label' => 'Panier', 'class' => 'btn-outline']]
); ?>

<main class="page-shell wide">
    <section class="hero reveal">
        <div class="hero-copy">
            <p class="eyebrow">Seconde main premium</p>
            <h1>Une boutique plus claire, page par page.</h1>
            <p class="section-copy">Navigue simplement entre l'accueil, les categories et le catalogue produits, sans tout melanger dans une seule page.</p>
            <div class="hero-actions">
                <a href="produits.php" class="btn-primary">Voir les produits</a>
                <a href="categories.php" class="btn-outline">Voir les categories</a>
            </div>
        </div>
        <div class="hero-side">
            <div class="hero-card">
                <span class="panel-tag">amallya_ul</span>
                <h2>Des pieces choisies avec soin</h2>
                <p>Un accueil simple pour entrer dans la boutique, puis chaque partie dans sa propre page.</p>
                <div class="badge-row">
                    <span><?= $totalProducts ?> produits</span>
                    <span><?= count($categories) ?> categories</span>
                    <span>Boutique active</span>
                </div>
            </div>
            <?php foreach ($featuredProducts as $featured): ?>
                <?php $image = getProductImages($featured['image'] ?? '')[0]; ?>
                <article class="mini-card">
                    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($featured['nom']) ?>">
                    <div>
                        <strong><?= htmlspecialchars($featured['nom']) ?></strong>
                        <span><?= number_format((float) $featured['prix'], 0, '.', ' ') ?> DH</span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="stats-grid reveal">
        <article class="stat-card"><strong><?= $totalProducts ?></strong><span>Produits</span></article>
        <article class="stat-card"><strong><?= count($categories) ?></strong><span>Categories</span></article>
        <article class="stat-card"><strong>3</strong><span>Pages principales</span></article>
    </section>

    <section class="page-banner reveal">
        <div class="banner-grid">
            <div class="banner-copy">
                <p class="eyebrow">Boutique Visuelle</p>
                <h1>Une interface plus large, plus vivante.</h1>
                <p class="section-copy">Chaque zone prend maintenant mieux la largeur de l'ecran pour que la boutique respire et que la navigation soit plus agreable.</p>
            </div>
            <div class="banner-panel">
                <span class="panel-tag">Navigation rapide</span>
                <div class="banner-stats">
                    <div class="banner-stat"><strong>01</strong><span>Accueil clair</span></div>
                    <div class="banner-stat"><strong>02</strong><span>Categories visibles</span></div>
                    <div class="banner-stat"><strong>03</strong><span>Catalogue large</span></div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php renderPageEnd('', true); ?>
