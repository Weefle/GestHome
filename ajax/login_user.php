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
$query = 'SELECT id FROM utilisateur WHERE login = "' . $_REQUEST['login'] . '" AND password = "' . hash('sha512', $_REQUEST['password']) . '"';
$stmt = $pdo->query( $query );
$result = $stmt->fetch( PDO::FETCH_ASSOC );
//var_dump($result);
if($result) {
    $_SESSION['login'] = $_REQUEST['login'];
    $_SESSION['password'] = hash('sha512', $_REQUEST['password']);
    $query = 'SELECT type_utilisateur_id FROM utilisateur WHERE login = "' . $_REQUEST['login'] . '" AND password = "' . hash('sha512', $_REQUEST['password']) . '"';
    $stmt = $pdo->query( $query );
    $user_type = $stmt->fetch( PDO::FETCH_ASSOC );
    $_SESSION['type'] = $user_type['type_utilisateur_id'];
    $query = '
		UPDATE utilisateur
		SET date_derniere_connexion = :date_derniere_connexion
		WHERE id = :id
	';
    $prep = $pdo->prepare($query);

    $prep->bindValue('date_derniere_connexion', date("Y-m-d H:i:s"));
    $prep->bindValue('id', $result['id']);
    $prep->execute();
    // Tout est Ok
    echo json_encode(array('result' => 1));
}else{
    echo json_encode( array( 'result' => -10 ) );
}
// Fin d'éxécution
exit(0);

?>