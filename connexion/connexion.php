<?php
    // Paramètres de connexion à la base de données
    $servername = "localhost"; // Serveur de la base de données
    $username = "root"; // Nom d'utilisateur pour la connexion à la base de données
    $password = ""; // Mot de passe pour la connexion à la base de données
    $dbname = "afbci"; // Nom de la base de données

    // Création d'une nouvelle connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérification de la connexion à la base de données
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Arrêt du script en cas d'échec de connexion
    }
?>