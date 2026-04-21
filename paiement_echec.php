<?php
// ================================================
// php/paiement_echec.php - Paiement échoué
// ================================================
require_once __DIR__ . '/config.php';

$reservationId = (int)($_GET['reservationId'] ?? 0);

if ($reservationId) {
    // Annuler la réservation
    $pdo->prepare("UPDATE reservations SET statut='annulée' WHERE id=?")
        ->execute([$reservationId]);
}

header('Location: ../index.php?error=' . urlencode('Paiement annulé ou échoué. Veuillez réessayer.'));
exit;
?>
