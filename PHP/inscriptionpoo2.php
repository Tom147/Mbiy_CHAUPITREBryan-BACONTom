<?php

include 'conf.php';
$bdd = new PDO($db,$id,$mdp);

	$true = 0;

    class InscriptionMembre
    {
        private $pseudo;
        private $email;
        private $mdp;

        public function saveBDD($infosMembre)
        {
            $val1 = htmlspecialchars($_POST['pseudo']);
	        $val2 = htmlspecialchars($_POST['mail']);
	        $val3 = sha1($_POST['mdp']);

       		$req = $infosMembre->prepare("INSERT INTO membres (pseudo_membres, email_membres, mdp_membres) VALUES (:pseudo, :mail, :mdp)");
       		$req->execute(array('pseudo' => $val1,'mail' => $val2,'mdp' =>$val3));
        }

        public function __construct($pseudo, $email, $mdp)
        {
            $this->pseudo = $pseudo;
            $this->email = $email;
            $this->mdp = $mdp;
        }

        public function Verification()
        {
        	$bdd = new PDO('mysql:host=127.0.0.1;dbname=mbiy','root','');
        	$result = true;
			if(isset($_POST['forminscription']))
			{
				if (!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mdp']) ) 
				{
					$pseudoLength = strlen($pseudo);
					if ($pseudoLength <= 255) 
					{
						$reqmail = $bdd->prepare("SELECT * FROM membres WHERE email_membres = ?");
						$reqmail->execute(array($mail));
						$mailexiste = $reqmail->rowCount();

						if ($mailexiste == 0) 
						{
							$reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo_membres = ?");
							$reqpseudo->execute(array($pseudo));
							$pseudoexiste = $reqpseudo->rowCount();

							if ($pseudoexiste == 0) 
							{
								echo"vous avez bien était inscrit";
								header("Location: connexion.php");
							}
							else 
							{
								echo "pseudo existant";
								$result = false;
							}
						}
						else
						{
							echo "mailexiste";
							$result = false;
						}
					}

					else
					{
						$erreur ="Pseudo supérieur a 255 caractères";
						$result = false;
					}
       			}
       		}
       		return $result;
       	}
    }

    if (!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mdp'])) {
    	 $i = new InscriptionMembre($_POST['pseudo'], $_POST['mail'], $_POST['mdp']);
    	 if ($i->Verification()) {
    	 	$i->saveBDD($bdd);
    	 }
    }

?>


<!DOCTYPE html>
<html>
	<head>
		<title>MBiY - Inscription</title>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			<h2>Inscription</h2>

			<form method="POST" action="">
				<input type="text" name="pseudo" placeholder="Pseudo"><br>
				<input type="email" name="mail" placeholder="E-mail" id="mail"><br>
				<input type="password" name="mdp" placeholder="mot de passe" id="mdp"><br>

				<input type="submit" name="forminscription" value="Inscription">
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