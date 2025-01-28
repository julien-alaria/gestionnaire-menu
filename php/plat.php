<?php

$pdo = new PDO("mysql:host=localhost;dbname=gestionnaire", "root", "");
$sql_select = $pdo->prepare("SELECT nom, ingredient, description_plat, prix FROM plats");
$sql_select->execute();
$rqt_select= $sql_select->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['nom_plat']) && isset($_POST['ingredient_plat']) && isset($_POST['description_plat']) && isset($_POST['prix_plat'])) {
    if (isset($_POST['submit'])) {
        $sql_insert = $pdo->prepare("INSERT INTO plats (nom, ingredient, prix, description_plat) VALUES (:nom_plat, :ingredient_plat, :prix_plat, :description_plat)");
        $sql_insert->execute([
            ':nom_plat' => $_POST['nom_plat'],
            ':ingredient_plat' => $_POST['ingredient_plat'],
            ':prix_plat' => $_POST['prix_plat'],
            ':description_plat' => $_POST['description_plat']
        ]);

        echo "Le plat a été ajouté avec succès.";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style1.css">
    <title>Document</title>
</head>
<body>
    <header>

    </header>
    
    <main>
        <div class="formulaire">
            <form action="plat.php" method="post">
                <h2>Ajouter un plat : </h2>

                <label for="nom_plat"> Nom du plat :
                    <input type="text" name="nom_plat" placeholder="nom du plat">
                </label>

                <label for="ingredient_plat"> Ingrédients du plat : 
                    <input type="text" name="ingredient_plat" placeholder="ingredient du plat">
                </label>

                <label for="description_plat"> Description du plat : 
                    <input type="text" name="description_plat" placeholder="description du plat">
                </label>

                <label for="prix_plat"> Prix du plat :
                    <input type="text" name="prix_plat" placeholder="prix du plat">
                </label>

                <label for="submit">
                    <input type="submit" name="submit" placeholder="Ajouter">
                </label>

            </form>
        </div>

        <h2>Plats</h2>
        <div class ='container_global'>
        <?php
        foreach($rqt_select as $lignes){
            echo 
        "<div class='container'>
        
            
            <h3>{$lignes['nom']}</h3>
            <h4>Ingrédients :</h4>
            <p>{$lignes['ingredient']}<p>
            <h4>Descritption du plat :</h4>
            <p>{$lignes['description_plat']}<p>
            <p>{$lignes['prix']}€<p>
            <input type='submit' name='supprimer' value='Supprimer'>
            <input type='submit' name='modifier' value='Modifier'>

        </div>";
        }
        ?>
        </div>

    </main>
</body>
</html>