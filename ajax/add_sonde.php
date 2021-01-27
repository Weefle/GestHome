<?php

// On inclu le fichier avec tous les paramétres
require '../parametres.php';

// Vérification du captcha
if ( !isset( $_REQUEST['value']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

if ( !isset( $_REQUEST['group']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

if ( !isset( $_REQUEST['type']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

// Extraction de la sonde
$query = 'SELECT label FROM sonde ORDER BY id ASC';
$stmt = $pdo->query( $query );
$result = $stmt->fetchAll(PDO::FETCH_COLUMN);
//var_dump($result);
if(!in_array($_REQUEST['value'],$result)){

    $query = 'SELECT code FROM sonde ORDER BY id ASC';
    $stmt = $pdo->query( $query );
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

    do{

        $code = random_int(10000000, 99999999);

    } while(in_array($code,$result));

    $group_id = $_REQUEST['group']+1;

    $query = 'SELECT id FROM groupe WHERE ordre = "' . $group_id . '"';
    $stmt = $pdo->query( $query );
    $group = $stmt->fetch(PDO::FETCH_ASSOC);



    $query = '
		INSERT INTO sonde (code, label, type_sonde_id, groupe_id, valeur)
		VALUES (:code, :label, :type_sonde_id, :groupe_id, :valeur)';
    $prep = $pdo->prepare($query);
    $prep->bindValue('label', $_REQUEST['value']);
    $prep->bindValue('code', $code);
    $prep->bindValue('type_sonde_id', $_REQUEST['type']+1);
    $prep->bindValue('groupe_id', $group['id']);
    $prep->bindValue('valeur', 0);
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