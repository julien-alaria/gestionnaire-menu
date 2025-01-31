<?php
session_start();

// Vérifier si l'ID de l'utilisateur est dans la session
if (!isset($_SESSION['id'])) {
    die("Utilisateur non connecté.");
}

// Vérifier si l'utilisateur a l'ID 33
// if ($_SESSION['id'] !== 33) {
//     die("Vous n'êtes pas autorisé à accéder à cette fonctionnalité.");
// }

echo "ID Utilisateur: " . $_SESSION['id'];

$host = "localhost";
$dbname = "gestionnaire";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Ajouter une catégorie
if (isset($_POST["categorie_nom"]) && isset($_POST["submit"])) {
    $categorie_nom = trim($_POST["categorie_nom"]);
    $tva = $_POST["tva"];

    if (!empty($categorie_nom) && !empty($tva)) {
        // Vérifier si la catégorie existe déjà
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM categorie WHERE id_utilisateur = :id_utilisateur AND nom = :categorie_nom");
        $stmt_check->bindParam(':id_utilisateur', $_SESSION['id']);
        $stmt_check->bindParam(':categorie_nom', $categorie_nom);
        $stmt_check->execute();
        $categorieExists = $stmt_check->fetchColumn();

        if ($categorieExists) {
            $_SESSION['message'] = "Cette catégorie est déjà prise.";
        } else {

            try {
                $stmt_insert = $pdo->prepare("INSERT INTO categorie (nom, id_utilisateur, TVA) VALUES (:categorie_nom, :id_utilisateur, :tva)");
                $stmt_insert->bindParam(':categorie_nom', $categorie_nom);
                $stmt_insert->bindParam(':tva', $tva);
                $stmt_insert->bindParam(':id_utilisateur', $_SESSION['id']);
                $stmt_insert->execute();

                $_SESSION['message'] = "La catégorie a été ajoutée avec succès.";

                // Redirection vers la même page pour rafraîchir et afficher le message
                header("Location: gestion_categorie.php");
                exit(); // Important de quitter après la redirection
            } catch (PDOException $e) {
                $_SESSION['message'] = "Erreur d'insertion : " . $e->getMessage();
            }
        }
    } else {
        $_SESSION['message'] = "Le nom de la catégorie ne peut pas être vide.";
    }
}

if (isset($_POST["delete"])) {
    $id = $_POST["delete"];

    $stmt_delete = "DELETE FROM categorie WHERE id_categorie = :id_categorie";
    $stmt_delete = $pdo->prepare($stmt_delete);
    $stmt_delete->execute([':id_categorie' => $id]);

    $_SESSION['message'] = "La catégorie a été supprimée avec succès.";
    header("Location: gestion_categorie.php");
    exit();
}

$stmt_select = $pdo->query("SELECT * FROM categorie");
$categories = $stmt_select->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des catégories</title>
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
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            margin-top: 60px;
            margin: auto;
            background-color: #77a464;
            height: auto;
            width: auto;
        }

        table {
            margin: auto;
            width: 80vw;
            max-height: auto;

        }

        th {
            border: 2px solid black;
            border-collapse: collapse;
            padding: 10px;
            text-align: center;
            background-color: grey;
            border-radius: 5px;
        }

        td {
            border: 2px solid black;
            border-collapse: collapse;
            padding: 10px;
            text-align: center;
            padding: 10px;
            text-align: center;
            background-color: wheat;
            border-radius: 5px;
        }

        .item {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 20px;
            background-color: #deb887f7;
            border: 2px solid black;
            border-radius: 50px;
            height: auto;
            text-align: center;
        }

        .item h1 {
            padding: 15px;
            margin: 5px;
        }

        input[type="submit"] {
            padding: 5px 10px;
            background-color: #77a464;
            border: 2px solid black;
            border-radius: 10px;
            cursor: pointer;
        }

        input[type="text"] {
            margin: 20px;
            padding: 5px 10px;
            background-color: white;
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

        .tva {
            display: flex;
            flex-direction: column;
            padding: 5px;
        }

        div #ajouter_categorie {
            background-color: grey;
        }
    </style>

</head>

<body>

    <div class="container">


        <div class="item">
            <h1>Gestion des Catégories</h1>
            <form action="gestion_categorie.php" method="POST">

                <div class="item" id="ajouter_categorie">

                    <label for="categorie_nom"><strong>Ajouter une catégorie :</strong></label>

                    <input type="text" id="categorie_nom" name="categorie_nom" placeholder="Nom de la catégorie" required>
                    <br>

                    <label for="tva"><strong>TVA :</strong></label>
                    <div class="tva" id="tva">
                        <label for=""><input type="radio" name="tva" value="10" required>10%</label>
                        <label for=""><input type="radio" name="tva" value="20" required>20%</label>
                    </div>

                    <input type="submit" name="submit" value="Valider">

                </div>
            </form>


            <form action="gestion_categorie.php" method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Catégories</th>
                            <th>Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php
                        foreach ($categories as $lignes) {
                            echo "<tr>
                                    <td>{$lignes['nom']}</td>
                                    <td>
                                        <form method='POST' action='gestion_categorie.php' style='display:inline'>
                                     
                                            <input type='hidden' name='delete' value='{$lignes['id_categorie']}'>
                                            <input type='submit' value='Supprimer'>
                                        </form>
                                    </td>
                                  </tr>";
                        }

                        if (isset($_SESSION['message'])) {
                            echo "<div class='message'>" . $_SESSION['message'] . "</div>";
                            // Supprimer le message après l'affichage
                            unset($_SESSION['message']);
                        }
                        ?>


                    </tbody>
                </table>
            </form>
        </div>

        <!-- <div class="item">
            <form action="gestion_categorie.php" method="POST">
                <label for="categorie_nom"><strong>Ajouter une catégorie :</strong></label>
                <input type="text" id="categorie_nom" name="categorie_nom" placeholder="Nom de la catégorie" required>
                <br>
                <label for="tva"><strong>TVA :</strong></label>
                <div class="tva" id="tva">
                <label for=""><input type="radio" name="tva" value="10" required>10%</label>
                <label for=""><input type="radio" name="tva" value="20" required>20%</label>
                </div>

                <input type="submit" name="submit" value="Valider">
            </form>
        </div> -->


    </div>

</body>

</html>