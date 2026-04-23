<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../app/helpers/catalog.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id=?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

if (!$produit) {
    header("Location: gestion.php");
    exit();
}

if(isset($_POST['modifier'])){

    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $categorie = $_POST['categorie'];

    // Ensure images folder exists
    $imagesDir = '../images/';
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0755, true);
    }

    $existingImages = [];
    if (!empty($produit['image'])) {
        $decoded = json_decode($produit['image'], true);
        if ($decoded && is_array($decoded)) {
            $existingImages = $decoded;
        } else {
            $existingImages = [$produit['image']];
        }
    }

    // Add new uploads
    for ($i = 1; $i <= 4; $i++) {
        $field = 'image' . $i;
        if (!empty($_FILES[$field]['name'])) {
            $name = basename($_FILES[$field]['name']);
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $safeName = uniqid('img_') . '.' . $ext;
            $target = '../images/' . $safeName;
            if (move_uploaded_file($_FILES[$field]['tmp_name'], $target)) {
                $existingImages[] = 'images/' . $safeName;
            }
        }
    }

    if (empty($existingImages)) {
        $existingImages[] = 'images/IMAGE.jfif';
    }

    $imageForDb = count($existingImages) === 1 ? $existingImages[0] : json_encode($existingImages);

    $stmt = $pdo->prepare("UPDATE produits SET nom=?, description=?, prix=?, categorie=?, image=? WHERE id=?");
    $stmt->execute([$nom, $description, $prix, $categorie, $imageForDb, $id]);

    header("Location: gestion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="admin-header">
    <div class="admin-logo">MyShop Admin</div>
    <div>
        <a href="gestion.php" class="btn-outline">Dashboard</a>
        <a href="../index.php" class="btn-primary">Voir Boutique</a>
    </div>
</header>

<div class="container">
    <h2 class="page-title">Modifier Produit</h2>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nom" value="<?= $produit['nom'] ?>" required>
            <textarea name="description" required><?= $produit['description'] ?></textarea>
            <input type="number" step="0.01" name="prix" value="<?= $produit['prix'] ?>" required>
            <select name="categorie" required>
                <option value="">Choisir catégorie</option>
                <option value="robes" <?= $produit['categorie'] == 'robes' ? 'selected' : '' ?>>Robes</option>
                <option value="combinaisons" <?= $produit['categorie'] == 'combinaisons' ? 'selected' : '' ?>>Combinaisons</option>
                <option value="vestes" <?= $produit['categorie'] == 'vestes' ? 'selected' : '' ?>>Vestes</option>
                <option value="tops" <?= $produit['categorie'] == 'tops' ? 'selected' : '' ?>>Tops</option>
                <option value="jeans" <?= $produit['categorie'] == 'jeans' ? 'selected' : '' ?>>Jeans</option>
                <option value="accessoires" <?= $produit['categorie'] == 'accessoires' ? 'selected' : '' ?>>Accessoires</option>
            </select>

            <?php
                $pImages = [];
                if (!empty($produit['image'])) {
                    $dec = json_decode($produit['image'], true);
                    if ($dec && is_array($dec)) {
                        $pImages = $dec;
                    } else {
                        $pImages = [$produit['image']];
                    }
                }
            ?>
            <?php if (!empty($pImages)): ?>
                <div class="thumb-row">
                    <?php foreach($pImages as $pi): ?>
                        <img src="../<?= htmlspecialchars(normalizeImagePath((string) $pi)) ?>" class="thumbnail" onerror="this.onerror=null;this.src='../images/pic1.png';">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <label>Ajouter/ remplacer images (1 à 4)</label>
            <input type="file" name="image1" accept="image/*">
            <input type="file" name="image2" accept="image/*">
            <input type="file" name="image3" accept="image/*">
            <input type="file" name="image4" accept="image/*">

            <button type="submit" name="modifier">Modifier</button>
        </form>
    </div>
</div>

</body>
</html>
