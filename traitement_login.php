<?php
require __DIR__ . '/config.php';
require __DIR__ . '/app/helpers/ui.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);

$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nom'] = $user['nom'];

    header("Location: index.php");
    exit;

} else {
    $message = "Email ou mot de passe incorrect ❌";
}
renderAuthShell('Connexion', 'Verification de vos identifiants.', '<div class="message-box-inline"><h2>' . htmlspecialchars($message) . '</h2><a href="login.php" class="btn-primary">Reessayer</a></div>', [
    ['href' => 'index.php', 'label' => 'Boutique'],
    ['href' => 'inscription.php', 'label' => 'Inscription'],
]);
