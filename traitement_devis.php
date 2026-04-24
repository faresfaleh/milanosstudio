<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/php/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nom_homme    = trim($_POST['nom_homme']    ?? '');
$nom_femme    = trim($_POST['nom_femme']    ?? '');
$telephone    = trim($_POST['telephone']   ?? '');
$cin          = trim($_POST['cin']         ?? '');
$date_soiree  = trim($_POST['date_soiree'] ?? '');
$choix_prix   = trim($_POST['choix_package'] ?? '');
$wedding_type = trim($_POST['wedding_type'] ?? '');
$gouvernorat  = trim($_POST['gouvernorat'] ?? '');
$delegation   = trim($_POST['delegation']  ?? '');
$res_id       = (int)($_POST['res_id']     ?? 0);

if (empty($telephone) || empty($date_soiree)) {
    header('Location: index.php?error=Champs+manquants');
    exit;
}

// UPDATE si res_id fourni (modification depuis le panier)
if ($res_id > 0) {
    $pdo->prepare(
        "UPDATE reservations SET nom_homme=?, nom_femme=?, telephone=?, cin=?, date_soiree=?, choix_prix=?, wedding_type=?, gouvernorat=?, delegation=? WHERE id=?"
    )->execute([$nom_homme, $nom_femme, $telephone, $cin, $date_soiree, $choix_prix, $wedding_type, $gouvernorat, $delegation, $res_id]);
    header('Location: index.php?success=1&res_id=' . $res_id . '&updated=1');
    exit;
}

// INSERT nouvelle réservation
$pdo->prepare(
    "INSERT INTO reservations (nom_homme, nom_femme, telephone, cin, date_soiree, choix_prix, wedding_type, gouvernorat, delegation, statut, created_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'en attente', NOW())"
)->execute([$nom_homme, $nom_femme, $telephone, $cin, $date_soiree, $choix_prix, $wedding_type, $gouvernorat, $delegation]);

$new_id = (int)$pdo->lastInsertId();
header('Location: index.php?success=1&res_id=' . $new_id);
exit;