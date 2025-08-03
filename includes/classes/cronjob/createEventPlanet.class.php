<?php

class createEventPlanet implements CronjobTaskInterface
{
    function run()
    {
        global $db;

        // Vérifie si un NPC a déjà été créé aujourd'hui
        $today = date('Y-m-d');
        $result = $db->uniquequery("SELECT COUNT(*) as count FROM uni1_users WHERE username LIKE 'NPC_$today%'");
        if ($result['count'] > 0) {
            return;
        }

        // Trouver une position libre
        for ($i = 0; $i < 1000; $i++) {
            $galaxy = rand(1, 9);
            $system = rand(1, 499);
            $planet = rand(1, 15);

            $check = $db->uniquequery("SELECT COUNT(*) as count FROM uni1_planets WHERE galaxy = $galaxy AND system = $system AND planet = $planet");

            if ($check['count'] == 0) {
                break;
            }
        }

        // Création du joueur NPC
        $username = "NPC_" . $today . "_" . rand(1000, 9999);
        $password = md5("event1234");
        $email = $username . '@npc.com';
        $now = TIMESTAMP;

        $db->query("INSERT INTO uni1_users (username, password, email, authlevel, onlinetime, register_time)
            VALUES ('$username', '$password', '$email', 0, $now, $now)");
        $userId = $db->GetInsertID();

        // Création de la planète
        $db->query("INSERT INTO uni1_planets (id_owner, name, galaxy, system, planet, planet_type, last_update, metal, crystal, deuterium)
            VALUES ($userId, 'Cible Événement', $galaxy, $system, $planet, 1, $now, 50000000, 50000000, 50000000)");
    }
}
