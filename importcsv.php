<?php
$da=date('Y');
$dm=date('m')-1;
$dj=date('d');
$dm='0'.$dm;
$path = "/media/dataserveur/Teleinfo/$da.$dm";
$Filename = "/media/dataserveur/Teleinfo/$da.$dm/file.csv";
echo $Filename;
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

$fp = fopen("$Filename", "a+");
global $csv2;

$db = new SQLite3("/media/dataserveur/Teleinfo/$da.$dm/teleinfo.sqlite");
$db->exec('CREATE TABLE IF NOT EXISTS conso (TIME TEXT, PTCOUR TEXT, EA INTEGER, EApP INTEGER, EApHCE INTEGER, EApHCH INTEGER ,EApHPE INTEGER, EApHPH INTEGER);'); // cree la table puissance si elle n'existe pas
$db->exec('CREATE TABLE IF NOT EXISTS conso1 ( TIME TEXT, EApP INTEGER, EApHCE INTEGER, EApHCH INTEGER ,EApHPE INTEGER, EApHPH INTEGER, EAp1P INTEGER, EAp1HCE INTEGER, EAp1HCH INTEGER, EAp1HPE INTEGER ,EAp1HPH INTEGER);'); // cree la table conso1 si elle n'existe pas


/*Ouverture du fichier en lecture seule*/
$handle = fopen("$Filename", "r");
/*Si on a réussi à ouvrir le fichier*/
if ($handle)
{
	/*Tant que l'on est pas à la fin du fichier*/
	while (!feof($handle))
	{
		/*On lit la ligne courante*/
		$buffer = fgets($handle);
		/*On l'affiche*/
//		echo $buffer;
$csv2=chop($buffer);
if ($csv2!="")
{
	
/* elimination des unités après lecture pour n'avoir que des entiers */
$index=0;
$letters = array("W", "h", "k", "a", "v", "r", "h", "A", "%", "V" );	
	
$table=array();
$table1=array();

/* recupération des données utiles à partir du fichier csv en éliminant les lignes mal formatées */
$table=explode(",",$csv2);
if (($table[4] == 'PTCOUR=HPH') OR ($table[4] == 'PTCOUR=P') OR ($table[4] == 'PTCOUR=HCH'))
{
$time=$table[0];
	$table1=explode("=",$time);
	$time = $table1[1];
//	echo $time;
	print "<br>";
		
/* elimination des unités après lecture pour n'avoir que des entiers  à partir de $letters
et création des tables  en fonction de la trame (dans notre cas la trame extraite n'est pas toujours la même )*/	
$EA=$table[2];
	$table1=explode("=",$EA);
	 $EA     	= str_replace($letters, "", $table1[1]);
	 
$PTCOUR= $table[4];
	$table1=explode("=",$PTCOUR);
	$PTCOUR=$table1[1];	 
	 	 	 
//	echo $EA;
$EApP=$table[12];
	$table1=explode("=",$EApP);
	$EApP     	= str_replace($letters, "", $table1[1]);
//	echo $EApP;
$EApHCE=$table[13];
	$table1=explode("=",$EApHCE);
	$EApHCE     	= str_replace($letters, "", $table1[1]);
//	echo $EApHCE;
$EApHCH=$table[14];
	$table1=explode("=",$EApHCH);
	$EApHCH     	= str_replace($letters, "", $table1[1]);
//	echo $EApHCH;
$EApHPE=$table[17];
	$table1=explode("=",$EApHPE);
	$EApHPE    	= str_replace($letters, "", $table1[1]);
//	echo $EApHPE;	
$EApHPH=$table[18];
	$table1=explode("=",$EApHPH);
	$EApHPH     	= str_replace($letters, "", $table1[1]);
//	echo $EApHPH;

/* si $EAp1-- existe on doit extraires les données dans une autre table */

$EAp1P=$table[21];
	$table1=explode("=",$EAp1P);
	$var= $table1[0];		
	
	if ($var=="EAp1P")
	{
//	echo "var=";
//	echo $var;
//	print "<br>";
	$EAp1P=$table[21];
	$table1=explode("=",$EAp1P);
	$EAp1P     	= str_replace($letters, "", $table1[1]);
//	echo $EAp1P ;
	$EAp1HCE=$table[22];
	$table1=explode("=",$EAp1HCE);
	$EAp1HCE     	= str_replace($letters, "", $table1[1]);
//	echo $EAp1HCE ;
	$EAp1HCH=$table[23];
	$table1=explode("=",$EAp1HCH);
	$EAp1HCH     	= str_replace($letters, "", $table1[1]);
//	echo $EAp1HCH ;
	$EAp1HPE=$table[26];
	$table1=explode("=",$EAp1HPE);
	$EAp1HPE     	= str_replace($letters, "", $table1[1]);
//	echo $EAp1HPE;
	$EAp1HPH=$table[27];
	$table1=explode("=",$EAp1HPH);
	$EAp1HPH    	= str_replace($letters, "", $table1[1]);
//	echo $EAp1HPH;

	if($db->busyTimeout(5000)){ // stock les 
	$db->exec("INSERT INTO conso1 (TIME, EApP, EApHCE, EApHCH, EApHPE, EApHPH, EAp1P, EAp1HCE, EAp1HCH, EAp1HPE, EAp1HPH) VALUES ('".$time."',".$EApP.",".$EApHCE.",".$EApHCH.",".$EApHPE.",".$EApHPH.",".$EAp1P.",".$EAp1HCE.",".$EAp1HCH.",".$EAp1HPE.",".$EAp1HPH.");");
	}

	}
	else{
	echo "fin1";
	}

if($db->busyTimeout(5000)){ // stock les 
$db->exec("INSERT INTO conso (TIME, PTCOUR, EA, EApP, EApHCE, EApHCH, EApHPE, EApHPH) VALUES ('".$time."','".$PTCOUR."',".$EA.",".$EApP.",".$EApHCE.",".$EApHCH.",".$EApHPE.",".$EApHPH.");");

}

else{
echo "fin2";

}
}
}
}
}
//mysql_close();
	
	/*On ferme le fichier*/
fclose($handle);

?>
