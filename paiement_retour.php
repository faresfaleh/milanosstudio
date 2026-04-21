<?php
// ================================================
// php/paiement_retour.php - Retour après paiement réussi
// ================================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/clictopay_config.php';

$orderId       = $_GET['orderId'] ?? '';
$reservationId = (int)($_GET['reservationId'] ?? 0);

if (empty($orderId) || !$reservationId) {
    header('Location: ../index.php?error=' . urlencode('Paramètres manquants.'));
    exit;
}

// Vérifier le statut du paiement auprès de ClicToPay
$url    = CTP_URL . 'getOrderStatus.do';
$params = http_build_query([
    'userName' => CTP_LOGIN,
    'password' => CTP_PASSWORD,
    'orderId'  => $orderId,
    'language' => 'fr',
]);

$ch = curl_init($url . '?' . $params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

// orderStatus: 2 = payé avec succès
if (isset($result['orderStatus']) && $result['orderStatus'] == 2) {
    // Mettre à jour la réservation → confirmée
    $pdo->prepare("UPDATE reservations SET statut='confirmée' WHERE id=?")
        ->execute([$reservationId]);
    header('Location: ../index.php?success=1&paid=1');
} else {
    // Paiement échoué
    $pdo->prepare("UPDATE reservations SET statut='annulée' WHERE id=?")
        ->execute([$reservationId]);
    header('Location: ../index.php?error=' . urlencode('Paiement non confirmé. Réessayez.'));
}
exit;
?>
