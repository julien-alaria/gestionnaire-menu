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

        //verifier si le nom d'utilisateur existe deja
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE username = :username");
        $stmt->bindParam(':username', $nom);
        $stmt->execute();
        $usernameExists = $stmt->fetchColumn();

        //verifier si l'email existe deja
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($usernameExists > 0) {
            echo "Ce nom d'utilisateur est dejà pris.";
        } elseif ($emailExists > 0) {
            echo "Cet email est déjà enrrgistré.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Préparer la requête SQL d'insertion avec des paramètres
            $req = $pdo->prepare("INSERT INTO utilisateurs (username, email, hashed_password) VALUES (:username, :email, :hashedPassword)");

            // Lier les paramètres avec les valeurs
            $req->bindParam(':username', $nom);
            $req->bindParam(':email', $email);
            // $req->bindParam(':password', $password);
            $req->bindParam(':hashedPassword', $hashedPassword);

            // Exécuter la requête pour insérer les données dans la base
            $req->execute();

            echo "Utilisateur inscrit avec succès !";
            header("Location: http://localhost/files_laplateforme/gestionnaire-menu/connexion/connexion.php");
        }
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
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #77a464;
        }

        .container {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
            margin-top: 60px;
            margin: auto;
            background-color: #77a464;
            height: auto;
            width: auto;
        }

        .item {
            /* display: block;
            flex-direction: column;
            justify-content: center; */
            align-items: center;
            padding: 20px;
            margin: 20px;
            background-color: #deb887f7;
            border: 2px solid black;
            border-radius: 50px;
            height: 50vh;
            text-align: center;
        }

        input[type="text"] {
            margin: 20px;
            padding: 5px 10px;
            background-color: white;
            border: 2px solid black;
            border-radius: 10px;
            cursor: pointer;
        }

        input[type="password"] {
            margin: 20px;
            padding: 5px 10px;
            background-color: white;
            border: 2px solid black;
            border-radius: 10px;
            cursor: pointer;
        }

        input[type="email"] {
            margin: 20px;
            padding: 5px 10px;
            background-color: white;
            border: 2px solid black;
            border-radius: 10px;
            cursor: pointer;
        }

        input[type="submit"] {
            margin: 20px;
            padding: 5px 10px;
            background-color: #77a464;
            border: 2px solid black;
            border-radius: 10px;
            cursor: pointer;
        }

        .message {
            background-color: grey;
            color: black;
            padding: 10px;
            border: 2px solid black;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="item">
            <h1>Inscription Utilisateur</h1>
            <form action="inscription.php" method="POST">
                <label for="nom">Username :</label>
                <input type="text" id="username" name="username" required><br><br>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="genre">Password :</label>
                <input type="password" id="password" name="password">

                <input type="submit" value="S'inscrire">
            </form>
        </div>
    </div>

</body>

</html>