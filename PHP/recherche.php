<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de recherche</title>
</head>
<body>
<h1>Rechercher un livre</h1>

<div class="rechercheForm">
    <form action="rechercher.php" method="GET">
        
        <input type="text" name="search" placeholder="Votre mot clé de recherche..">
       
        <select name="Type" size="1">
            <option value="">...</option>        
            <option value="BD">BD </option>
            <option value="Roman">Roman </option>
            <option value="Nouvelle">Nouvelle</option>
            <option value="Manga">Manga </option>
        </select><br><br>

        <select name="Genre" size="1">
            <option value="">...</option>
            <option value="Policier">Policier </option>
            <option value="Fantastique">Fantastique </option>
            <option value="Horreur">Horreur </option>
            <option value="Philosophie">Philosophie</option>
            <option value="Dystopie">Dystopie</option>
            <option value="Shonen">Shonen</option>
            <option value="Seinen">Seinen</option>
        </select><br><br><br>
       
        <input type="submit" name="submit" value="rechercher">
        <a href="profil.php?id_membres=<?php echo $_SESSION['id_membres']; ?>">Retour Profil</a>
        <style type="text/css">
            img {
                width: 60px;
            }
        </style>
        
    </form>
</div>
</body>
</html>