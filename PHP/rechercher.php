<?php
session_start();

include 'conf.php';
$bdd = new PDO($db,$id,$mdp);

if (isset($_GET['submit']) ){

    $params = array();


$str = "";
    if($_GET['search'] !== "")
    {
        $str = " (titre_livre LIKE ? OR auteur_livre LIKE ?) ";
        array_push($params, "%".$_GET['search']."%");
        array_push($params, "%".$_GET['search']."%");

    }

    $genre = "";
    if($_GET['Genre'] !== "")
    {
        if($str !== "") $genre = " AND ";
        $genre .= "  genre_livre = ? ";
        array_push($params, $_GET['Genre']);
    }

    $type = "";
    if($_GET['Type'] !== "")
    {
        if($genre !== "" || $str !== "") $type = " AND ";
        $type .= "  type_livre = ? ";
        array_push($params, $_GET['Type']);

    }


    $sql = "SELECT * FROM livres WHERE  $str $genre $type  ";
    $req = $bdd->prepare($sql);
    $req->execute($params);

    if($req->rowCount() > 0){
        echo "Voici les résultats de votre recherche :";
    } else{
        echo "Aucun résultats";
    }

    $tableau = $req->fetchAll();

    foreach ($tableau as $value){
        echo'titre : '. $value['titre_livre']. ' | ';
        echo'auteur : '. $value['auteur_livre']. ' | ';
        echo 'genre :'. $value['genre_livre']. ' | ';
        echo 'type : '. $value['type_livre']. ' | ';
        echo "Couverture : <img src='".$value["image_livre"]."' />";
        echo'<br>';
    }

 
} else {
    echo "Votre saisie est invalide";
}
?>