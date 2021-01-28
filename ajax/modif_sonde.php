<?php

// On inclu le fichier avec tous les paramétres
require '../parametres.php';

// Vérification du captcha
if ( !isset( $_REQUEST['value']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

if ( !isset( $_REQUEST['id']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

// Extraction de la sonde
$query = 'SELECT label FROM sonde WHERE id = "' . $_REQUEST['id'] . '"';
$stmt = $pdo->query( $query );
$result = $stmt->fetch(PDO::FETCH_ASSOC);
//var_dump($result['label']);
if(in_array($_REQUEST['value'],$result)) {
    $query = '
		UPDATE sonde
		SET label = :valeur
		WHERE id = :id
	';
    $prep = $pdo->prepare($query);

    $prep->bindValue('valeur', $_REQUEST['value']);
    $prep->bindValue('id', $_REQUEST['id']);
    $prep->execute();
    // Tout est Ok
    echo json_encode(array('result' => 1));
}else{
    echo json_encode( array( 'result' => -10 ) );
}
// Fin d'éxécution
exit(0);

?>