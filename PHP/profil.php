<?php
session_start();

include 'conf.php';
$bdd = new PDO($db,$id,$mdp);

if(isset($_GET['id_membres']) AND $_GET['id_membres'] > 0) 
{
	$getid = intval($_GET['id_membres']); //converti ce que l'utilisateur rentre en nombre
	$requser = $bdd->prepare('SELECT * FROM membres WHERE id_membres = ?');
	$requser->execute(array($getid));
	$userinfo = $requser->fetch();
?>


<!DOCTYPE html>
<html>
	<head>
		<title>MBiY - Profil</title>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			<?php
			if(isset($_SESSION['id_membres']) AND $userinfo['id_membres'] == $_SESSION['id_membres']) 
			{
				?>
				<h2><?php echo $userinfo['pseudo_membres']; ?></h2>

				<?php
				if (!empty($userinfo['avatar_membres'])) 
				{
				?>
				<img src="../membres/avatars/<?php echo $userinfo['avatar_membres']; ?>"><br>
				<?php
				}
				?>
				
				<a href="editionprofil.php">modifier profil</a>
				<a href="POOAjout.php">Ajouter un livre</a>
				<a href="recherche.php">Rechercher un livre</a>
				<a href="deconnexion.php">Deconnexion</a>
				<?php
			}
			else
			{
				echo "erreur";
			}

			?>
		</div>
	</body>
</html>
<?php
}
?>