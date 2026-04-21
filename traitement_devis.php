<?php
// ================================================
// traitement_devis.php - Enregistrer une réservation
// ================================================
require_once __DIR__ . '/php/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Récupérer données
$nom_homme     = trim($_POST['nom_homme'] ?? '');
$nom_femme     = trim($_POST['nom_femme'] ?? '');
$telephone     = trim($_POST['telephone'] ?? '');
$cin           = trim($_POST['cin'] ?? '');
$date_soiree   = trim($_POST['date_soiree'] ?? '');
$choix_package = trim($_POST['choix_package'] ?? '');
$wedding_type  = trim($_POST['wedding_type'] ?? '');
$choix_prix    = trim($_POST['choix_prix'] ?? '');

// Validation
$errors = [];

// ✅ FIX: Vérifier choix_package EN PREMIER avant wedding_type
if ($choix_package === 'shoot-ind' || $choix_package === 'event') {
    // Shooting individuel / Event → nom_homme seulement
    if (empty($nom_homme)) $errors[] = "Ce champ est requis.";
} elseif ($choix_package === 'shoot-mar') {
    // Shooting mariage → les deux noms
    if (empty($nom_homme)) $errors[] = "Le nom de l'homme est requis.";
    if (empty($nom_femme)) $errors[] = "Le nom de la femme est requis.";
} elseif ($wedding_type === 'wtiya') {
    if (empty($nom_femme)) $errors[] = "Le nom de la femme est requis.";
} elseif ($wedding_type === 'henna') {
    if (empty($nom_homme)) $errors[] = "Le nom de l'homme est requis.";
} else {
    // mariage → les deux
    if (empty($nom_homme)) $errors[] = "Le nom de l'homme est requis.";
    if (empty($nom_femme)) $errors[] = "Le nom de la femme est requis.";
}

if (strlen($telephone) !== 8 || !ctype_digit($telephone)) $errors[] = "Le numéro de téléphone doit contenir exactement 8 chiffres.";
if (strlen($cin) !== 8 || !ctype_digit($cin)) $errors[] = "Le numéro CIN doit contenir exactement 8 chiffres.";
if (empty($date_soiree)) $errors[] = "La date de la soirée est requise.";
if (!empty($date_soiree) && $date_soiree < date('Y-m-d')) $errors[] = "La date doit être dans le futur.";

if (!empty($errors)) {
    header("Location: index.php?error=" . urlencode(implode(' | ', $errors)));
    exit;
}

// Vérifier max 4 réservations par date
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE date_soiree = ? AND statut != 'annulée'");
$stmt->execute([$date_soiree]);
if ($stmt->fetchColumn() >= 4) {
    header("Location: index.php?error=" . urlencode("Cette date est complète (4/4). Choisissez une autre date."));
    exit;
}

// Insérer réservation
$stmt = $pdo->prepare("
    INSERT INTO reservations (nom_homme, nom_femme, telephone, cin, date_soiree, choix_package, wedding_type, choix_prix)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$nom_homme, $nom_femme, $telephone, $cin, $date_soiree, $choix_package, $wedding_type, $choix_prix]);

header("Location: index.php?success=1");
exit;
?>