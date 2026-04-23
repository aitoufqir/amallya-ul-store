<?php
require __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$items = [];
$total = 0;

foreach ($_SESSION['panier'] as $id => $quantity) {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([(int) $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        continue;
    }

    $product['quantity'] = max(1, (int) $quantity);
    $product['subtotal'] = (float) $product['prix'] * $product['quantity'];
    $items[] = $product;
    $total += $product['subtotal'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - amallya_ul</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="top-nav">
    <div class="nav-left">
        <a href="index.php" class="logo">amallya_ul</a>
        <nav class="main-nav">
            <a href="index.php">Accueil</a>
            <a href="index.php#products">Produits</a>
            <a href="mes_commandes.php">Mes Commandes</a>
            <a href="panier.php">Panier</a>
        </nav>
    </div>
    <div class="nav-right">
        <a href="logout.php" class="btn-outline">Déconnexion</a>
    </div>
</header>

<div class="container">
    <div class="section-intro">
        <p class="eyebrow">Votre sélection</p>
        <h1 class="page-title">Panier</h1>
        <p class="section-copy">Retrouve ici tous les articles choisis avant validation de la commande.</p>
    </div>

    <?php if (empty($items)): ?>
        <div class="no-orders">
            <div class="empty-icon">•</div>
            <h3>Votre panier est vide</h3>
            <p>Ajoute quelques produits pour continuer votre commande.</p>
            <a href="index.php#products" class="btn-primary">Découvrir les produits</a>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($items as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nom']) ?></td>
                        <td><?= number_format((float) $p['prix'], 2, '.', ' ') ?> DH</td>
                        <td>
                            <div class="qty-controls">
                                <a href="supprimer_panier.php?id=<?= (int) $p['id'] ?>&action=decrease" class="btn-outline">-</a>
                                <span class="qty-value"><?= (int) $p['quantity'] ?></span>
                                <a href="supprimer_panier.php?id=<?= (int) $p['id'] ?>&action=increase" class="btn-outline">+</a>
                            </div>
                        </td>
                        <td><?= number_format((float) $p['subtotal'], 2, '.', ' ') ?> DH</td>
                        <td>
                            <a href="supprimer_panier.php?id=<?= (int) $p['id'] ?>" class="btn-delete">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="total-box">Total : <?= number_format($total, 2, '.', ' ') ?> DH</div>

        <div class="form-container">
            <h2>Confirmer la commande</h2>
            <form action="commander.php" method="POST">
                <input type="text" name="nom" placeholder="Votre nom" required>
                <input type="text" name="telephone" placeholder="Téléphone" required>
                <input type="text" name="adresse" placeholder="Adresse" required>
                <button type="submit">Valider la commande</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
