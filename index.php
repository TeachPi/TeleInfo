<?php
	/*
    *  This program is free software: you can redistribute it and/or modify
    *  it under the terms of the GNU General Public License as published by
    *  the Free Software Foundation, either version 3 of the License, or
    *  (at your option) any later version.
    *
    *  This program is distributed in the hope that it will be useful,
    *  but WITHOUT ANY WARRANTY; without even the implied warranty of
    *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *  GNU General Public License for more details.
    *
    *  You should have received a copy of the GNU General Public License
    *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
    *
    *  (c) 2008 Radim BADSI <info AT devbay DOT fr>
    */
	
    include ("teleinfo.php");
    ?>
    <html>
    <head>
       <title>TELEINFO</title>
    </head>
    <body>
    <p>
    <?php
	$sqlite = 'teleinfo.sqlite';
	$csv = "";
	$csv3 = "";
	$datas = array();
	$da=date('Y');
	$dm=date('m');
	$dj=date('d');
	$path = "/media/dataserveur/Teleinfo/$da.$dm";
	$Filename = "/media/dataserveur/Teleinfo/$da.$dm/file.csv";
	if (!file_exists($path)) {
    mkdir($path, 0777, true);
}


	$compte=(0);
	
	while ($compte >= 0) {
		$csv = "";	
		$trame = teleinfo(); 
		foreach ($trame as $etiquette=>$valeur) {
			$len = 0;  
			$data = $valeur;
			$datas= time();
			$datas=date("Y-m-d H:i:s",$datas);
			$csv .= $etiquette . chr(61) . $trame[$etiquette] . ","; 
		}
		$compte-=1;
	
		$len=strlen ($csv);
		if ($len >= 487){
			$csv3 .= chr(61) .$datas. ",". $csv ; 
			$fp = fopen("$Filename", "a+");
			fputcsv($fp, array($csv3));
			fclose($fp);
		}
	}
	
    ?>
    </p>
    </body>
    </html>

