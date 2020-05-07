<?php
session_start();

include 'conf.php';
$bdd = new PDO($db,$id,$mdp);

if(isset($_POST['formconnexion']))
{
	$mailconnect = htmlspecialchars($_POST['mailconnect']);
	$mdpconnect = sha1($_POST['mdpconnect']);
	if (!empty($mailconnect) AND !empty($mdpconnect)) 
	{
		$requser = $bdd->prepare("SELECT * FROM membres WHERE email_membres = ? AND mdp_membres = ?");
		$requser->execute(array($mailconnect, $mdpconnect));
		$userexiste = $requser->rowCount();
		if ($userexiste == 1) 
		{
			$userinfo = $requser->fetch();
			$_SESSION['id_membres'] = $userinfo['id_membres'];
			$_SESSION['pseudo_membres'] = $userinfo['pseudo_membres'];
			$_SESSION['mail_membres'] = $userinfo['mail_membres'];
			header("Location: profil.php?id_membres=".$_SESSION['id_membres']);
		}
		else
		{
			$erreur = "email ou mot de passe invalide";
		}
	}
	else
	{
		$erreur = "tout les champs doivent etre connecté";
	}
}

?>


<!DOCTYPE html>
<html>
	<head>
		<title>MBiY - Connexion</title>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			<h2>Connexion</h2>

			<form method="POST" action="">
				<input type="email" name="mailconnect" placeholder="E-mail" id="mail"><br>
				<input type="password" name="mdpconnect" placeholder="mot de passe" id="mdp"><br>

				<input type="submit" name="formconnexion" value="Connexion">
			</form>
			<?php 
			if (isset($erreur)) 
			{
				echo $erreur;
			}
			 ?>
		</div>
	</body>
</html>