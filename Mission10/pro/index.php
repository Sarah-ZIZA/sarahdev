<?php
    // Start output buffering to prevent headers sent issues
    ob_start();
    session_start(); // Démarrer la session une seule fois, au début.
    
    if (isset($_SESSION['error_message'])) {
      $error_message = $_SESSION['error_message'];
      unset($_SESSION['error_message']); // Supprimer le message après l'affichage.
    }

    if (isset($_POST['submit'])) {
      $Captcha = htmlspecialchars($_POST['captcha']);

      // Vérification du CAPTCHA
      if (isset($Captcha) && isset($_SESSION['captcha']) && $Captcha === $_SESSION['captcha']) {
        try {
          // Connexion à la base de données
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          $bdd = new PDO('mysql:host=saraj9-APSIO2.db.tb-hosting.com;dbname=saraj9_APSIO2', 'saraj9_sarahziza', 'MonSite2@25', $pdo_options);

          // Vérifier que les champs ne sont pas vides
          if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
            $adresse_mail = htmlspecialchars($_POST['email']);
            $mdp = htmlspecialchars($_POST['mdp']);

            // Requête pour vérifier l'utilisateur
            $req = $bdd->prepare('SELECT Mdp, id_poste FROM professionnels WHERE Adresse_mail = :email');
            $req->execute(array(':email' => $adresse_mail));
            $res = $req->fetch();

            if ($res) {
              // Vérification du mot de passe
              if ($mdp === $res['Mdp']) {
                // Stocker l'email et le poste dans la session
                $_SESSION['login'] = $mdp;
                $_SESSION['admin_email'] = $adresse_mail; // Stocker l'email
                $_SESSION['id_poste'] = $res['id_poste'];

                // Rediriger selon le poste de l'utilisateur
                if ($res['id_poste'] == 1) {
                  header('Location: ../admin/admin_acceuil.php');
                } else if ($res['id_poste'] == 2) {
                  header('Location: ../secretaire/secretaire_acceuil.php');
                } else {
                  header('Location: ../médecin/medecin_accueil.php');
                }
                exit; // Terminer le script après une redirection
              } else {
                $_SESSION['error_message'] = 'Votre identifiant ou mot de passe est incorrect';
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
              }
            } else {
              $_SESSION['error_message'] = 'Votre identifiant ou mot de passe est incorrect';
              header('Location: ' . $_SERVER['PHP_SELF']);
              exit;
            }
          } else {
            $_SESSION['error_message'] = 'Votre identifiant ou mot de passe est incorrect';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
          }
        } catch (Exception $e) {
          die('Erreur : ' . $e->getMessage());
        }
      } else {
        $_SESSION['error_message'] = 'Le captcha est incorrect.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
      }
    }
    ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <link id="favicon" rel="shortcut icon" href="../assets/img/logo.png" type="image/x-png" />
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>LPFSClinique</title>
  <link rel="stylesheet" href="../assets/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,300&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <section class="Connexion">
    <form method="post" action="index.php" class="form">
      <h1>Bienvenue à <span>LPFSClinique</span></h1>
      <img class="logo" src="../assets/img/logo.png" height="200px" />
      <h2>Connectez-Vous</h2>
      <?php if (isset($error_message)): ?>
        <p class="incorrect"><?php echo $error_message; ?></p>
      <?php endif; ?>
      <span class="input-span">
        <!--<label for="email" class="label">Email</label>!-->
        <input required="" type="email" name="email" id="email" placeholder="email" /></span>
      <span class="input-span">
        <!--<label for="password" class="label">Password</label>!-->
        <input required="" type="password" name="mdp" id="mdp" placeholder="Mot de passe" /></span>
      <img src="../captcha.php" style=" display: block; color: #212529;font-size:3rem;" />
      <span class="input-span">
        <input required="" id="captcha" type="text" name="captcha" placeholder="Copier le code" /></span>
      <span class="span"><a href="#">Mot de passe oublié ?</a></span>
      <input class="submit" type="submit" name="submit" value="Se connecter" />
    </form>
  </section>
</body>
<script>
  //Empecher le retour arrière navigateur
  window.history.pushState(null, "", window.location.href);
  window.onpopstate = function () {
    window.history.pushState(null, "", window.location.href);
  };
</script>
<footer></footer>

</html>
<?php ob_end_flush(); ?>