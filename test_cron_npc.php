<?php
require 'includes/config.php';
require 'includes/constants.php';
require 'includes/vars.php';
require 'includes/classes/Database.class.php';
require 'includes/classes/cronjob/CronjobTask.interface.php';
require 'includes/classes/cronjob/createEventPlanet.class.php';

// Connexion DB
$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_error) {
    die('Erreur de connexion à la base de données : ' . $db->connect_error);
}

// Exécution manuelle du cron
$task = new createEventPlanet();
$task->run();

echo "✅ Cron NPC exécuté manuellement.";
