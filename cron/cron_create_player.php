<?php

define('MODE', 'CRON');
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/constants.php';
require_once __DIR__.'/includes/GeneralFunctions.php';
require_once __DIR__.'/includes/classes/class.Application.php';

$uni = 1;
$username = 'EventPlayer';
$password = 'password123';
$email = 'event@bot.fr';
$planetName = 'BotHome';

if (PlayerUtil::isNameAvailable($username)) {
    $userId = PlayerUtil::createPlayer($uni, $username, md5($password), $email, 1, 0, 0, 0, 0, 0, 0, $planetName);
    echo "✅ Joueur créé avec succès. ID : " . $userId . "\n";
} else {
    echo "⚠️ Le nom d'utilisateur est déjà pris.\n";
}
