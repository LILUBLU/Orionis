<?php
/**
 * createEventPlanet.php
 * Script pour créer un joueur PNJ avec une planète pleine de ressources
 */

require_once dirname(__FILE__) . '/../config.php';
require_once ROOT_PATH . 'includes/common.php';

function createBotPlayer() {
    global $db, $UNI;

    // Générer un nom unique
    $username = 'Cargobot_' . mt_rand(1000, 9999);
    $email = $username . '@bot.universe';

    // Créer le joueur
    $db->insert(T_USERS, array(
        'username'      => $username,
        'email'         => $email,
        'password'      => '',
        'authlevel'     => 0,
        'id_planet'     => 0,
        'universe'      => $UNI,
        'register_time' => TIMESTAMP,
    ));

    $userId = $db->GetInsertID();

    // Choisir des coordonnées aléatoires
    $galaxy  = mt_rand(1, 9);
    $system  = mt_rand(1, 499);
    $planet  = mt_rand(1, 15);

    // Créer la planète
    require_once ROOT_PATH . 'includes/classes/missions/MissionCasePlanet.php';
    $planetId = PlayerUtil::createPlanet($galaxy, $system, $planet, $UNI, $username . ' Planet', true, $userId);

    // Lier la planète au joueur
    $db->update(T_USERS, array('id_planet' => $planetId), "id = " . $userId);

    // Remplir de ressources
    $resources = array(
        'metal'     => 5000000,
        'crystal'   => 3000000,
        'deuterium'=> 1000000,
        'energy_max' => 0,
    );

    $updateSQL = array();
    foreach ($resources as $res => $value) {
        $updateSQL[] = "`$res` = $value";
    }

    $db->query("UPDATE " . T_PLANETS . " SET " . implode(', ', $updateSQL) . " WHERE id = " . $planetId . ";");

    echo "✅ Joueur PNJ '$username' créé avec la planète ID $planetId ([$galaxy:$system:$planet])\n";
}

createBotPlayer();
?>