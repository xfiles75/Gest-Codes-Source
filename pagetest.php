<html>
	   <head>
	      <title>TUTO PHP</title>
	      <meta charset="utf-8">
		  <style>
        .cadre {
            border: 1px solid black;
            padding: 30px;
            width: 150px;
        }
        .pressed {
      background-color: #ccc;
    }
    </style>
	   </head>
	   <body>
      
	  <div align="right">
       <a href="deconnexion.php">Déconnecter</a>
             
            
			</div>
 <div align="left">
	         <h2>INSFP-RELIZANE </h2>
			 
        </div> 

            <div align="center">
	         <h2>GESTION DES CODES SOURCE : ORACLE-DELPHI </h2>
			 <h2>----------------------------------------------- </h2>
             </div>   
			
      
			

<script>
function copierDansPressePapier() {
    // Sélection du contenu de la zone de texte
    var memo = document.getElementById('memo');
    memo.select();
    memo.setSelectionRange(0, 99999); // Pour les navigateurs mobiles

    // Copie du contenu dans le presse-papier
    document.execCommand('copy');

    // Affichage d'un message de confirmation
    alert("Le code a été copié dans le presse-papier !");
}
</script>
    
    <?php
  
    function affichememo($texte){
        $nombreLignes = substr_count($texte, "\n");

        // Division du texte en lignes
        $lignes = explode("\n", $texte);
        
        // Variables pour le maximum
        $maxLigne = '';
        $maxCaracteres = 0;
        $maxcol=0; 
        // Parcours des lignes
        foreach ($lignes as $ligne) {
           
            // Calcul de la longueur de la ligne
            $longueur = strlen($ligne);
        
            // Mise à jour du maximum
            if ($longueur > $maxCaracteres) {
                $maxLigne = $ligne;
                $maxCaracteres = $longueur;
            }
        if ($maxCaracteres > $maxcol){$maxcol=$maxCaracteres;}
        
        // Affichage du résultat
        //echo "Ligne avec le maximum de caractères : " . $maxLigne . "<br>";
        //echo "Nombre de caractères : " . $maxCaracteres . "<br>";
     
        }
 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            echo '<textarea id="memo" rows="'.$nombreLignes.'" cols="'.$maxcol.'">';
            echo $texte ;
            echo'</textarea>';
         //   echo "<button style='background-color:#00FF00' onclick='copierDansPressePapier()'>Copier le code source</button>";
            
        }
    }

	
    
    if ((isset($_GET['user'])) AND (isset($_GET['pwd'])))  {
        $utilisateur = $_GET['user'];
        $motDePasse = $_GET['pwd']; 
      //  $conn = oci_connect($utilisateur,$motDePasse,"localhost/XE");
}
	
	$utilisateur = strtoupper($utilisateur);
   
	
$conn = oci_connect($utilisateur,$motDePasse,"localhost/XE");

//$utilisateur =$_POST['utilisateur'];
// Requête pour récupérer les tables de l'utilisateur Oracle
$requete = "SELECT table_name FROM all_tables WHERE owner ='$utilisateur' ORDER BY table_name";

// Préparation de la requête
$statement = oci_parse($conn, $requete);


// Exécution de la requête
oci_execute($statement);




//echo '<div class="cadre">';
echo '<div>';
echo '<form name="tables_user" action="" method="POST">';

// Création du combobox
echo' <h3>Table</h3>';    
echo '<select name="table">';

// Affichage des utilisateurs en tant qu'options du combobox
while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
$tableName = $row['TABLE_NAME'];
echo '<option value="' . $tableName . '">' . $tableName . '</option>';

}

echo '</select>';
//echo '<br>';
echo' <h3>Opérations</h3>';
echo "<button  id='mon-bouton' name='nouveau'>Bouton Nouveau</button>";
echo "<button  name='enregistrer'>Bouton Enregistrer</button>";
echo "<button  name='modifier'>Bouton Modifier</button>";
echo "<button  name='supprimer'>Bouton Supprimer</button>";
echo "<button  name='actualiser'>Bouton Actualiser</button>";
echo "<button  name='annuler'>Bouton Annuler</button>";


//echo '<input type="submit" name="table" value="Afficher champs table" />';
echo '</form>';

echo'</div>';
 echo "<h3>Code source du boutton :</h3>";
 echo "<button style='background-color:#00FF00' onclick='copierDansPressePapier()'>Copier le code source</button>";  
//echo "<br>";	

//if (isset($_POST['champs'])) {
    if (isset($_POST['modifier'])) {
      
  $tablename =$_POST['table'];
  echo"<br>";

 
 
   // Requête pour récupérer les tables de l'utilisateur Oracle

  $requete = "Select COLUMN_NAME from all_tab_columns where TABLE_NAME='$tablename'";
  // Préparation de la requête
  $statement = oci_parse($conn, $requete);
  
  
  // Exécution de la requête
  oci_execute($statement);

  $phrase="//----------------- Modifier un Enregistrement ----------------- //\n";
  $phrase.="op:='M';\n";
  // Parcours des tables
  $i = 0;
  while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
      $champ = $row['COLUMN_NAME'];
      $i++;   
      if ($i==1){
          $phrase.="valmod:=ADOQuery1.fieldbyname('".$champ."').Value;\n";
      } 
  
  $phrase.="Edit$i".".text:=ADOQuery1.fieldbyname('".$champ."').Value;\n";
  }
  $phrase.="//--------------------------------------------------------------//\n";
  affichememo($phrase);
 


}

if (isset($_POST['nouveau'])) {

    $tablename =$_POST['table'];
    echo"<br>";
   
     // Requête pour récupérer les tables de l'utilisateur Oracle
  
    $requete = "Select COLUMN_NAME from all_tab_columns where TABLE_NAME='$tablename'";
    // Préparation de la requête
    $statement = oci_parse($conn, $requete);
    
    
    // Exécution de la requête
    oci_execute($statement);
  
    $phrase="//----------------- Vider les champs de saisie pour insérer un nouveau Enregistrement ----------------- //\n";
    $phrase.="op:='N';\n";
    // Parcours des tables
    $i = 0;
    while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
        $champ = $row['COLUMN_NAME'];
        $i++;      
    $phrase.="Edit$i".".text:='';\n";
    
    }
    $phrase.="//-----------------------------------------------------------------------------------------------------//\n";
    affichememo($phrase);
    

  }
  if (isset($_POST['enregistrer'])) {

    $tablename =$_POST['table'];
    echo"<br>";
  
     // Requête pour récupérer les tables de l'utilisateur Oracle
  
    $requete = "Select COLUMN_NAME from all_tab_columns where TABLE_NAME='$tablename'";
    // Préparation de la requête
    $statement = oci_parse($conn, $requete);
    
    
    // Exécution de la requête
    oci_execute($statement);
    
   

// Récupération de tous les enregistrements dans un tableau
$result = array();
oci_fetch_all($statement, $result);

// Obtention du nombre d'enregistrements dans le tableau résultant
$numRows = count($result['COLUMN_NAME']); // Remplacez 'NOM_COLONNE' par le nom d'une colonne existante dans votre table

// Affichage du nombre d'enregistrements
//echo "Nombre d'enregistrements dans la table $tablename : $numRows";
oci_execute($statement);
    
    $phrase="//-----------------Ajouter un nouveau Enregistrement ----------------- //\n";
    $phrase.="if op='N' then\n";
    $phrase.="Begin\n";
    $phrase.="//--------------------------------------------------------------------//\n";
    $phrase.="with ADOQuery1 do\n";
    $phrase.="begin\n";
    $phrase.="close;\n";
    $phrase.="with SQL do\n";
    $phrase.="begin\n";
    $phrase.="clear;\n";
    $phrase.="add('Insert into ".$tablename."');\n";
    $phrase.="add('(";
    // Parcours des tables
    $i = 0;
    
    while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
        $champ = $row['COLUMN_NAME'];
        $i++;  
                  
   
    if ($i==$numRows) {
        $phrase.=$champ;
    }else {
         $phrase.=$champ.",";
        }
    }

    $phrase.=")');\n"; 
   
 
    
    $phrase.="add('VALUES');\n"; 

    oci_execute($statement); 
    $phrase.="add('(";
    $i = 0;
    while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
        $champ = $row['COLUMN_NAME'];
        $i++;  
        
     if ($i==$numRows) {
        $phrase.=$champ;
    } else {   
    $phrase.=":".$champ.",";
    }
    
    }

    $phrase.=")');\n"; 

    



$i = 0;
    while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
        $champ = $row['COLUMN_NAME'];
        $i++;  
        
     $phrase.='parameters.ParamByName('.$champ.').Value:='."Edit$i".".text\n";    
       
    }
$phrase.="Execsql;\n";
$phrase.="end;\n";
$phrase.="end;\n";
$phrase.="Button6.OnClick(self);\n";
$phrase.="ShowMessage(' Enregistrement ajouté avec succès  ');\n";
$phrase.="end;\n";
$phrase.="//--------------------------------------------------------------------------//\n";
$phrase.="//----------------- Modifier un Enregistrement ----------------- //\n";
$phrase.="if op='M' then\n";
$phrase.="Begin\n";
$phrase.="//--------------------------------------------------------------------------//\n";
$phrase.="with ADOQuery1 do\n";
$phrase.="begin\n";
$phrase.="close;\n";
$phrase.="with SQL do\n";
$phrase.="begin\n";
$phrase.="clear\n";


$phrase.="add('UPDATE ".$tablename." SET');\n";



oci_execute($statement); 

$phrase.="add('(";
$i = 0;
while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
    $champ = $row['COLUMN_NAME'];
    $i++;  
 if ($i==$numRows) {
    $phrase.=$champ.'=:'.$champ;
}else{   
     
$phrase.=$champ.'=:'.$champ.",";

}
}

$phrase.=")');\n";



$i = 0;
    while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
        $champ = $row['COLUMN_NAME'];
        $i++;  
        if ($i==1){
        $phrase.="Add('Where '.$champ.'=:valmod');\n"; }  
     $phrase.="parameters.ParamByName('.$champ.').Value:='.'Edit$i'.'.text'\n";   
       
    }
    $phrase.="parameters.ParamByName('valmod').Value:=valmod;\n";

$phrase.="Execsql;\n";
$phrase.="end;\n";
$phrase.="end;\n";
$phrase.="Button6.OnClick(self);\n";
$phrase.="showMessage(' Enregistrement modifié avec succès  ');\n";
$phrase.="end;\n";
$phrase.="//--------------------------------------------------------------------------//\n";


affichememo($phrase);


  }


  if (isset($_POST['supprimer'])) {

    $tablename =$_POST['table'];
    echo"<br>";
  
     // Requête pour récupérer les tables de l'utilisateur Oracle
  
    $requete = "Select COLUMN_NAME from all_tab_columns where TABLE_NAME='$tablename'";
    // Préparation de la requête
    $statement = oci_parse($conn, $requete);
    
    
    // Exécution de la requête
    oci_execute($statement);
  
  
    $phrase="//----------------- Supprimer un Enregistrement ----------------- //\n";

    $phrase.="with ADOQuery1 do\n";
    $phrase.="begin\n";
    $phrase.="close;\n";
    $phrase.="with SQL do\n";
    $phrase.="begin\n";
    $phrase.="clear;\n";
    $phrase.="add('Delete from ".$tablename."');\n";
    $i = 0;
    while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
        $champ = $row['COLUMN_NAME'];
        $i++;      
        if ($i==1) {
    $phrase.="Add('Where".$champ."='+Edit1.text);\n";
    }
}
    

    $phrase.="Execsql;\n";
    $phrase.="end;\n";
    $phrase.="Button6.OnClick(self);\n";
    $phrase.="ShowMessage(' Enregistrement supprimé avec succès  ');\n";
    $phrase.="end;\n";
    $phrase.="//---------------------------------------------------------------//\n";
    
    
    affichememo($phrase);
    

  }

  if (isset($_POST['actualiser'])) {

    $tablename =$_POST['table'];
    echo"<br>";
  
     // Requête pour récupérer les tables de l'utilisateur Oracle
  
    $requete = "Select COLUMN_NAME from all_tab_columns where TABLE_NAME='$tablename'";
    // Préparation de la requête
    $statement = oci_parse($conn, $requete);
    
    
    // Exécution de la requête
    oci_execute($statement);
  
  
    $phrase="//-----------------  Actualisation de la table   -----------------//\n";

    $phrase.="with ADOQuery1 do\n";
    $phrase.="begin\n";
    $phrase.="close;\n";
    $phrase.="with SQL do\n";
    $phrase.="begin\n";
    $phrase.="clear;\n";
    $phrase.="add('Select * from ".$tablename."');\n";
    $phrase.="Execsql;\n";
    $phrase.="end;\n";
    $phrase.="Open;\n";
    $phrase.="end;\n";
    $phrase.="//---------------------------------------------------------------//\n";
    
        affichememo($phrase);
       

  }

  if (isset($_POST['annuler'])) {

    $tablename =$_POST['table'];
    echo"<br>";
    
     // Requête pour récupérer les tables de l'utilisateur Oracle
  
    $requete = "Select COLUMN_NAME from all_tab_columns where TABLE_NAME='$tablename'";
    // Préparation de la requête
    $statement = oci_parse($conn, $requete);
    
    
    // Exécution de la requête
    oci_execute($statement);
  
$phrase="//-----------------  Annulation de l'opération   -----------------//\n";
    // Parcours des tables
    $i = 0;
    while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
        $champ = $row['COLUMN_NAME'];
        $i++;      
    $phrase.="Edit$i".".text:='';\n";
    
    }
    $phrase.="//-----------------------------------------------------------//\n";
        affichememo($phrase);
       
        
  
      
  }

  
  ?>


</body>
</html>			