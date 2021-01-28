<?php

// On inclu le fichier avec tous les paramétres
require '../parametres.php';

// Vérification du captcha
if ( !isset( $_REQUEST['login']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

if ( !isset( $_REQUEST['password']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}
if ( !isset( $_REQUEST['nom']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}
if ( !isset( $_REQUEST['prenom']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

if ( !isset( $_REQUEST['type']) ){
    echo json_encode( array( 'result' => -10 ) );
    exit(0);
}

// Extraction de la sonde
$query = 'SELECT login FROM utilisateur ORDER BY id ASC';
$stmt = $pdo->query( $query );
$result = $stmt->fetchAll(PDO::FETCH_COLUMN);
//var_dump($result);
if(!in_array($_REQUEST['login'],$result)){

    $type = $_REQUEST['type']+1;

    $query = '
		INSERT INTO utilisateur (nom, prenom, type_utilisateur_id, login, password, date_derniere_connexion)
		VALUES (:nom, :prenom, :type_utilisateur_id, :login, :password, :date_derniere_connexion)';
    $prep = $pdo->prepare($query);
    $prep->bindValue('nom', $_REQUEST['nom']);
    $prep->bindValue('prenom', $_REQUEST['prenom']);
    $prep->bindValue('type_utilisateur_id', $type);
    $prep->bindValue('login', $_REQUEST['login']);
    $prep->bindValue('password', $_REQUEST['password']);
    $prep->bindValue('date_derniere_connexion', date("Y-m-d H:i:s"));
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