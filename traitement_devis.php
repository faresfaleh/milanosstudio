<?php
// Chercher config.php selon l'emplacement du fichier
if (file_exists(__DIR__ . '/php/config.php')) {
    require_once __DIR__ . '/php/config.php';
} elseif (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
} else {
    require_once __DIR__ . '/../php/config.php';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nom_homme    = trim($_POST['nom_homme']    ?? '');
$nom_femme    = trim($_POST['nom_femme']    ?? '');
$telephone    = trim($_POST['telephone']    ?? '');
$cin          = trim($_POST['cin']          ?? '');
$date_soiree  = trim($_POST['date_soiree']  ?? '');
$wedding_type = trim($_POST['wedding_type'] ?? '');
$res_id       = (int)($_POST['res_id']      ?? 0);
$total_price  = (int)($_POST['total_price'] ?? 0);
$delegation   = trim($_POST['delegation']   ?? '');
$del_gov      = trim($_POST['delegation_gov'] ?? '');

// Construire choix_prix avec prix inclus
$choix_prix = trim($_POST['choix_package'] ?? '');
if ($total_price > 0 && !preg_match('/\d+\s*DT/i', $choix_prix)) {
    $choix_prix .= ' — ' . $total_price . ' DT';
}
if ($delegation) {
    $loc = $del_gov ? $del_gov . ' / ' . $delegation : $delegation;
    $choix_prix .= ' | ' . $loc;
}

// Détecter le type pour shooting
if (empty($wedding_type)) {
    if (stripos($choix_prix, 'Shooting Individuel') !== false)  $wedding_type = 'shoot-ind';
    elseif (stripos($choix_prix, 'Shooting Mariage') !== false) $wedding_type = 'shoot-mar';
    elseif (stripos($choix_prix, 'Event') !== false)            $wedding_type = 'event';
}

if (empty($telephone) || empty($date_soiree)) {
    header('Location: index.php?error=Champs+manquants');
    exit;
}

// ── UPDATE réservation existante ─────────────────────────────────────────
if ($res_id > 0) {
    $pdo->prepare(
        "UPDATE reservations SET nom_homme=?, nom_femme=?, telephone=?, cin=?,
         date_soiree=?, choix_prix=?, wedding_type=? WHERE id=?"
    )->execute([$nom_homme, $nom_femme, $telephone, $cin,
                $date_soiree, $choix_prix, $wedding_type, $res_id]);
    header('Location: index.php?success=1&res_id=' . $res_id . '&updated=1');
    exit;
}

// ── INSERT nouvelle réservation ──────────────────────────────────────────
try {
    $pdo->prepare(
        "INSERT INTO reservations
            (nom_homme, nom_femme, telephone, cin, date_soiree, choix_prix, wedding_type, total_price, statut, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'en attente', NOW())"
    )->execute([$nom_homme, $nom_femme, $telephone, $cin,
                $date_soiree, $choix_prix, $wedding_type, $total_price]);
} catch (\PDOException $e) {
    $pdo->prepare(
        "INSERT INTO reservations
            (nom_homme, nom_femme, telephone, cin, date_soiree, choix_prix, wedding_type, statut, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'en attente', NOW())"
    )->execute([$nom_homme, $nom_femme, $telephone, $cin,
                $date_soiree, $choix_prix, $wedding_type]);
}

$new_id = (int)$pdo->lastInsertId();

// ═══════════════════════════════════════════════════════════════════
// CONFIGURATION EMAIL GMAIL
// ═══════════════════════════════════════════════════════════════════
// Remplace 'MOT_DE_PASSE_APPLICATION_ICI' par le mot de passe 16 caractères
// généré sur myaccount.google.com → Sécurité → Mots de passe des applications
$gmail_user = 'faresfaleh47@gmail.com';
$gmail_pass = 'cshs fbdo iizy suhn'; // ex: 'abcd efgh ijkl mnop'
// ═══════════════════════════════════════════════════════════════════

$typeLabels = [
    'wtiya'     => 'Wtiya',
    'henna'     => 'Henna',
    'mariage'   => 'Mariage',
    'shoot-ind' => 'Shooting Individuel',
    'shoot-mar' => 'Shooting Mariage',
    'event'     => 'Event Photography',
];
$typeLabel = $typeLabels[$wedding_type] ?? $wedding_type;
$nom       = trim($nom_homme . ' ' . $nom_femme) ?: 'Non renseigne';
$delLabel  = $delegation ? ($del_gov ? "$del_gov / $delegation" : $delegation) : 'Non renseigne';
$prixLabel = $total_price > 0 ? $total_price . ' DT' : 'Non calcule';
$dateNow   = date('d/m/Y à H:i');

$html_email = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:20px;">
<div style="max-width:560px;margin:0 auto;background:#fff;border-radius:10px;overflow:hidden;border-top:4px solid #E31E24;box-shadow:0 4px 20px rgba(0,0,0,0.1);">
  <div style="background:#E31E24;color:#fff;padding:24px 28px;">
    <h1 style="margin:0;font-size:22px;">🔔 Nouvelle Réservation #' . $new_id . '</h1>
    <p style="margin:6px 0 0;opacity:0.85;font-size:13px;">Milano Studio — ' . $dateNow . '</p>
  </div>
  <div style="padding:28px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;border-collapse:collapse;">
      <tr><td style="padding:10px 0;border-bottom:1px solid #f0f0f0;color:#888;font-size:12px;text-transform:uppercase;">Type</td>
          <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;font-weight:600;text-align:right;">' . htmlspecialchars($typeLabel) . '</td></tr>
      <tr><td style="padding:10px 0;border-bottom:1px solid #f0f0f0;color:#888;font-size:12px;text-transform:uppercase;">Nom(s)</td>
          <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;font-weight:600;text-align:right;">' . htmlspecialchars($nom) . '</td></tr>
      <tr><td style="padding:10px 0;border-bottom:1px solid #f0f0f0;color:#888;font-size:12px;text-transform:uppercase;">Téléphone</td>
          <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;font-weight:700;text-align:right;">
            <a href="tel:' . htmlspecialchars($telephone) . '" style="color:#E31E24;text-decoration:none;font-size:20px;">' . htmlspecialchars($telephone) . '</a></td></tr>
      <tr><td style="padding:10px 0;border-bottom:1px solid #f0f0f0;color:#888;font-size:12px;text-transform:uppercase;">CIN</td>
          <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;font-weight:600;text-align:right;font-family:monospace;">' . htmlspecialchars($cin) . '</td></tr>
      <tr><td style="padding:10px 0;border-bottom:1px solid #f0f0f0;color:#888;font-size:12px;text-transform:uppercase;">Date soirée</td>
          <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;font-weight:600;text-align:right;">📅 ' . htmlspecialchars($date_soiree) . '</td></tr>
      <tr><td style="padding:10px 0;border-bottom:1px solid #f0f0f0;color:#888;font-size:12px;text-transform:uppercase;">Package</td>
          <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;font-weight:600;text-align:right;font-size:13px;">' . htmlspecialchars($choix_prix) . '</td></tr>
      <tr><td style="padding:10px 0;border-bottom:1px solid #f0f0f0;color:#888;font-size:12px;text-transform:uppercase;">Localisation</td>
          <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;font-weight:600;text-align:right;">📍 ' . htmlspecialchars($delLabel) . '</td></tr>
      <tr><td style="padding:16px 0 0;color:#888;font-size:12px;text-transform:uppercase;">Total estimé</td>
          <td style="padding:16px 0 0;font-weight:700;font-size:28px;color:#E31E24;text-align:right;">' . $prixLabel . '</td></tr>
    </table>
  </div>
  <div style="background:#f8f8f8;padding:14px 28px;font-size:11px;color:#bbb;text-align:center;">
    Milano Studio Photography · Mazouna, Sidi Bouzid
  </div>
</div></body></html>';

// Envoi via Gmail SMTP (socket direct, sans librairie externe)
if ($gmail_pass !== 'MOT_DE_PASSE_APPLICATION_ICI') {
    envoyerGmail($gmail_user, $gmail_pass, $gmail_user, 
        'Nouvelle reservation #' . $new_id . ' - Milano Studio',
        $html_email,
        "Reservation #{$new_id}\nNom: {$nom}\nTel: {$telephone}\nCIN: {$cin}\nDate: {$date_soiree}\nType: {$typeLabel}\nPackage: {$choix_prix}\nTotal: {$prixLabel}"
    );
}

header('Location: index.php?success=1&res_id=' . $new_id);
exit;

// ────────────────────────────────────────────────────────────────────────────
// Fonction envoi Gmail SMTP via socket (sans PHPMailer, sans cURL)
// ────────────────────────────────────────────────────────────────────────────
function envoyerGmail(string $user, string $pass, string $to, string $subject, string $html, string $text): void {
    $host    = 'ssl://smtp.gmail.com';
    $port    = 465;
    $timeout = 15;

    $sock = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if (!$sock) return;

    $boundary = md5(uniqid());

    $body  = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n" . $text . "\r\n";
    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n" . $html . "\r\n";
    $body .= "--{$boundary}--";

    $msg  = "Date: " . date('r') . "\r\n";
    $msg .= "From: Milano Studio <{$user}>\r\n";
    $msg .= "To: <{$to}>\r\n";
    $msg .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
    $msg .= "MIME-Version: 1.0\r\n";
    $msg .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
    $msg .= "\r\n" . $body;

    $encoded_msg = chunk_split(base64_encode($msg));

    $cmds = [
        null,                                                          // lire bannière
        "EHLO milanofaleh.free.nf\r\n",
        "AUTH LOGIN\r\n",
        base64_encode($user) . "\r\n",
        base64_encode($pass) . "\r\n",
        "MAIL FROM:<{$user}>\r\n",
        "RCPT TO:<{$to}>\r\n",
        "DATA\r\n",
        $encoded_msg . "\r\n.\r\n",                                   // message encodé
        "QUIT\r\n",
    ];

    foreach ($cmds as $cmd) {
        if ($cmd !== null) fwrite($sock, $cmd);
        fgets($sock, 512);
    }
    fclose($sock);
}