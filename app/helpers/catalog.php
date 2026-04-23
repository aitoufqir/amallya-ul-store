<?php

declare(strict_types=1);

function normalizeImagePath(?string $path): string
{
    if (!$path) {
        return 'images/pic10.png';
    }

    $path = trim($path);
    if (preg_match('#^[A-Za-z]:\\\\#', $path) || preg_match('#^[A-Za-z]:/#', $path)) {
        $path = basename(str_replace('\\', '/', $path));
    }

    $path = str_replace('\\', '/', $path);
    $path = str_replace(' ', '%20', $path);

    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }

    if ($path !== '' && $path[0] === '/') {
        $path = ltrim($path, '/');
    }

    if ($path === '') {
        return 'images/pic10.png';
    }

    if (!preg_match('#^(images/|https?://)#', $path)) {
        $path = 'images/' . ltrim($path, '/');
    }

    return $path;
}

function getProductImages(?string $imageField): array
{
    $fallbacks = [
        'images/pic1.png',
        'images/pic2.png',
        'images/pic3.png',
        'images/pic4.png',
    ];

    $images = [];
    if ($imageField && ($decoded = json_decode($imageField, true)) && is_array($decoded)) {
        $images = $decoded;
    } elseif (!empty($imageField)) {
        $images = [$imageField];
    }

    $images = array_values(array_filter(array_map('normalizeImagePath', $images)));

    if (empty($images)) {
        return $fallbacks;
    }

    return array_slice(array_unique(array_merge($images, $fallbacks)), 0, 4);
}

function getProductExtras(string $name, string $category): array
{
    $catalog = [
        'Robe Lina Rose' => ['taille' => 'M', 'marque' => 'Zara', 'qualite' => 'Tres bon etat'],
        'Robe Sofia Chic' => ['taille' => 'L', 'marque' => 'Mango', 'qualite' => 'Excellent etat'],
        'Robe Yasmina Fleurie' => ['taille' => 'M', 'marque' => 'H&M', 'qualite' => 'Tres bon etat'],
        'Robe Salma Beige' => ['taille' => 'XL', 'marque' => 'Stradivarius', 'qualite' => 'Excellent etat'],
        'Veste Nora Classique' => ['taille' => 'L', 'marque' => 'Bershka', 'qualite' => 'Tres bon etat'],
        'Blazer Amal Noir' => ['taille' => 'M', 'marque' => 'Mango', 'qualite' => 'Premium'],
        'Veste Imane Denim' => ['taille' => 'XL', 'marque' => 'Pull&Bear', 'qualite' => 'Tres bon etat'],
        'Veste Sara Camel' => ['taille' => 'L', 'marque' => 'Zara', 'qualite' => 'Excellent etat'],
        'Jean Aya Bleu' => ['taille' => 'M', 'marque' => 'Levi\'s', 'qualite' => 'Bon etat'],
        'Jean Ines Noir' => ['taille' => 'M', 'marque' => 'Bershka', 'qualite' => 'Tres bon etat'],
        'Jean Kenza Large' => ['taille' => 'XL', 'marque' => 'Stradivarius', 'qualite' => 'Excellent etat'],
        'Jean Maha Taille Haute' => ['taille' => 'L', 'marque' => 'Mango', 'qualite' => 'Tres bon etat'],
        'Robe Dounia Ivoire' => ['taille' => 'M', 'marque' => 'Zara', 'qualite' => 'Excellent etat'],
        'Robe Nada Satin' => ['taille' => 'S', 'marque' => 'H&M', 'qualite' => 'Premium'],
        'Veste Hiba Creme' => ['taille' => 'XL', 'marque' => 'Reserved', 'qualite' => 'Excellent etat'],
        'Veste Ghita Soft' => ['taille' => 'XXL', 'marque' => 'Mango', 'qualite' => 'Premium'],
        'Escarpins Leila Nude' => ['taille' => '38', 'marque' => 'Aldo', 'qualite' => 'Tres bon etat'],
        'Bottines Maryam Noires' => ['taille' => '39', 'marque' => 'Bata', 'qualite' => 'Excellent etat'],
        'Sandales Hana Dorees' => ['taille' => '37', 'marque' => 'Zara', 'qualite' => 'Bon etat'],
        'Mocassins Rita Urbains' => ['taille' => '40', 'marque' => 'Parfois', 'qualite' => 'Tres bon etat'],
        'Doudoune Ikram Noire' => ['taille' => 'XL', 'marque' => 'Uniqlo', 'qualite' => 'Excellent etat'],
        'Pull Wiam Raye' => ['taille' => 'L', 'marque' => 'H&M', 'qualite' => 'Tres bon etat'],
        'Manteau Manel Beige' => ['taille' => 'M', 'marque' => 'Mango', 'qualite' => 'Premium'],
        'Jean Asma Coupe Large' => ['taille' => 'L', 'marque' => 'Levi\'s', 'qualite' => 'Excellent etat'],
        'Bomber Farah Bordeaux' => ['taille' => 'XL', 'marque' => 'Pull&Bear', 'qualite' => 'Tres bon etat'],
        'T-shirt Zineb Noir' => ['taille' => 'M', 'marque' => 'Cotton Club', 'qualite' => 'Bon etat'],
    ];

    return $catalog[$name] ?? ['taille' => 'M', 'marque' => ucfirst($category ?: 'Mode'), 'qualite' => 'Tres bon etat'];
}

function adminMainImage(?string $imageField): string
{
    if (!empty($imageField)) {
        $decoded = json_decode($imageField, true);
        if ($decoded && is_array($decoded) && !empty($decoded[0])) {
            return normalizeImagePath((string) $decoded[0]);
        }

        return normalizeImagePath($imageField);
    }

    return 'images/pic1.png';
}
