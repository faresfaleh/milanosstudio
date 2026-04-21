<?php
// ================================================
// php/check_dispo.php - Vérifier disponibilité date
// ================================================
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? '';

// Valider format date
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['count' => 0, 'dispo' => true]);
    exit;
}

// Compter réservations pour cette date (hors annulées)
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM reservations 
    WHERE date_soiree = ? 
    AND statut != 'annulée'
");
$stmt->execute([$date]);
$count = (int)$stmt->fetchColumn();

echo json_encode([
    'count' => $count,
    'max'   => 4,
    'dispo' => $count < 4
]);
?>
