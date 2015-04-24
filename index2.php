<?php
include ("teleinfo2.php");
?>
<html>
<head>
   <title>TELEINFO</title>
</head>
<body>
<p>
<?php
foreach (teleinfor() as $etiquette=>$valeur) {
 echo("$etiquette = $valeur\n");
}
?>
</p>
</body>
</html>
