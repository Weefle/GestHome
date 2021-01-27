<?php

// On inclu le fichier avec tous les paramétres
require '../parametres.php';

// Vérification du captcha

if ( !isset( $_REQUEST['id']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

// Extraction de la sonde
$query = 'DELETE FROM sonde WHERE id = "' . $_REQUEST['id'] . '"';
$stmt = $pdo->query( $query );
//var_dump($result['label']);
// Tout est Ok
echo json_encode(array('result' => 1));
// Fin d'éxécution
exit(0);

?>