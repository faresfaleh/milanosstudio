<?php
// ================================================
// php/clictopay_config.php
// Remplir après inscription sur clictopay.com.tn
// ================================================

define('CTP_LOGIN',    'VOTRE_MERCHANT_LOGIN');     // ← Remplacer
define('CTP_PASSWORD', 'VOTRE_MERCHANT_PASSWORD');  // ← Remplacer
define('CTP_URL',      'https://ipay.clictopay.com.tn/payment/rest/'); // URL production

// URLs de retour après paiement
define('CTP_RETURN_URL', 'http://localhost/milano/php/paiement_retour.php'); // ← Changer en production
define('CTP_FAIL_URL',   'http://localhost/milano/php/paiement_echec.php');  // ← Changer en production

// Montants fixes par type (en millimes: 1 DT = 1000)
define('PRIX_SHOOTING_IND', 200000);  // 200 DT
define('PRIX_SHOOTING_MAR', 200000);  // 200 DT
define('PRIX_EVENT',        350000);  // 350 DT
define('PRIX_CHOIX_1',      250000);  // 250 DT
define('PRIX_CHOIX_2',      500000);  // 500 DT
define('PRIX_CHOIX_3',      800000);  // 800 DT
?>
