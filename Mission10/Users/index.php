<?php 
session_start(); 
 


  if (isset($_POST['submit'])) {
    $email = htmlspecialchars($_POST['email'] ?? '');
    $mdp = $_POST['mdp'] ?? '';
    $captcha = htmlspecialchars($_POST['captcha'] ?? '');

    // Vérification CAPTCHA
    if (!isset($_SESSION['captcha']) || $captcha !== $_SESSION['captcha']) {
      $_SESSION['captcha_error'] = "Le captcha est incorrect.";
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit;
    }

    if (!empty($email) && !empty($mdp)) {
      try {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $bdd = new PDO('mysql:host=saraj9-APSIO2.db.tb-hosting.com;dbname=saraj9_APSIO2', 'saraj9_sarahziza', 'MonSite2@25', $pdo_options);

        // Vérification de l'utilisateur SANS password_verify
        $req = $bdd->prepare('SELECT email, Mot_de_passe FROM users WHERE email = :email');
        $req->execute(['email' => $email]);
        $res = $req->fetch();

        // Comparaison directe des mots de passe
        if ($res && $mdp === $res['Mot_de_passe']) {
          $_SESSION['admin_email'] = $email;
          $_SESSION['login'] = $mdp;
          header('Location: formulaire.php');
          exit;
        } else {
          $_SESSION['mdp_error'] = "Votre identifiant ou mot de passe est incorrect.";
          header('Location: ' . $_SERVER['PHP_SELF']);
          exit;
        }
      } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
      }
    } else {
      $_SESSION['email_error'] = "Veuillez remplir tous les champs.";
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit;
    }
  }
  ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link id="favicon" rel="shortcut icon" href="../assets/img/logo.png" type="image/x-png" />
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

      <span class="input-span">
        <input required type="email" name="email" id="email" placeholder="email" />
        <?php if (isset($_SESSION['email_error'])): ?>
          <p class="error-message"><?php echo $_SESSION['email_error'];
          unset($_SESSION['email_error']); ?></p>
        <?php endif; ?>
      </span>

      <span class="input-span">
        <input required type="password" name="mdp" id="mdp" placeholder="Mot de passe" />
        <?php if (isset($_SESSION['mdp_error'])): ?>
          <p class="error-message"><?php echo $_SESSION['mdp_error'];
          unset($_SESSION['mdp_error']); ?></p>
        <?php endif; ?>
      </span>

      <img src="../captcha.php" style="display: block; margin: 1em 0;" />
      <span class="input-span">
        <input required id="captcha" type="text" name="captcha" placeholder="Copier le code" />
        <?php if (isset($_SESSION['captcha_error'])): ?>
          <p class="error-message"><?php echo $_SESSION['captcha_error'];
          unset($_SESSION['captcha_error']); ?></p>
        <?php endif; ?>
      </span>

      <span class="span"><a href="#">Mot de passe oublié ?</a></span>
      <span class="span">Vous n'avez pas de compte ? <a href="inscription.php">S'inscrire</a></span>
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