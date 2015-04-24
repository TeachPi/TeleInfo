 <?php
    /*
    * PHP-Teleinfo2
    * v.0.2
    *
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
 
 /* Verifie le checksum d'un message */
    function checksum ($etiquette, $valeur) {
       $sum = 32; // Somme des codes ASCII du message+un espace
       for ($i=0;$i<strlen($etiquette);$i++) {
          $sum = $sum + ord($etiquette[$i]);
       }
       for ($i=0;$i<strlen($valeur);$i++) {
          $sum = $sum + ord($valeur[$i]);
       }
       $sum = ($sum & 63) + 32;
       return (chr($sum));
    }
	
    /* Recuperer une trame valide */
    function teleinfor ($port = "/dev/ttyAMA0") {
       // Resultat : liste etiquette=>valeur
       $trame_array = array();
       
       // Ouvrir le port en lecture
       $handle = fopen ($port, "r");
       $char = "";
       $contents = "";
       while (fread($handle, 1) != chr(2));
       do {
          $char = fread($handle, 1);
          if ($char != chr(2)) $contents .= $char;
       } while ($char != chr(2));

       // Fermer le port
       fclose ($handle);

       // Supprimer les caracteres debut/fin de trame
       $trame = substr($contents,1,-1);

       // Separer les messages
       $trame = explode(chr(10).chr(10), $trame);

       // Verifier les checksum et supprimer les trames incorrectes
       foreach ($trame as $key=>$message) {
          
          // Separer l'etiquette, la valeur et le checksum
          $message = explode (chr(32), $message, 3);
          list ($etiquette, $valeur, $checksum) = $message;
       
         // Supprimer le message si incomplet ou incorrect (checksum invalide) 
      if (count($message)<3 || 
         checksum($etiquette, $valeur) != $checksum 
		 
	//	|| 
    //     messize($etiquette) != strlen($valeur)  
         ) { 
         unset($trame[$key]); 
      
          } else {
             // Enregistrer le message dans la liste trame_array
             $trame_array[$etiquette] = $valeur;
			 
          }
       }
       
       return $trame_array;
    }
    ?>
