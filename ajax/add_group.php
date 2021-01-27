<?php

// On inclu le fichier avec tous les paramétres
require '../parametres.php';

// Vérification du captcha
if ( !isset( $_REQUEST['value']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

if ( !isset( $_REQUEST['icon']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

// Extraction de la sonde
$query = 'SELECT label FROM groupe ORDER BY ordre ASC';
$stmt = $pdo->query( $query );
$result = $stmt->fetchAll(PDO::FETCH_COLUMN);
//var_dump($result);
    if(!in_array($_REQUEST['value'],$result)){

        $query = 'SELECT id FROM groupe ORDER BY ordre ASC';
        $stmt = $pdo->query( $query );
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $ordre = count($result)+1;

        $query = '
		INSERT INTO groupe (label, ordre, icone)
		VALUES (:label, :ordre, :icone)';
        $prep = $pdo->prepare($query);
        $prep->bindValue('label', $_REQUEST['value']);
        $prep->bindValue('ordre', $ordre);
        $prep->bindValue('icone', $_REQUEST['icon']);
        $prep->execute();
        echo json_encode(array('result' => 1));

    /*foreach ($result as &$value) {
        $value = str_replace("label:", "", $value);
    }*/
    // Tout est Ok

}else{
    echo json_encode( array( 'result' => -10 ) );
}
// Fin d'éxécution
exit(0);

?>