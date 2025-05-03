<?php
session_start(); 


    if (isset($_POST['submit'])) {
      try {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $bdd = new PDO('mysql:host=saraj9-APSIO2.db.tb-hosting.com;dbname=saraj9_APSIO2', 'saraj9_sarahziza', 'MonSite2@25', $pdo_options);

        if (!empty($_POST['email']) && !empty($_POST['mdp']) && !empty($_POST['mdp_confirmation'])) {
          $email = htmlspecialchars($_POST['email']);
          $mdp = htmlspecialchars($_POST['mdp']);
          $mdp_confirmation = htmlspecialchars($_POST['mdp_confirmation']);

          if ($mdp !== $mdp_confirmation) {
            $_SESSION['error_message'] = 'Les mots de passe ne correspondent pas.';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
          }

          // Vérification si l'email existe déjà
          $check = $bdd->prepare('SELECT email FROM users WHERE email = ?');
          $check->execute([$email]);
          if ($check->rowCount() > 0) {
            $_SESSION['error_message'] = 'Cette adresse email est déjà utilisée.';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
          }

          // Insertion SANS hacher le mot de passe
          $insert = $bdd->prepare('INSERT INTO users (email, Mot_de_passe) VALUES (?, ?)');
          $insert->execute([$email, $mdp]); // Stockage direct du mot de passe
    
          $_SESSION['admin_email'] = $email;
          $_SESSION['login'] = $mdp;
          header('Location: index.php');
          exit;
        } else {
          $_SESSION['error_message'] = 'Veuillez remplir tous les champs.';
          header('Location: ' . $_SERVER['PHP_SELF']);
          exit;
        }
      } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
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

  <section class="Inscription">
    <form method="post" action="inscription.php" class="form">
      <h1>Bienvenue à <span>LPFSClinique</span></h1>
      <img class="logo" src="../assets/img/logo.png" height="200px" />
      <h2>Créer un compte</h2>
      <span class="input-span">
        <!--<label for="email" class="label">Email</label>!-->
        <input required="" type="email" name="email" id="email" placeholder="email" /></span>
      <span class="input-span">
        <!--<label for="password" class="label">Password</label>!-->

        <input required type="password" name="mdp" id="mdp" placeholder="Mot de passe" />
        <div id="password-strength">
          <div class="progress strength-0"></div>
        </div>
        <p id="password-error" class="error-message"></p>
      </span>

      <span class="input-span">
        <input required="" type="password" name="mdp_confirmation" id="mdp_confirmation"
          placeholder="Confirmer Mot de passe" />
        <p id="confirmation-error" class="error-message"></p>
      </span>
      <span class="span">Vous avez déja un compte ?<a href="index.php">Connexion</a></span>
      <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error"><?php echo $_SESSION['error_message'];
        unset($_SESSION['error_message']); ?></p>
      <?php endif; ?>
      <input class="submit" type="submit" name="submit" value="S'incrire" />
    </form>
   

  </section>
  <script>
    const passwordInput = document.getElementById('mdp');
    const confirmInput = document.getElementById('mdp_confirmation');
    const strengthBar = document.querySelector('#password-strength .progress');
    const passwordError = document.getElementById('password-error');
    const confirmationError = document.getElementById('confirmation-error');

    function checkPasswordStrength(password) {
      let strength = 0;
      if (password.length >= 8) strength++;
      if (/[A-Z]/.test(password)) strength++;
      if (/[a-z]/.test(password)) strength++;
      if (/\d/.test(password)) strength++;
      if (/[\W_]/.test(password)) strength++;

      return Math.min(strength, 4); // max 4
    }

    passwordInput.addEventListener('input', () => {
      const password = passwordInput.value;
      const strength = checkPasswordStrength(password);

      // Mettre à jour la barre de progression
      strengthBar.className = 'progress';
      strengthBar.classList.add(`strength-${strength}`);

      // Message d'erreur si le mot de passe ne remplit pas les critères
      if (password.length > 0 && strength < 4) {
        passwordError.textContent = "Le mot de passe doit contenir : majuscule, minuscule, chiffre, caractère spécial, 8 caractères minimum.";
      } else {
        passwordError.textContent = "";
      }
    });

    document.querySelector('.form').addEventListener('submit', function (e) {
      const password = passwordInput.value;
      const confirmPassword = confirmInput.value;
      const strength = checkPasswordStrength(password);
      let valid = true;

      if (strength < 4) {
        passwordError.textContent = "Le mot de passe est trop faible.";
        valid = false;
      }

      if (password !== confirmPassword) {
        confirmationError.textContent = "Les mots de passe ne correspondent pas.";
        valid = false;
      } else {
        confirmationError.textContent = "";
      }

      if (!valid) e.preventDefault();
    });
  </script>


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