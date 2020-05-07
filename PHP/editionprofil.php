<?php
session_start();

include 'conf.php';
$bdd = new PDO($db,$id,$mdp);

if(isset($_SESSION['id_membres'])) 
{
	$requser = $bdd->prepare('SELECT * FROM membres WHERE id_membres = ?');
	$requser->execute(array($_SESSION['id_membres']));
	$user = $requser->fetch();

	//MAJ PSEUDO
	if (isset($_POST['newpseudo']) AND !empty($_POST['newpseudo'] AND $_POST['newpseudo'] != $user['pseudo_membres'])) 
	{
		$newpseudo = htmlspecialchars($_POST['newpseudo']);
		$newpseudoLength = strlen($newpseudo);
		if ($newpseudoLength <= 255)
		{
			$insertpseudo = $bdd->prepare("UPDATE membres SET pseudo_membres = ? WHERE id_membres = ?"); //Si absence de WHERE id, MAJ  de tout les pseudo de la Table
			$insertpseudo->execute(array($newpseudo, $_SESSION['id_membres']));
			header('Location: profil.php?id_membres='.$_SESSION['id_membres']);
		}
	}

	//MAJ MAIL
	if (isset($_POST['newmail']) AND !empty($_POST['newmail'] AND $_POST['newmail'] != $user['email_membres'])) 
	{
		$newmail = htmlspecialchars($_POST['newmail']);
		$insertmail = $bdd->prepare("UPDATE membres SET email_membres = ? WHERE id_membres = ?"); //Si absence de WHERE id, MAJ  de tout les pseudo de la Table
		$insertmail->execute(array($newmail, $_SESSION['id_membres']));
		header('Location: profil.php?id_membres='.$_SESSION['id_membres']);
	}

	//MAJ MDP
	if (isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2'])) 
	{
		$mdp1 = sha1($_POST['newmdp1']);
		$mdp2 = sha1($_POST['newmdp2']);

		if ($mdp1 == $mdp2) 
		{
			$insertmdp = $bdd->prepare("UPDATE membres SET mdp_membres = ? WHERE id_membres = ?");
			$insertmdp->execute(array($mdp1, $_SESSION['id_membres']));
			header('Location: profil.php?id_membres='.$_SESSION['id_membres']);
		}
		else
		{
			echo "les mdp ne correspondent pas";
		}
	}

	//MAJ Photo
	if (isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])) 
	{
		$taillemax = 2097152;
		$extensionValides = array('jpg', 'jpeg', 'gif', 'png');
		if ($_FILES['avatar']['size'] <= $taillemax) 
		{
			$extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
			if (in_array($extensionUpload, $extensionValides)) 
			{
				$chemin = "membres/avatars/".$_SESSION['id_membres'].".".$extensionUpload;
				$resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
				if ($resultat) 
				{
					$updateavatar = $bdd->prepare('UPDATE membres SET avatar_membres = :avatar_membres WHERE id_membres = :id_membres');
					$updateavatar->execute(array('avatar_membres' => $_SESSION['id_membres'].".".$extensionUpload, 'id_membres' => $_SESSION['id_membres']));
				}
				else
				{
					echo "error";
				}
			}
			else
			{
				echo "erreur de format";
			}
		}
		else 
		{
			echo "taille supérieur a 2 Mo";
		}
	}
	
?>


<!DOCTYPE html>
<html>
	<head>
		<title>MBiY - Profil</title>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			<h2>Edition Profil</h2>
			
			<form method="POST" enctype="multipart/form-data">
				<label>Pseudo :</label>
				<input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo_membres']?>"><br>
				<label>E-mail :</label>
				<input type="email" name="newmail" placeholder="Mail" value="<?php echo $user['email_membres']?>"><br>
				<label>MDP :</label>
				<input type="password" name="newmdp1" placeholder="Mot de passe"><br>
				<label>MDP :</label>
				<input type="password" name="newmdp2" placeholder="Confirmation mot de passe"><br>
				<label>Photo de profil :</label>
				<input type="file" name="avatar"><br>
				<input type="submit" name="submit" placeholder="Mettre a jour"><br>
			</form>
		</div>
	</body>
</html>
<?php
}
else
{
	header("Location: connexion.php");
}
?>