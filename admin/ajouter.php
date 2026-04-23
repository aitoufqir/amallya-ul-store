<?php
require __DIR__ . '/../config.php';

if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = (float) $_POST['prix'];
    $categorie = trim($_POST['categorie']);

    $imagesDir = '../images/';
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0755, true);
    }

    $uploaded = [];
    for ($i = 1; $i <= 4; $i++) {
        $field = 'image' . $i;
        if (!empty($_FILES[$field]['name'])) {
            $name = basename($_FILES[$field]['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $safeName = uniqid('img_', true) . '.' . $ext;
            $target = '../images/' . $safeName;
            if (move_uploaded_file($_FILES[$field]['tmp_name'], $target)) {
                $uploaded[] = 'images/' . $safeName;
            }
        }
    }

    if (empty($uploaded)) {
        $uploaded[] = 'images/pic1.png';
    }

    $imageForDb = count($uploaded) === 1 ? $uploaded[0] : json_encode($uploaded);

    $stmt = $pdo->prepare("INSERT INTO produits(nom,description,prix,categorie,image) VALUES(?,?,?,?,?)");
    $stmt->execute([$nom, $description, $prix, $categorie, $imageForDb]);

    header("Location: gestion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Produit</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="admin-header">
    <div class="admin-logo">amallya_ul Admin</div>
    <div class="nav-right">
        <a href="gestion.php" class="btn-outline">Dashboard</a>
        <a href="../index.php" class="btn-primary">Voir Boutique</a>
    </div>
</header>

<div class="container">
    <div class="section-intro">
        <p class="eyebrow">Nouveau produit</p>
        <h1 class="page-title">Ajouter un article</h1>
        <p class="section-copy">Ajoute rapidement de nouveaux articles avec prix, catégorie et jusqu'à 4 photos.</p>
    </div>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nom" placeholder="Nom du produit" required>
            <textarea name="description" placeholder="Description courte et claire" required></textarea>
            <input type="number" step="0.01" min="100" max="3000" name="prix" placeholder="Prix en DH" required>
            <select name="categorie" required>
                <option value="">Choisir catégorie</option>
                <option value="robes">Robes</option>
                <option value="combinaisons">Combinaisons</option>
                <option value="vestes">Vestes</option>
                <option value="tops">Tops</option>
                <option value="jeans">Jeans</option>
                <option value="chaussures">Chaussures</option>
                <option value="accessoires">Accessoires</option>
            </select>
            <label>Ajouter 1 à 4 photos</label>
            <input type="file" name="image1" accept="image/*">
            <input type="file" name="image2" accept="image/*">
            <input type="file" name="image3" accept="image/*">
            <input type="file" name="image4" accept="image/*">
            <button type="submit" name="ajouter">Enregistrer le produit</button>
        </form>
    </div>
</div>

</body>
</html>
