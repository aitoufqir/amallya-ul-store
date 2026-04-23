<?php

declare(strict_types=1);

function columnExists(PDO $pdo, string $table, string $column): bool
{
    try {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        return (bool) $stmt->fetch();
    } catch (Throwable $e) {
        return false;
    }
}

function formatProductNames(PDO $pdo, string $idsString): array
{
    $pairs = array_filter(array_map('trim', explode(',', $idsString)));
    $quantities = [];

    foreach ($pairs as $pair) {
        if (strpos($pair, 'x') !== false) {
            [$id, $qty] = explode('x', $pair, 2);
            $id = (int) $id;
            $qty = max(1, (int) $qty);
        } else {
            $id = (int) $pair;
            $qty = 1;
        }

        if ($id > 0) {
            $quantities[$id] = $qty;
        }
    }

    $ids = array_keys($quantities);
    if (empty($ids)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT nom FROM produits WHERE id IN ($placeholders)");
    $stmt->execute($ids);

    $names = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $result = [];

    foreach ($ids as $index => $id) {
        $name = $names[$index] ?? ('Produit #' . $id);
        $result[] = $name . ' x' . $quantities[$id];
    }

    return $result;
}
