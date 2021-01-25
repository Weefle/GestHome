<?php

// On inclu le fichier avec tous les paramétres
require '../parametres.php';

// Vérification du captcha
if ( !isset( $_REQUEST['login']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

if ( !isset( $_REQUEST['password'] ) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

// Extraction de la sonde
$query = 'SELECT * FROM utilisateur WHERE login = "' . $_REQUEST['login'] . '" AND password = "' . $_REQUEST['password'] . '"';
$stmt = $pdo->query( $query );
$result = $stmt->fetch( PDO::FETCH_ASSOC );
//var_dump($result);
if($result) {
    // Tout est Ok
    echo json_encode(array('result' => 1));
}else{
    echo json_encode( array( 'result' => -10 ) );
}
// Fin d'éxécution
exit(0);

?>