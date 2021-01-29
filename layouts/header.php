<?php setcookie( 'last_page', $_SERVER['REQUEST_URI'], ( time() + 3600), '/' ); ?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="UTF-8" />
		<title>GestHome</title>
		
		<link rel="stylesheet" type="text/css" media="all" href="css/font-awesome.css" />
		<link rel="stylesheet" type="text/css" media="all" href="css/datatable.css" />
		<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon'/ >

        <script type="text/javascript" src="js/sweetalert.min.js"></script>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.form.min.js"></script>
		<script type="text/javascript" src="js/jquery.datatable.min.js"></script>
	</head>

	<body>
	
		<div id="header">
			Gestion Maison
                <?php

                if (isset($_SESSION['login']) && isset($_SESSION['password']) && isset($_SESSION['type'])) {
                    $query = 'SELECT * FROM utilisateur WHERE login = "' . $_SESSION['login'] . '" AND password = "' . $_SESSION['password'] . '"';
                    $stmt = $pdo->query( $query );
                    $result = $stmt->fetchAll();
                    $user = $result[0];
                    //var_dump($user['nom']);
                    echo " - ${user['nom']} ${user['prenom']}
<div class='header_img' data-login='true'>
                <img src='img/unlock.png'/>
                </div>";
                }else {

                    echo "
<div class='header_img' data-login='false'>
                <img src='img/lock.png'/>
                </div>";
                }
                ?>
		</div>
