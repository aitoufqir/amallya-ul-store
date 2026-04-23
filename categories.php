<?php
require __DIR__ . '/config.php';
require __DIR__ . '/app/helpers/ui.php';

$categories = [
    ['label' => 'Robes', 'copy' => 'Pieces elegantes et fluides pour un style feminin.', 'value' => 'robes'],
    ['label' => 'Vestes', 'copy' => 'Looks forts et coupes modernes pour tous les jours.', 'value' => 'vestes'],
    ['label' => 'Chaussures', 'copy' => 'Finitions propres pour completer la tenue.', 'value' => 'chaussures'],
    ['label' => 'Jeans', 'copy' => 'Denim facile a porter avec des coupes actuelles.', 'value' => 'jeans'],
];
?>
<?php renderPageStart('Categories - amallya_ul'); ?>
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
    <section class="page-banner reveal">
        <div class="banner-grid">
            <div class="banner-copy">
                <p class="eyebrow">Categories</p>
                <h1>Choisis ton univers mode.</h1>
                <p class="section-copy">Entre dans une categorie claire, large et bien visible avant d'ouvrir la page produits correspondante.</p>
            </div>
            <div class="banner-panel">
                <span class="panel-tag">Vue rapide</span>
                <div class="banner-stats">
                    <div class="banner-stat"><strong>4</strong><span>familles mode</span></div>
                    <div class="banner-stat"><strong>1</strong><span>clic pour ouvrir</span></div>
                    <div class="banner-stat"><strong>100%</strong><span>lecture simple</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="category-section reveal" id="categories">
        <div class="category-grid category-showcase">
            <?php foreach ($categories as $category): ?>
                <a href="produits.php?cat=<?= urlencode($category['value']) ?>" class="category-card">
                    <strong><?= htmlspecialchars($category['label']) ?></strong>
                    <span><?= htmlspecialchars($category['copy']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php renderPageEnd('', true); ?>
