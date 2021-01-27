<?php

// On inclu le fichier avec tous les paramétres
require '../parametres.php';

// Extraction de la sonde
$query = 'SELECT label FROM type_sonde ORDER BY id ASC';
$stmt = $pdo->query( $query );
$result = $stmt->fetchAll( PDO::FETCH_COLUMN );
//var_dump($result);
if($result) {
    /*foreach ($result as &$value) {
        $value = str_replace("label:", "", $value);
    }*/
    // Tout est Ok
    echo json_encode(array('result' => 1, 'list' => $result));
}else{
    echo json_encode( array( 'result' => -10 ) );
}
// Fin d'éxécution
exit(0);

?>