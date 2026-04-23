<?php
require __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$isValid = true;
$messageTitle = "Commande validée";
$messageText = "Merci pour votre achat. Votre commande a bien été enregistrée.";
$commandeId = null;
$total = 0;

if (!isset($_POST['nom'], $_POST['telephone'], $_POST['adresse'])) {
    $isValid = false;
    $messageTitle = "Formulaire incomplet";
    $messageText = "Merci de remplir toutes les informations avant de confirmer votre commande.";
}

if ($isValid && (!isset($_SESSION['panier']) || empty($_SESSION['panier']))) {
    $isValid = false;
    $messageTitle = "Panier vide";
    $messageText = "Ajoute d'abord des produits au panier avant de valider la commande.";
}

if ($isValid) {
    $nom = trim($_POST['nom']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    $lineItems = [];

    foreach ($_SESSION['panier'] as $productId => $quantity) {
        $quantity = max(1, (int) $quantity);
        $stmt = $pdo->prepare("SELECT prix FROM produits WHERE id = ?");
        $stmt->execute([(int) $productId]);
        $price = (float) ($stmt->fetchColumn() ?: 0);

        if ($price <= 0) {
            continue;
        }

        $total += $price * $quantity;
        $lineItems[] = ((int) $productId) . 'x' . $quantity;
    }

    $produitsIds = implode(",", $lineItems);

    $hasIdClient = false;
    try {
        $check = $pdo->prepare("SHOW COLUMNS FROM commandes LIKE ?");
        $check->execute(['id_client']);
        $hasIdClient = (bool) $check->fetch();
    } catch (Throwable $e) {
        $hasIdClient = false;
    }

    if ($hasIdClient) {
        $stmt = $pdo->prepare("
            INSERT INTO commandes (id_client, nom_client, telephone, adresse, total, produits_ids)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([(int) $_SESSION['user_id'], $nom, $telephone, $adresse, $total, $produitsIds]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO commandes (nom_client, telephone, adresse, total, produits_ids)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nom, $telephone, $adresse, $total, $produitsIds]);
    }

    $commandeId = $pdo->lastInsertId();


    if (!isset($_SESSION['recent_orders'])) {
        $_SESSION['recent_orders'] = [];
    }

    array_unshift($_SESSION['recent_orders'], [
        'id' => $commandeId,
        'nom_client' => $nom,
        'telephone' => $telephone,
        'adresse' => $adresse,
        'total' => $total,
        'produits_ids' => $produitsIds,
        'created_at_label' => date('d/m/Y H:i')
    ]);

    $_SESSION['recent_orders'] = array_slice($_SESSION['recent_orders'], 0, 20);
    unset($_SESSION['panier']);
} else {
    $total = 0;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isValid ? 'Commande validée' : 'Commande impossible' ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="success-page">

<header class="top-nav">
    <div class="nav-left">
        <a href="index.php" class="logo">amallya_ul</a>
    </div>
    <div class="nav-right">
        <a href="index.php" class="btn-outline">Retour boutique</a>
    </div>
</header>

<div class="message-box">
    <div class="success-icon"><?= $isValid ? '✓' : '!' ?></div>
    <h2><?= htmlspecialchars($messageTitle) ?></h2>
    <p><?= htmlspecialchars($messageText) ?></p>

    <?php if ($isValid && $commandeId): ?>
        <div class="order-id-container">
            <p>Numéro de commande</p>
            <h3 class="order-id">#<?= str_pad((string) $commandeId, 6, "0", STR_PAD_LEFT) ?></h3>
        </div>
    
        <div class="price-display">
            <p>Montant total</p>
            <div class="price-counter"><?= number_format($total, 2, '.', ' ') ?> DH</div>
        </div>

        <a href="mes_commandes.php" class="btn-primary">Voir mes commandes</a>
    <?php else: ?>
        <div class="price-display">
            <p>Votre panier doit contenir au moins un produit pour continuer.</p>
        </div>
        <a href="panier.php" class="btn-primary">Retour au panier</a>
    <?php endif; ?>
</div>

</body>
</html>
