<?php
require __DIR__ . '/config.php';
require __DIR__ . '/app/helpers/ui.php';

$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$rawPassword = $_POST['password'] ?? '';
$password = password_hash($rawPassword, PASSWORD_DEFAULT);

$message = "";
$ok = false;

// check email déjà existe
$stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    $message = "Email déjà utilisé ❌";
} elseif ($nom === '' || $email === '' || $rawPassword === '') {
    $message = "Tous les champs sont obligatoires ❌";
} else {
    $stmt = $pdo->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $email, $password]);
    $message = "Inscription réussie ✅";
    $ok = true;
}
$buttonHref = $ok ? 'login.php' : 'inscription.php';
$buttonLabel = $ok ? 'Aller a la connexion' : 'Reessayer';

renderAuthShell('Inscription', 'Creation et verification du compte.', '<div class="message-box-inline"><h2>' . htmlspecialchars($message) . '</h2><a href="' . htmlspecialchars($buttonHref) . '" class="btn-primary">' . htmlspecialchars($buttonLabel) . '</a></div>', [
    ['href' => 'index.php', 'label' => 'Boutique'],
    ['href' => 'login.php', 'label' => 'Connexion'],
]);
