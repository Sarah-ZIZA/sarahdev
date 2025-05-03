<?php
  $serveur = "saraj9-APSIO2.db.tb-hosting.com";
      $utilisateur = "saraj9_sarahziza";
      $motDePasse = "MonSite2@25";
      $nomBDD = "saraj9_APSIO2";

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motDePasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $num_chambre = $_POST['num_chambre'];
        $type = $_POST['type_chambre'];
        $etage = $_POST['etage'];

        $requete = $connexion->prepare("INSERT INTO chambres (num_chambre, type ,étage) VALUES (?, ?, ?)");
        $requete->execute([$num_chambre, $type, $etage]);

        echo "<script>alert('Service ajouté avec succès !'); window.location.href='../admin/admin_chambre.php';</script>";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>