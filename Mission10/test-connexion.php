<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $bdd = new PDO('mysql:host=saraj9-APSIO2.db.tb-hosting.com;dbname=saraj9_APSIO2', 'saraj9_sarahziza', 'MonSite2@25', $pdo_options);
    
    echo "✅ Connexion réussie à la base de données !<br>";

    // Exécution de la requête SQL correctement
    $email = 'arnaud.petasse@lpfs.com';
    $req = $bdd->prepare('SELECT Mdp, id_poste FROM professionnels WHERE Adresse_mail = :email');
    $req->execute(['email' => $email]);
    $res = $req->fetch();

    // Vérification du résultat
    if ($res) {
        echo "✅ Utilisateur trouvé :<br>";
        var_dump($res);
    } else {
        echo "❌ Aucun utilisateur trouvé avec cet email.";
    }
} catch (Exception $e) {
    die('❌ Erreur de connexion : ' . $e->getMessage());
}
?>

