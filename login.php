<?php
require __DIR__ . '/app/helpers/ui.php';

$content = <<<HTML
<h2>Connexion</h2>
<form action="traitement_login.php" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>
<p class="auth-switch">Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
HTML;

renderAuthShell('Connexion', 'Entre rapidement pour gerer ton panier et tes commandes.', $content, [
    ['href' => 'index.php', 'label' => 'Boutique'],
    ['href' => 'inscription.php', 'label' => 'Inscription'],
]);
