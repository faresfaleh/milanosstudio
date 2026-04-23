<?php
session_start();

$host = 'sql302.infinityfree.com';
$user = 'if0_41721997';
$pass = 'faresfaleh1234';
$db   = 'if0_41721997_if0_41721997_mabase';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Connexion échouée: ' . $e->getMessage()]));
}
?>