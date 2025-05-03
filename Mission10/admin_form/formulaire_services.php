<?php
  $serveur = "saraj9-APSIO2.db.tb-hosting.com";
      $utilisateur = "saraj9_sarahziza";
      $motDePasse = "MonSite2@25";
      $nomBDD = "saraj9_APSIO2";
try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motDePasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_service = $_POST['id_service'];
        $nom_service = $_POST['nom_service'];

        $requete = $connexion->prepare("INSERT INTO services (id_service, service) VALUES (?, ?)");
        $requete->execute([$id_service, $nom_service]);

        echo "<script>alert('Service ajouté avec succès !'); window.location.href='../admin/admin_service.php';</script>";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>