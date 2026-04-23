<?php
require __DIR__ . '/config.php';
require __DIR__ . '/app/helpers/orders.php';
require __DIR__ . '/app/helpers/auth.php';
require __DIR__ . '/app/helpers/ui.php';

requireLogin();

$userId = (int) $_SESSION['user_id'];
$userName = $_SESSION['nom'] ?? '';
$commandes = [];

$hasDateCreation = columnExists($pdo, 'commandes', 'date_creation');
$hasIdClient = columnExists($pdo, 'commandes', 'id_client');
$hasNomClient = columnExists($pdo, 'commandes', 'nom_client');

$fields = ['id', 'telephone', 'adresse', 'total', 'produits_ids'];
if ($hasNomClient) {
    $fields[] = 'nom_client';
}
if ($hasIdClient) {
    $fields[] = 'id_client';
}
if ($hasDateCreation) {
    $fields[] = 'date_creation';
}

$sql = "SELECT " . implode(', ', $fields) . " FROM commandes";
$conditions = [];
$params = [];

if ($hasIdClient) {
    $conditions[] = "id_client = ?";
    $params[] = $userId;
}
if ($hasNomClient && $userName !== '') {
    $conditions[] = "nom_client = ?";
    $params[] = $userName;
}
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' OR ', $conditions);
}
$sql .= $hasDateCreation ? " ORDER BY date_creation DESC" : " ORDER BY id DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $commandes = [];
}

if (empty($commandes) && !empty($_SESSION['recent_orders'])) {
    $commandes = $_SESSION['recent_orders'];
}
?>
<?php renderPageStart('Mes Commandes - amallya_ul'); ?>
<?php renderTopNav(
    [
        ['href' => 'index.php', 'label' => 'Accueil'],
        ['href' => 'index.php#products', 'label' => 'Produits'],
        ['href' => 'mes_commandes.php', 'label' => 'Mes Commandes'],
        ['href' => 'panier.php', 'label' => 'Panier'],
    ],
    [
        ['href' => 'panier.php', 'label' => 'Panier', 'class' => 'btn-outline'],
        ['href' => 'logout.php', 'label' => 'Deconnexion', 'class' => 'btn-outline'],
    ]
); ?>

<div class="container orders-container">
    <h1 class="page-title">Mes Commandes</h1>

    <?php if (empty($commandes)): ?>
        <div class="no-orders">
            <div class="empty-icon">•</div>
            <h3>Aucune commande pour le moment</h3>
            <p>Votre historique de commandes apparaitra ici apres votre premier achat.</p>
            <a href="index.php#products" class="btn-primary">Voir les produits</a>
        </div>
    <?php else: ?>
        <div class="orders-grid">
            <?php foreach ($commandes as $index => $cmd): ?>
                <?php $productNames = formatProductNames($pdo, $cmd['produits_ids'] ?? ''); ?>
                <article class="order-card" style="transition-delay: <?= min($index * 80, 480) ?>ms;">
                    <div class="order-header">
                        <div class="order-id-badge">
                            <span class="badge-label">Commande</span>
                            <span class="badge-number">#<?= str_pad((string) ($cmd['id'] ?? ($index + 1)), 6, "0", STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="order-date">
                            <?php if (!empty($cmd['date_creation'])): ?>
                                <?= date('d/m/Y H:i', strtotime($cmd['date_creation'])) ?>
                            <?php elseif (!empty($cmd['created_at_label'])): ?>
                                <?= htmlspecialchars($cmd['created_at_label']) ?>
                            <?php else: ?>
                                Enregistrée
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="order-content">
                        <div class="order-info">
                            <h4><?= htmlspecialchars($cmd['nom_client'] ?? $userName ?: 'Client') ?></h4>
                            <p><strong>Téléphone :</strong> <?= htmlspecialchars($cmd['telephone'] ?? 'Non renseigné') ?></p>
                            <p><strong>Adresse :</strong> <?= htmlspecialchars($cmd['adresse'] ?? 'Non renseignée') ?></p>
                            <?php if (!empty($productNames)): ?>
                                <p><strong>Articles :</strong> <?= htmlspecialchars(implode(', ', $productNames)) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="order-total">
                            <span class="total-label">Montant total</span>
                            <span class="total-amount"><?= number_format((float) ($cmd['total'] ?? 0), 2, '.', ' ') ?></span>
                            <span class="total-currency">DH</span>
                        </div>
                    </div>

                    <div class="order-footer">
                        <div class="status-badge status-completed">
                            <span class="status-dot">●</span> Validée
                        </div>
                        <details class="order-details">
                            <summary>Voir détails</summary>
                            <div class="details-content">
                                <p><strong>Produits IDs :</strong> <?= htmlspecialchars($cmd['produits_ids'] ?? 'Aucun détail') ?></p>
                                <p>Votre commande est enregistrée et sera traitée prochainement.</p>
                            </div>
                        </details>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php renderPageEnd(); ?>
