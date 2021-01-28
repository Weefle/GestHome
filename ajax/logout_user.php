<?php

// On inclu le fichier avec tous les paramétres
require '../parametres.php';

// On détruit les variables de notre session
session_unset ();

// On détruit notre session
session_destroy ();

echo json_encode(array('result' => 1));
// Fin d'éxécution
exit(0);

?>
