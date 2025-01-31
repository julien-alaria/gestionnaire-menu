 <?php
    session_start();

    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Récupérer les données envoyées par le formulaire
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Connexion à la base de données MySQL avec PDO
            $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparer la requête SQL pour récupérer l'utilisateur avec le nom d'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Vérifier si l'utilisateur existe
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            var_dump($user);

            if ($user) {
                // Comparer les mots de passe en texte clair
                // if ($password === $user['password']) {
                if (password_verify($password, $user['hashed_password'])) {
                    // Mot de passe correct, connexion réussie
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['id'] = $user['id_utilisateur'];

                    // Redirection vers phpMyAdmin ou une page sécurisée
                    echo "Connection réussie";
                  
                    header('Location:http://localhost/files_laplateforme/gestionnaire-menu/gestion_categorie/gestion_categorie.php');
                    exit;
                } else {
                    // Rediriger vers la page de connexion avec message d'erreur pour mot de passe incorrect
                    header('Location: connexion.php?error=1');
                    exit;
                }
            } else {
                // Rediriger vers la page de connexion avec message d'erreur pour utilisateur incorrect
                header('Location: connexion.php?error=2');
                exit;
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de connexion à la base de données
            echo "Erreur de connexion ou d'exécution : " . $e->getMessage();
        }
    }
    ?>

 <!DOCTYPE html>
 <html lang="fr">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Connexion User gestionnaire_menu</title>
     <style>
    
        *{
            margin: 0;
            padding: 0;
        }

        body{
            background-color: #77a464;
        }

        .container {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            margin-top: 60px; 
            margin: auto;
            background-color: #77a464;
            height: auto;
            width: auto;
        }
        
        .item {
            justify-content: center;
            align-items: center;
            padding: 50px;
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
            background-color:white;
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

     <!-- Vérification du paramètre d'erreur dans l'URL -->
     <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == '1') {
                echo "<p style='color: red;'>Mot de passe incorrect.</p>";
            } elseif ($_GET['error'] == '2') {
                echo "<p style='color: red;'>Nom d'utilisateur incorrect.</p>";
            }
        }
     ?>
    
    <div class="container">
        <div class="item">
            <h1>Connexion Utilisateur</h1>
            <!-- Formulaire de connexion -->
            <form action="connexion.php" method="POST">
                <label for="username">Username :</label>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Password :</label>
                <input type="password" id="password" name="password"><br><br>

                <input type="submit" value="Valider">
            </form>
        </div>
    </div>

 </body>

 </html>