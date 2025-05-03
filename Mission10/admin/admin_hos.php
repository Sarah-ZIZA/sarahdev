<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link id="favicon" rel="shortcut icon" href="../assets/img/logo.png" type="image/x-png" />
  <title>LPFSClinique</title>
  <link rel="stylesheet" href="../assets/style_admin/stylesAdmin.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,300&display=swap" rel="stylesheet" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>
  <?php
  // Démarrer la session (si ce n'est pas déjà fait)
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Vérifier si l'email de l'administrateur est dans la session
  if (!isset($_SESSION['admin_email'])) {
    header("Location:../404/404.html"); // Rediriger vers la page 404
    exit(); // Assure-toi que le script s'arrête après la redirection
  }


  // Récupérer l'email de l'administrateur
  $admin_email = htmlspecialchars($_SESSION['admin_email'], ENT_QUOTES, 'UTF-8');

  // Extraire la partie avant '@' de l'email
  $admin_name = htmlspecialchars(explode('@', $admin_email)[0], ENT_QUOTES, 'UTF-8');
  ?>

  <header>

    <div class="border_top"></div>
    <div class="boutons_droite">
      <div class="boutons_id">
        <button id="btn-user" class="btn-user" data-nom="<?php echo $admin_name; ?>" onclick="toggleNomUtilisateur()">
          <svg class="user-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path
              d="M12,12.5c-3.04,0-5.5,1.73-5.5,3.5s2.46,3.5,5.5,3.5,5.5-1.73,5.5-3.5-2.46-3.5-5.5-3.5Zm0-.5c1.66,0,3-1.34,3-3s-1.34-3-3-3-3,1.34-3,3,1.34,3,3,3Z">
            </path>
          </svg>
          <span class="btn-text">Utilisateur</span>
        </button>
      </div>

    </div>
    <i class='bx bxs-left-arrow-square' onclick="document.getElementById('decoForm').submit();"></i>

    <form id="decoForm" action="../commandes/deconnexion.php" method="post" style="display: none;"></form>

    <nav>
      <div class="logo">
        <img src="../assets/img/logo.png" alt="logo">
      </div>
      <ul class="menu">
        <li class="has-submenue">
          <a href="admin_acceuil.php">Accueil <span class="toggle-icon"></a>

        </li>

        <li class="has-submenu">
          <a href="#">Services <span class="toggle-icon">▼</span></a>
          <ul class="submenu">
            <li><a href="admin_service.php?modal=true">Inscrire un service</a></li>

            <li><a href="admin_service.php" data-target="list-service">Liste des services</a></li>
          </ul>
        </li>

        <li class="has-submenu">
          <a href="#">Patients <span class="toggle-icon">▼</span></a>
          <ul class="submenu">
            <li><a href="../admin_form/formulaires_patients.php" data-target="add-patient">Inscrire un patient</a>
            </li>
            <li><a href="admin_patient.php" data-target="list-patient">Liste des patients</a></li>
          </ul>
        </li>
        <li class="has-submenu">
          <a href="#">Couverture_scl <span class="toggle-icon">▼</span></a>
          <ul class="submenu">

            <li><a href="admin_couvert.php" data-target="list-hospital">Liste des couverture_scl</a></li>
          </ul>
        </li>
        <li class="has-submenu">
          <a href="#">Personnels <span class="toggle-icon">▼</span></a>
          <ul class="submenu">
            <li><a href="../admin_form/formulaires_personnels.php" data-target="add-personnel">Inscrire un
                personnel</a>
            </li>
            <li><a href="admin_pro.php" data-target="list-personnel">Liste du personnel</a>
            </li>
          </ul>
        </li>

        <li class="has-submenu">
          <a href="#">Hospitalisation <span class="toggle-icon">▼</span></a>
          <ul class="submenu">

            <li><a href="admin_hos.php" data-target="list-hospital">Liste des hospitalisations</a></li>
          </ul>
        </li>
        <li class="has-submenu">
          <a href="#">Chambres <span class="toggle-icon">▼</span></a>
          <ul class="submenu">
            <li><a href="admin_chambre.php?modal=true" data-target="add-hospital">Ajouter une Chambre</a></li>
            <li><a href="admin_chambre.php" data-target="list-hospital">Liste des Chambres</a></li>
          </ul>
        </li>

      </ul>


    </nav>
  </header>
  <main>
    <form method="get" class="search" action="admin_hos.php" onsubmit="scrollToPage5()">
      <input class="recherche" type="text" name="recherche_page5"
        style="width: 50%; height:41px; border:none;border-radius:10px;" placeholder="Rechercher patient"
        value="<?php echo isset($_GET['recherche_page5']) ? htmlspecialchars($_GET['recherche_page5'], ENT_QUOTES, 'UTF-8') : ''; ?>">
      <button class="btn" type="submit">Rechercher</button>
    </form>
    <div class="global">


      <?php
      $serveur = "saraj9-APSIO2.db.tb-hosting.com";
      $utilisateur = "saraj9_sarahziza";
      $motDePasse = "MonSite2@25";
      $nomBDD = "saraj9_APSIO2";

      try {
        $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motDePasse);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $table5 = "hospitalisation";
        $table6 = "professionnels";
        $recherche = $_GET['recherche_page5'] ?? '';


       // Requête de recherche
        if ($recherche) {
          $requete1 = $connexion->prepare("
          SELECT 
              id_hos, pré_ad, date_hos, heure,
              CONCAT(patient.nom, ' ', patient.prénom) AS 'Nom_patient',
              $table6.nom AS 'Médecin',
              $table5.Adresse_mail_utilisateur, 
              chambre_num, type_chambre,
              patient.id_patient AS 'hidden_id_patient'  
          FROM $table5, $table6, patient
          WHERE $table5.Adresse_mail = $table6.Adresse_mail
            AND $table5.id_patient = patient.id_patient
            AND (patient.nom LIKE ? OR patient.prénom LIKE ?)
      ");
          $requete1->execute(['%' . $recherche . '%', '%' . $recherche . '%']);

        } else {
          $requete1 = $connexion->prepare("SELECT id_hos,pré_ad,date_hos,heure,CONCAT(patient.nom, ' ', patient.prénom) AS 'Nom_patient' ,$table6.nom AS 'Médecin',$table5.Adresse_mail_utilisateur,chambre_num,type_chambre, patient.id_patient AS 'hidden_id_patient'   FROM $table5, $table6,patient WHERE $table5.Adresse_mail=$table6.Adresse_mail AND $table5.id_patient = patient.id_patient");
          $requete1->execute();
        }

        $resultats1 = $requete1->fetchAll(PDO::FETCH_ASSOC);

        // Gestion des suppressions et modifications
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if (isset($_POST['supprimer']) && !empty($_POST['selection'])) {
            $ids = $_POST['selection'];
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $requeteSuppression = $connexion->prepare("DELETE FROM  $table5 WHERE id_hos IN ($placeholders)");
            $requeteSuppression->execute($ids);
            header("Location: admin_hos.php"); // Redirection pour éviter la double soumission
            exit();
          } elseif (isset($_POST['supprimer_unique'])) {
            $id = $_POST['supprimer_unique'];
            $requeteSuppression = $connexion->prepare("DELETE FROM $table5 WHERE id_hos = ?");
            $requeteSuppression->execute([$id]);
            header("Location: admin_hos.php"); // Redirection pour éviter la double soumission
            exit();
          } elseif (isset($_POST['modifier_unique'])) {
            $id = $_POST['modifier_unique'];
            if (isset($_POST['modifications'][$id])) {
              foreach ($_POST['modifications'][$id] as $colonne => $valeur) {
                $requeteModification = $connexion->prepare("UPDATE $table5 SET $colonne = ? WHERE id_hos = ?");
                $requeteModification->execute([$valeur, $id]);
              }
            }
            header("Location: admin_hos.php"); // Redirection après toutes les modifications
            exit();
          }
        }


        // Affichage des résultats
        echo "<h2>Hospitalisation</h2>";
        echo "<div class='table-container'>";
        echo "<form method='post' action='admin_hos.php'>";
        echo "<table border='2'>";
        echo "<tr><th>Sélection</th>";

        // Liste des colonnes à afficher (sans hidden_id_patient)
        $colonnesVisibles = ['id_hos', 'pré_ad', 'date_hos', 'heure', 'Nom_patient', 'Médecin', 'Adresse_mail_utilisateur', 'chambre_num', 'type_chambre'];
        foreach ($colonnesVisibles as $colonne) {
          echo "<th>$colonne</th>";
        }
        echo "<th>Actions</th></tr>";

        if (!empty($resultats1)) {
          foreach ($resultats1 as $ligne) {
            $idhos = $ligne['id_hos'];
            $idpatient = $ligne['hidden_id_patient']; // On récupère l'ID caché
      
            echo "<tr>";
            echo "<td><input type='checkbox' name='selection[]' value='" . htmlspecialchars($idhos, ENT_QUOTES, 'UTF-8') . "'></td>";

            // Affichez uniquement les colonnes visibles
            foreach ($colonnesVisibles as $colonne) {
              if ($colonne === 'Adresse_mail_utilisateur') {
                echo "<td>" . htmlspecialchars($ligne[$colonne], ENT_QUOTES, 'UTF-8') . "</td>";
              } else {
                echo "<td><input type='text' name='modifications[" . htmlspecialchars($idhos, ENT_QUOTES, 'UTF-8') . "][$colonne]' value='" . htmlspecialchars($ligne[$colonne], ENT_QUOTES, 'UTF-8') . "'></td>";
              }
            }

            // Boutons d'action avec utilisation de l'ID patient caché
            echo "<td>";
            echo "<a href='../pdf2.php?id_patient=" . htmlspecialchars($idpatient, ENT_QUOTES, 'UTF-8') . "' target='_blank' class='icon-btn'><i class='fas fa-file-pdf'></i></a>";
            echo "<button class='icon-btn' type='submit' name='modifier_unique' value='" . htmlspecialchars($idhos, ENT_QUOTES, 'UTF-8') . "'><i class='fas fa-pencil-alt'></i></button>";
            echo "<button class='icon-btn delete' type='submit' name='supprimer_unique' value='" . htmlspecialchars($idhos, ENT_QUOTES, 'UTF-8') . "' onclick='return confirm(\"Voulez-vous vraiment supprimer cette Hospitalisation ?\");'><i class='fas fa-trash'></i></button>";
            echo "</td>";
            echo "</tr>";
          }
        } else {
          // Calcul du nombre de colonnes (colonnes visibles + sélection + actions)
          $colspan = count($colonnesVisibles) + 2;
          echo "<tr><td colspan='$colspan'>Aucune donnée disponible</td></tr>";
        }

        echo "</table>";
        echo "<button class='btn delete-all' type='submit' name='supprimer'>Supprimer la sélection</button>";
        echo "</form>";
        echo "</div>";


      } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
      }
      ob_end_flush();
      ?>
    </div>
  </main>

  <footer></footer>
  <script src="../assets/script_admin/scripts_admin.js"></script>
  <!-- Script pour alterner le texte au clic -->
  <script>
    function toggleNomUtilisateur() {
      // Récupérer le bouton, le texte et le nom utilisateur
      const bouton = document.getElementById('btn-user');
      const texte = bouton.querySelector('.btn-text');
      const nomUtilisateur = bouton.getAttribute('data-nom');

      // Vérifier le texte actuel et basculer
      if (texte.textContent === "Utilisateur") {
        texte.textContent = nomUtilisateur; // Afficher le nom
      } else {
        texte.textContent = "Utilisateur"; // Revenir au texte par défaut
      }
    }
  </script>

  <script>
    //script des liste deroulante
    document.addEventListener("DOMContentLoaded", function () {
      const menuItems = document.querySelectorAll(".has-submenu > a");

      menuItems.forEach(item => {
        item.addEventListener("click", function (event) {
          event.preventDefault(); // Empêcher le lien de naviguer ailleurs

          const parentLi = this.parentElement;

          // Vérifier si ce menu est déjà ouvert
          const isOpen = parentLi.classList.contains("open");

          // Fermer tous les autres menus
          document.querySelectorAll(".has-submenu").forEach(menu => {
            menu.classList.remove("open");
          });

          // Ouvrir ou fermer le menu actuel
          if (!isOpen) {
            parentLi.classList.add("open");
          }
        });
      });

      // Fermer le menu si on clique ailleurs
      document.addEventListener("click", function (event) {
        if (!event.target.closest(".menu")) {
          document.querySelectorAll(".has-submenu").forEach(menu => {
            menu.classList.remove("open");
          });
        }
      });
    });

  </script>
</body>


</html>