<?php
// Vérifier si les données ont été envoyées via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données envoyées par le formulaire
    $nom = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    try {
        // Connexion à la base de données MySQL avec PDO
        $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête SQL d'insertion avec des paramètres
        $req = $pdo->prepare("INSERT INTO utilisateurs (username, email, password) VALUES (:username, :email, :password)");

        // Lier les paramètres avec les valeurs
        $req->bindParam(':username', $nom);
        $req->bindParam(':email', $email);
        $req->bindParam(':password', $password);

        // Exécuter la requête pour insérer les données dans la base
        $req->execute();

        echo "Utilisateur inscrit avec succès !";
    } catch (PDOException $e) {
        echo "Erreur de connexion ou d'exécution : " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription User gestionnaire de menu</title>
</head>

<body>

    <h1>Inscription User</h1>
    <form action="inscription.php" method="POST">
        <label for="nom">Username :</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="genre">Password :</label>
        <input type="password" id="password" name="password">

        <input type="submit" value="S'inscrire">
    </form>

</body>

</html>