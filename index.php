<?php session_start(); ?>

<html>
<head>
  <title>GESTION DES CODES SOURCE DELPHI & ORACLE</Title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    

<div class="form-wrapper">

  <form action="#" method="post">

    <h3>Login Form</h3>
	
    <div class="form-item">
		<input type="text" name="mailconnect" required="required" placeholder="Nom utilisateur" autofocus required></input>
      
    </div>
    
    <div class="form-item">
		<input type="password" name="mdpconnect" required="required" placeholder="Mot de passe" required></input>
    </div>
    
    <div class="button-panel">
		<input type="submit" class="button" title="Log In" name="login" value="Se connecter"></input>
        <?php
	if (isset($_POST['login']))
		{
            $utilisateur = $_POST['mailconnect'];
			$motDePasse = $_POST['mdpconnect']; 
    
			$serveur = 'localhost/XE';
			$conn = oci_connect($utilisateur,$motDePasse,$serveur);
			
          
            if ($conn)
		 
            {
          
		header("Location: profil.php?user=".$utilisateur."&pwd=".$motDePasse);
	//echo "<script>window.location.href='profil.php';</script>";
		
             
            }
			
			else
				{
					echo 'Invalid Username and Password Combination';
				}
		}
  ?>
    </div>
  </form>
  
    
</div>

</body>
</html>