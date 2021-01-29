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
                    echo "
<div class='header_img' data-login='true'>
<div >Deconnexion</div>
                <img src='img/unlock.png'/>
                </div>";
                }else {

                    echo "
<div class='header_img' data-login='false'>
<div >Connexion</div>
                <img src='img/lock.png'/>
                </div>";
                }
                ?>
		</div>
