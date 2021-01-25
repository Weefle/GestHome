<?php

// On inclu le fichier avec tous les paramétres
require '../parametres.php';

// Vérification du captcha
if ( !isset( $_REQUEST['sonde_id']) || !( $_REQUEST['sonde_id'] > 0 ) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

if ( !isset( $_REQUEST['value'] ) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

// Extraction de la sonde
$querySode = 'SELECT * FROM sonde WHERE id = "' . $_REQUEST['sonde_id'] . '" ';
$stmtSonde = $pdo->query( $querySode );
$resultSonde = $stmtSonde->fetch( PDO::FETCH_ASSOC );
$ancienValeur = $resultSonde['valeur'];

$value = $_REQUEST['value'];

// Valeur
    if ($value == "Augmenter_Chauffage") {
        $newValeur = $ancienValeur + 0.5;
    } else if ($value == "Diminuer_Chauffage") {
        $newValeur = $ancienValeur - 0.5;
    }
if($newValeur <= 50 && $newValeur >= -10) {
// Insertion d'une publication en base
    $query = '
		UPDATE sonde
		SET valeur = :valeur
		WHERE id = :id
	';
    $prep = $pdo->prepare($query);

    $prep->bindValue('valeur', $newValeur);
    $prep->bindValue('id', $resultSonde['id']);
    $prep->execute();

// Insertion d'une publication en basedu message
    $query = '
		INSERT INTO message (sonde_id, message, type_message_id, date_creation)
		VALUES (:sonde_id, :message, :type_message_id, :date_creation)';
    $prep = $pdo->prepare($query);

    $prep->bindValue('sonde_id', $resultSonde['id']);
    $prep->bindValue('message', 'L\'éclairage est passé de [' . $ancienValeur . '] à [' . $newValeur . ']');
    $prep->bindValue('type_message_id', TYPE_MESSAGE_MODIFICATION);
    $prep->bindValue('date_creation', date('Y-m-d H:i:s'));
    $prep->execute();

// Tout est Ok
    echo json_encode(array('result' => 1, 'newValeur' => $newValeur, 'ancienValeur' => (int)$ancienValeur));
}
// Fin d'éxécution
exit(0);

?>