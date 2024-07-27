<?php
require_once '../connexion.php';
require 'vendor/autoload.php'; // Assurez-vous d'avoir installé PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

require __DIR__ . '/config.php';
 

// Créer une nouvelle instance de Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Définir les en-têtes de colonnes
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nom');
$sheet->setCellValue('C1', 'Prénom');
$sheet->setCellValue('D1', 'Role');
$sheet->setCellValue('E1', 'Téléphone');
$sheet->setCellValue('F1', 'Adresse');
$sheet->setCellValue('G1', 'Email');

// Récupérer les données des membres
$query = "SELECT * FROM membre";
$resultat = $conn->query($query);

$rowNumber = 2;
while ($membre = $resultat->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNumber, $membre['id']);
    $sheet->setCellValue('B' . $rowNumber, $membre['nom']);
    $sheet->setCellValue('C' . $rowNumber, $membre['prenom']);
    $sheet->setCellValue('D' . $rowNumber, $membre['role']);
    $sheet->setCellValue('E' . $rowNumber, $membre['telephone']);
    $sheet->setCellValue('F' . $rowNumber, $membre['adresse']);
    $sheet->setCellValue('G' . $rowNumber, $membre['email']);
    $rowNumber++;
}

// Créer un writer pour le format Excel
$writer = new Xlsx($spreadsheet);

// Envoyer le fichier au navigateur pour le téléchargement
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="liste_des_membres.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
