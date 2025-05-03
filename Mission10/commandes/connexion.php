<?php
$host = 'saraj9-APSIO2.db.tb-hosting.com';
$dbname = 'saraj9_APSIO2';
$username = 'saraj9_sarahziza';
$password = 'MonSite2@25';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>