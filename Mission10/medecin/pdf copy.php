<?php
// Démarrer la session (si ce n'est pas déjà fait)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'email de l'administrateur est dans la session
if (!isset($_SESSION['admin_email'])) {
    header("Location:../404/404.html");
    exit();
}

// Récupérer l'email de l'administrateur
$admin_email = htmlspecialchars($_SESSION['admin_email'], ENT_QUOTES, 'UTF-8');

// Récupérer les paramètres de filtre
$mois = isset($_GET['mois']) ? $_GET['mois'] : '';
$annee = isset($_GET['annee']) ? $_GET['annee'] : '';

// Extraire la partie avant '@' de l'email
$admin_name = htmlspecialchars(explode('@', $admin_email)[0], ENT_QUOTES, 'UTF-8');

// Connexion à la BDD
$bddname = 'AP_SIO2';
$hostname = 'localhost';
$username = 'root';
$password = 'sio2024';
$db = mysqli_connect($hostname, $username, $password, $bddname);

// Vérifier la connexion
if (!$db) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Appel de la librairie FPDF
require("../fpdf/fpdf.php");

// Création de la classe PDF
class PDF extends FPDF
{
    // Header
    function Header()
    {
        // Logo
        $this->Image('../assets/img/logo.png', 8, 2, 50);

        // Ligne verticale
        $x = 8 + 50 + 2;
        $y1 = 2;
        $y2 = 45;

        $this->Line($x, $y1, $x, $y2);

        // Définir une police pour le texte
        $this->SetFont('Times', 'BI', 12);

        // Ajout de texte
        $t = 'LPFS clinique';
        $this->Text(62, 20, $t);
        $t1 = utf8_decode('70 Rue des Champs Elysées');
        $this->Text(62, 25, $t1);
        $t2 = utf8_decode('lpfs.clinique@lpsf.com');
        $this->Text(62, 31, $t2);
        $this->Ln(20);
    }

    // Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->Cell(196, 5, utf8_decode('LPFS_CLINIQUE - lpfs.clinique@lpfs.com'), 0, 0, 'C');
    }
}

// Création du document PDF
$pdf = new PDF('P', 'mm', 'A3');
$pdf->AddPage();
$pdf->SetFont('Times', 'I', 11);
$pdf->SetTextColor(0);
$pdf->SetFont('Times', 'BI', 20);

// Ajouter la période filtrée dans le titre si un filtre est appliqué
$titre = 'LISTE DE RENDEZ-VOUS';
if (!empty($mois) || !empty($annee)) {
    $titre .= ' (';
    if (!empty($mois)) {
        $mois_noms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $titre .= $mois_noms[$mois - 1];
    }
    if (!empty($annee)) {
        if (!empty($mois))
            $titre .= ' ';
        $titre .= $annee;
    }
    $titre .= ')';
}

$pdf->Text(100, 75, utf8_decode($titre));

// Requête SQL de base
$req = "SELECT patient.nom, patient.prénom, hospitalisation.date_hos, hospitalisation.heure, 
        hospitalisation.pré_ad, hospitalisation.chambre_num, professionnels.Nom, services.service 
        FROM patient
        JOIN hospitalisation ON patient.id_patient = hospitalisation.id_patient
        JOIN professionnels ON hospitalisation.Adresse_mail = professionnels.Adresse_mail
        JOIN services ON professionnels.id_service = services.id_service
        WHERE hospitalisation.Adresse_mail = ?";

// Ajout des conditions de filtre
if (!empty($annee)) {
    $req .= " AND YEAR(hospitalisation.date_hos) = ?";
}
if (!empty($mois)) {
    $req .= " AND MONTH(hospitalisation.date_hos) = ?";
}

// Préparer la requête
$stmt = mysqli_prepare($db, $req);

// Construction dynamique des paramètres
$types = 's';
$params = array($admin_email);

if (!empty($annee)) {
    $types .= 'i';
    $params[] = $annee;
}
if (!empty($mois)) {
    $types .= 'i';
    $params[] = $mois;
}

// Liaison des paramètres
mysqli_stmt_bind_param($stmt, $types, ...$params);

// Exécuter la requête
mysqli_stmt_execute($stmt);

// Obtenir les résultats
$result = mysqli_stmt_get_result($stmt);

// Ajout du tableau des rendez-vous
// Ajout du tableau des rendez-vous
$pdf->SetFont('Times', 'B', 12);
$pdf->SetXY(10, 100);

// Couleur d'en-tête
$pdf->SetFillColor(17, 150, 250); // #1196FA
// $pdf->SetFillColor(79, 129, 189); // Bleu clair
$pdf->SetTextColor(255, 255, 255); // Texte blanc

// En-têtes du tableau
$pdf->Cell(33, 10, mb_convert_encoding('Nom', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(35, 10, mb_convert_encoding('Prénom', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(39, 10, mb_convert_encoding('Type d\'admission', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(35, 10, mb_convert_encoding('Date du RDV', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(35, 10, mb_convert_encoding('Heure du RDV', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(35, 10, mb_convert_encoding('Chambre', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(35, 10, mb_convert_encoding('Spécialiste', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(35, 10, mb_convert_encoding('Service', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', true);

// Ajout des données du patient dans le tableau
$pdf->SetFont('Times', '', 12);
$pdf->SetTextColor(0, 0, 0); // Texte noir
$fill = false; // Pour alterner les couleurs

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $fill = !$fill;
        $pdf->SetFillColor($fill ? 79 : 255, $fill ? 129 : 255, $fill ? 189 : 255);

        $pdf->Cell(33, 10, mb_convert_encoding($row['nom'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fill);
        $pdf->Cell(35, 10, mb_convert_encoding($row['prénom'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fill);
        $pdf->Cell(39, 10, mb_convert_encoding($row['pré_ad'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fill);
        $pdf->Cell(35, 10, mb_convert_encoding($row['date_hos'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fill);
        $pdf->Cell(35, 10, mb_convert_encoding($row['heure'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fill);
        $pdf->Cell(35, 10, mb_convert_encoding($row['chambre_num'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fill);
        $pdf->Cell(35, 10, mb_convert_encoding($row['Nom'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fill);
        $pdf->Cell(35, 10, mb_convert_encoding($row['service'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', $fill);
    }
} else {
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Cell(240, 10, mb_convert_encoding('Aucun rendez-vous trouvé pour cette période.', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', true);
}

$pdf->SetFont('Times', 'I', 12);
$pdf->SetXY(20, 190);

// Ajout d'une image
$pdf->Image('../assets/img/cachet.png', 129, 215, 50);

// Affichage du PDF
$pdf->Output();
?>