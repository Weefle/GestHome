<?php

// Extraction de l'id
$groupeId = 0;
if ( isset( $_GET['id'] ) && $_GET['id'] > 0 ){
    $groupeId = $_GET['id'];
}

?>
<div id="menu">
	<ul>
	
		<li class="<?php echo ( $pageAffiche == 'accueil' ? 'selected' : '' ); ?>">
			<a href="index.php?page=accueil">
				<span class="fa fa-home"></span>Accueil
			</a>
		</li>

        <?php
        // Préparation de la requête
        $prep = $pdo->prepare( 'SELECT label FROM groupe ORDER BY ordre ASC' );
        // Exécution de la requête
        $prep->execute();
        // Récupération des résultats dans un tableau associatif
        $arrAll = $prep->fetchAll();
        //var_dump($arrAll);
            for ($i=0;$i<count($arrAll);$i++) {
                $arr = $arrAll[$i];
                $name = $arr[0];
                $currentPage = $pageAffiche . '&id=' . $groupeId;
                $generatedPage = 'groupe&id=' . $i;
                $classType = $currentPage == $generatedPage ? 'selected' : '';
                echo "<li class=$classType><a href='index.php?page=$generatedPage'><span class='fa fa-bed'></span>$name</a></li>";
            }

            //echo $pageAffiche . '&id=' . $groupeId;
        ?>


        <!--<li>
			<a href="index.php?page=groupe&id=1">
				<span class="fa fa-bed"></span>Chambre 1
			</a>
		</li>
		
		<li>
			<a href="index.php?page=groupe&id=2">
				<span class="fa fa-bed"></span>Chambre 2
			</a>
		</li>
		
		<li>
			<a href="index.php?page=groupe&id=3">
				<span class="fa fa-bed"></span>Chambre 3
			</a>
		</li>
		
		<li>
			<a href="index.php?page=groupe&id=4">
				<span class="fa fa-cutlery"></span>Cuisine
			</a>
		</li>
		
		<li>...</li>-->

		<li class="<?php echo ( $pageAffiche == 'message' ? 'selected' : '' ); ?>">
			<a href="index.php?page=message">
				<span class="fa fa-envelope"></span>Message
			</a>
		</li>
		
	</ul>
	<br class="clear" />
</div>
		