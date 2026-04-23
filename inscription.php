<?php
require __DIR__ . '/app/helpers/ui.php';

$content = <<<HTML
<h2>Inscription</h2>
<form action="traitement_inscription.php" method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>
<p class="auth-switch">Deja un compte ? <a href="login.php">Se connecter</a></p>
HTML;

renderAuthShell('Inscription', 'Cree ton compte pour acheter et suivre tes commandes.', $content, [
    ['href' => 'index.php', 'label' => 'Boutique'],
    ['href' => 'login.php', 'label' => 'Connexion'],
]);
