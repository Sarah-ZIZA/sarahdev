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

            <li><a href="admin_couvert.php" data-target="list-hospital">Liste descouverture_scl</a></li>
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
    <form method="get" class="search" action="admin_patient.php">
      <input class="recherche" type="text" name="recherche_page5"
        style="width: 50%; height:41px; border:none;border-radius:10px;"
        placeholder="Rechercher un patient (ID, nom ou prénom)"
        value="<?php echo htmlspecialchars($_GET['recherche_page5'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
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

        $table1 = "patient";
        $recherche = $_GET['recherche_page5'] ?? '';

        // Requête de recherche
        if ($recherche) {
          $requete1 = $connexion->prepare("SELECT * FROM $table1 WHERE id_patient LIKE ? OR nom LIKE ? OR prénom LIKE ?");
          $requete1->execute(['%' . $recherche . '%', '%' . $recherche . '%', '%' . $recherche . '%']);
        } else {
          $requete1 = $connexion->prepare("
          SELECT 
              p.id_patient, 
              p.civilité, 
              p.nom, 
              p.prénom, 
              p.nom_épouse, 
              p.date_naissance, 
              p.adresse_patient, 
              p.CP, 
              p.ville, 
              p.email, 
              p.tel_patient, 
              p.num_sécu, 
              p.carte_vitale, 
              p.carte_identité, 
              p.carte_mutuelle, 
              p.livretF,
              pc.nom AS personne_contact_nom,
              pc.prenom AS personne_contact_prenom,
              pc.tel AS personne_contact_tel,
              pc.adresse AS personne_contact_adresse,
              pcf.nom AS personne_confiance_nom,
              pcf.prenom AS personne_confiance_prenom,
              pcf.tel AS personne_confiance_tel,
              pcf.adresse AS personne_confiance_adresse
          FROM 
              patient p
          LEFT JOIN 
              personne_contact pc ON p.id_personne1 = pc.id_personne1
          LEFT JOIN 
              personne_confiance pcf ON p.id_personne2 = pcf.id_personne2
      ");
          $requete1->execute();
        }


        $resultats1 = $requete1->fetchAll(PDO::FETCH_ASSOC);


        // Gestion des suppressions et modifications
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if (isset($_POST['supprimer']) && !empty($_POST['selection'])) {
            $ids = $_POST['selection'];
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // 1. Récupérer les id_personne1 et id_personne2 des patients
            $stmt = $connexion->prepare("SELECT id_personne1, id_personne2 FROM patient WHERE id_patient IN ($placeholders)");
            $stmt->execute($ids);
            $personnes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ids_p1 = [];
            $ids_p2 = [];

            foreach ($personnes as $p) {
              $ids_p1[] = $p['id_personne1'];
              $ids_p2[] = $p['id_personne2'];
            }

            // 2. Supprimer les patients d'abord
            $stmt3 = $connexion->prepare("DELETE FROM patient WHERE id_patient IN ($placeholders)");
            $stmt3->execute($ids);

            // 3. Ensuite supprimer les personnes contact
            if (!empty($ids_p1)) {
              $placeholders1 = implode(',', array_fill(0, count($ids_p1), '?'));
              $stmt1 = $connexion->prepare("DELETE FROM personne_contact WHERE id_personne1 IN ($placeholders1)");
              $stmt1->execute($ids_p1);
            }

            // 4. Puis les personnes de confiance
            if (!empty($ids_p2)) {
              $placeholders2 = implode(',', array_fill(0, count($ids_p2), '?'));
              $stmt2 = $connexion->prepare("DELETE FROM personne_confiance WHERE id_personne2 IN ($placeholders2)");
              $stmt2->execute($ids_p2);
            }
            header("Location: admin_patient.php"); // Redirection pour éviter la double soumission
            exit();
          } elseif (isset($_POST['supprimer_unique'])) {
            $id = $_POST['supprimer_unique'];
            $requeteSuppression = $connexion->prepare("DELETE patient, personne_contact, personne_confiance
            FROM patient
            JOIN personne_contact ON patient.id_personne1 = personne_contact.id_personne1
            JOIN personne_confiance ON patient.id_personne2 = personne_confiance.id_personne2 WHERE id_patient = ?");
            $requeteSuppression->execute([$id]);
            header("Location: admin_patient.php"); // Redirection pour éviter la double soumission
            exit();
          } elseif (isset($_POST['modifier_unique'])) {
            $id = $_POST['modifier_unique'];
            if (!empty($_POST['modifications'][$id])) {
              try {
                $connexion->beginTransaction();

                // Récupérer les id_personne associés et l'ancien num_sécu
                $stmt = $connexion->prepare("SELECT id_personne1, id_personne2, num_sécu FROM patient WHERE id_patient = ?");
                $stmt->execute([$id]);
                $patient = $stmt->fetch(PDO::FETCH_ASSOC);
                $id_personne1 = $patient['id_personne1'];
                $id_personne2 = $patient['id_personne2'];
                $ancien_num_secu = $patient['num_sécu'];

                // Préparer les tableaux pour les mises à jour
                $patient_data = [];
                $contact_data = [];
                $confiance_data = [];
                $nouveau_num_secu = null;

                foreach ($_POST['modifications'][$id] as $colonne => $valeur) {
                  // Champs de la table patient
                  if (
                    in_array($colonne, [
                      'civilité',
                      'nom',
                      'prénom',
                      'nom_épouse',
                      'date_naissance',
                      'adresse_patient',
                      'CP',
                      'ville',
                      'email',
                      'tel_patient',
                      'num_sécu',
                      'carte_vitale',
                      'carte_identité',
                      'carte_mutuelle',
                      'livretF'
                    ])
                  ) {

                    $patient_data[$colonne] = $valeur;

                    // Si c'est le num_sécu qu'on modifie
                    if ($colonne == 'num_sécu' && $valeur != $ancien_num_secu) {
                      $nouveau_num_secu = $valeur;
                    }
                  }
                  // Champs de la table personne_contact
                  elseif (in_array($colonne, ['contact_nom', 'contact_prenom', 'contact_tel', 'contact_adresse'])) {
                    $contact_data[str_replace('contact_', '', $colonne)] = $valeur;
                  }
                  // Champs de la table personne_confiance
                  elseif (in_array($colonne, ['confiance_nom', 'confiance_prenom', 'confiance_tel', 'confiance_adresse'])) {
                    $confiance_data[str_replace('confiance_', '', $colonne)] = $valeur;
                  }
                }

                // Mise à jour patient
                if (!empty($patient_data)) {
                  $set_patient = implode(', ', array_map(fn($k) => "$k = ?", array_keys($patient_data)));
                  $req_patient = $connexion->prepare("UPDATE patient SET $set_patient WHERE id_patient = ?");
                  $req_patient->execute([...array_values($patient_data), $id]);
                }

                // Mise à jour couverture SI num_sécu modifié
                if ($nouveau_num_secu !== null && $nouveau_num_secu != $ancien_num_secu) {
                  $req_couverture = $connexion->prepare("UPDATE couverture SET num_sécu = ? WHERE id_patient = ?");
                  $req_couverture->execute([$nouveau_num_secu, $id]);
                }

                // Mise à jour personne_contact
                if (!empty($contact_data) && $id_personne1) {
                  $set_contact = implode(', ', array_map(fn($k) => "$k = ?", array_keys($contact_data)));
                  $req_contact = $connexion->prepare("UPDATE personne_contact SET $set_contact WHERE id_personne1 = ?");
                  $req_contact->execute([...array_values($contact_data), $id_personne1]);
                }

                // Mise à jour personne_confiance
                if (!empty($confiance_data) && $id_personne2) {
                  $set_confiance = implode(', ', array_map(fn($k) => "$k = ?", array_keys($confiance_data)));
                  $req_confiance = $connexion->prepare("UPDATE personne_confiance SET $set_confiance WHERE id_personne2 = ?");
                  $req_confiance->execute([...array_values($confiance_data), $id_personne2]);
                }

                $connexion->commit();
                header("Location: admin_patient.php");
                exit();
              } catch (PDOException $e) {
                $connexion->rollBack();
                echo "Erreur : " . $e->getMessage();
              }
            }
          }
        }


        // Affichage des résultats
        echo "<h2>Patients</h2>";
        echo "<div class='table-container'>";
        echo "<form method='post' action='admin_patient.php'>";
        echo "<table border='2'>";
        echo "<tr><th>Sélection</th>";

        if (!empty($resultats1)) {
          foreach ($resultats1[0] as $colonne => $valeur) {
            echo "<th>$colonne</th>";
          }
          echo "<th>Actions</th></tr>";

          foreach ($resultats1 as $ligne) {
            $idpatient = $ligne['id_patient'];
            echo "<tr>";
            echo "<td><input type='checkbox' name='selection[]' value='" . htmlspecialchars($idpatient, ENT_QUOTES, 'UTF-8') . "'></td>";

            foreach ($ligne as $colonne => $valeur) {
              if ($colonne === 'id_patient') {
                echo "<td>" . htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8') . "</td>";
              } else {
                echo "<td><input type='text' name='modifications[" . htmlspecialchars($idpatient, ENT_QUOTES, 'UTF-8') . "][" . htmlspecialchars($colonne, ENT_QUOTES, 'UTF-8') . "]' value='" . htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8') . "'></td>";
              }
            }

            // Boutons Modifier et Supprimer
            echo "<td>";
            // echo "<a href='../pdf2.php?id_patient=" . htmlspecialchars($idpatient, ENT_QUOTES, 'UTF-8') . "' target='_blank' class='icon-btn'><i class='fas fa-file-pdf'></i></a>";
            echo "<button class='icon-btn' type='submit' name='modifier_unique' value='" . htmlspecialchars($idpatient, ENT_QUOTES, 'UTF-8') . "'><i class='fas fa-pencil-alt'></i></button>";
            echo "<button class='icon-btn delete' type='submit' name='supprimer_unique' value='" . htmlspecialchars($idpatient, ENT_QUOTES, 'UTF-8') . "' onclick='return confirm(\"Voulez-vous vraiment supprimer ce patient ?\");'><i class='fas fa-trash'></i></button>";
            echo "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='100%'>Aucune donnée disponible</td></tr>";
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