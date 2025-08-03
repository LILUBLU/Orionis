<?php
// Chargement manuel des fichiers nécessaires (car pas d'init.php)
require_once 'config.php';
require_once 'includes/constants.php';
require_once 'includes/vars.php';
require_once 'includes/classes/class.db_mysqli.php';

// Connexion à la base de données
$db = new DB_MySQLi();
$db->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Fonction pour trouver une position vide
function findFreePosition($galaxyMax = 9, $systemMax = 499, $planetMax = 15) {
    global $db;

    for ($i = 0; $i < 1000; $i++) {
        $galaxy = rand(1, $galaxyMax);
        $system = rand(1, $systemMax);
        $planet = rand(1, $planetMax);

        $sql = "SELECT COUNT(*) as count FROM uni1_planets WHERE galaxy = $galaxy AND system = $system AND planet = $planet";
        $result = $db->uniquequery($sql);

        if ($result['count'] == 0) {
            return [$galaxy, $system, $planet];
        }
    }

    return false;
}

// Vérifie si un NPC a déjà été créé aujourd'hui
$today = date('Y-m-d');
$result = $db->uniquequery("SELECT COUNT(*) as count FROM uni1_users WHERE username LIKE 'NPC_$today%'");
if ($result['count'] > 0) {
    die("Un NPC a déjà été créé aujourd'hui.");
}

// Trouver une position libre
$position = findFreePosition();
if (!$position) {
    die("Aucune position libre trouvée.");
}
[$galaxy, $system, $planet] = $position;

// Créer le joueur NPC
$username = "NPC_" . $today . "_" . rand(1000, 9999);
$password = md5("event1234");
$email = $username . '@npc.com';

$db->query("INSERT INTO uni1_users (username, password, email, authlevel, onlinetime, register_time)
    VALUES ('$username', '$password', '$email', 0, " . time() . ", " . time() . ")");
$userId = $db->GetInsertID();

// Créer la planète
$db->query("INSERT INTO uni1_planets (id_owner, name, galaxy, system, planet, planet_type, last_update, metal, crystal, deuterium)
    VALUES ($userId, 'Cible Événement', $galaxy, $system, $planet, 1, " . time() . ", 50000000, 50000000, 50000000)");

echo "NPC créé avec succès : $username à [$galaxy:$system:$planet]";
