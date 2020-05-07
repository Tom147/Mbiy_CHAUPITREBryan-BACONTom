<?php 
session_start();

include 'conf.php';
$bdd = new PDO($db,$id,$mdp);

class Livre
{
    private $_titre;
    private $_auteur;
    private $_type;
    private $_genre;
    private $_cover;
    public $taillemax;
    public $extensionValides;

    public function __construct($titre, $auteur, $cover, $genre, $type)
    {
        $this->_titre = $titre;
        $this->_auteur = $auteur;
        $this->_genre = $genre;
        $this->_type = $type;
        $this->_cover = $cover;
    }

    public function saveBDD($instancePDO)
    {
        $req = $instancePDO->prepare("INSERT INTO `livres` (titre_livre, auteur_livre, image_livre, type_livre, genre_livre) VALUES (:Titre, :Auteur, :Cover, :Type, :Genre)");
        $req->execute(array(
            'Titre' => $this->_titre,
            'Auteur' => $this->_auteur,
            'Cover' => $this->CoverLivre(),
            'Type' =>$this->_type,
            'Genre' =>$this->_genre));
        return $req;
    }

    public function CoverLivre()
    {
        if (isset($_FILES['Cover']) AND $_FILES['Cover']['error'] == 0)
        {
            $taillemax = 2097152;
            $extensionValides = array('jpg', 'jpeg', 'gif', 'png');

            if ($_FILES['Cover']['size'] <= $taillemax AND $extensionValides) 
            {
                $url = '../covers/' . basename($_FILES['Cover']['name']);
                $res = move_uploaded_file($_FILES['Cover']['tmp_name'], $url);

                if($res)
                {

                return $url;
                
                }
            }
            else
            {
                echo "taille ou extension du fichier invalide";
            } 
        }
    }
}


if (!empty($_POST['Ajouter'])) {
   if(!empty($_POST['Titre']) AND !empty($_POST['Auteur']) AND !empty($_POST['Type']) AND !empty($_POST['Genre']) AND !empty($_FILES['Cover'])){
    $i = new Livre($_POST['Titre'], $_POST['Auteur'], $_FILES['Cover'], $_POST['Type'], $_POST['Genre']);
    $i->saveBDD($bdd);
    echo "Livre ajouté !";
    } 
    else
    {
    echo "erreur";
    }
}

?>

<div id="ajout">
    <h1 class="titre">Ajouter un livre</h1>
    <form  action="#ajout" method="POST" enctype="multipart/form-data">
        <input name="Titre" type="text" class="input" placeholder="Titre"/><br><br>
        <input name="Auteur" type="text" class="input" placeholder="Auteur"/><br><br>
        <input name="Cover" type="file"><br><br>
        <label for="Type">Choisissez un type</label>
        <select name="Type" size="1">
            <option value="BD">BD</option>
            <option value="Roman">Roman</option>
            <option value="Nouvelle">Nouvelle</option>
            <option value="Manga">Manga</option>
        </select><br><br>
        <label for="Genre">Genre :</label>
        <select name="Genre" size="1">
            <option value="Policier">Policier</option>
            <option value="Fantastique">Fantastique</option>
            <option value="Horreur">Horreur</option>
            <option value="Philosophie">Philosophie</option>
            <option value="Dystopie">Dystopie</option>
            <option value="Shonen">Shonen</option>
            <option value="Seinen">Seinen</option>
        </select><br><br><br>
        <input type="submit" name="Ajouter" id="Ajouter"/>
        <a href="profil.php?id_membres=<?php echo $_SESSION['id_membres']; ?>">Retour Profil</a>
    </form>
</div>

