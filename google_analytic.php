<?php
include("../connexion.php");

// Récupère l'adresse IP du visiteur
$ip_address = $_SERVER['REMOTE_ADDR'];

// Insère une nouvelle visite dans la base de données
$stmt = $conn->prepare("INSERT INTO visites (ip_address) VALUES (?)");
$stmt->bind_param("s", $ip_address);
$stmt->execute();
$stmt->close();
$conn->close();
?>

<?php
// Afficher le nombre de visiteurs
$result = $conn->query("SELECT COUNT(*) as total_visitors FROM visites");
$row = $result->fetch_assoc();
$total_visitors = $row['total_visitors'];

echo "Nombre total de visiteurs : " . $total_visitors;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Autres balises head -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXXX-X"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-XXXXXX-X');
    </script>
</head>
<body>
    
</body>
</html>
