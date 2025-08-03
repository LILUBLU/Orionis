<?php

class createEventPlanet implements CronjobTaskInterface
{
    function run()
    {
        global $db;

        // Vérifie si un NPC a déjà été créé aujourd'hui
        $today = date('Y-m-d');
        $result = $db->uniquequery("SELECT COUNT(*) as count FROM uni1_users WHERE username LIKE 'NPC_$today%'");
        if ($result === false) {
            error_log("Erreur requête SELECT NPC.");
            return;
        }
        if (isset($result['count']) && $result['count'] > 0) {
            error_log("NPC déjà créé aujourd'hui.");
            return;
        }

        $found = false;
        for ($i = 0; $i < 1000; $i++) {
            $galaxy = rand(1, 9);
            $system = rand(1, 499);
            $planet = rand(1, 15);

            $check = $db->uniquequery("SELECT COUNT(*) as count FROM uni1_planets WHERE galaxy = $galaxy AND system = $system AND planet = $planet");
            if ($check === false) {
                error_log("Erreur requête SELECT position.");
                return;
            }
            if (isset($check['count']) && $check['count'] == 0) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            error_log("Aucune position libre trouvée.");
            return;
        }

        $username = "NPC_" . $today . "_" . rand(1000, 9999);
        $password = md5("event1234");
        $email = $username . '@npc.com';
        $now = defined('TIMESTAMP') ? TIMESTAMP : time();

        $resUser = $db->query("INSERT INTO uni1_users (username, password, email, authlevel, onlinetime, register_time)
            VALUES ('$username', '$password', '$email', 0, $now, $now)");
        if ($resUser === false) {
            error_log("Erreur création user NPC.");
            return;
        }
        $userId = $db->GetInsertID();
        if (!$userId) {
            error_log("Erreur récupération ID user NPC.");
            return;
        }

        $resPlanet = $db->query("INSERT INTO uni1_planets (id_owner, name, galaxy, system, planet, planet_type, last_update, metal, crystal, deuterium)
            VALUES ($userId, 'Cible Événement', $galaxy, $system, $planet, 1, $now, 50000000, 50000000, 50000000)");
        if ($resPlanet === false) {
            error_log("Erreur création planète NPC.");
            return;
        }

        error_log("NPC et planète créés avec succès.");
    }
}
