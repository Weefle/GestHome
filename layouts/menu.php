<?php

// Préparation de la requête
$prep = $pdo->prepare( 'SELECT * FROM groupe ORDER BY ordre ASC' );
// Exécution de la requête
$prep->execute();
// Récupération des résultats dans un tableau associatif
$arrAll = $prep->fetchAll();
// Extraction de l'id
$groupeId=0;
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
        //var_dump($arrAll);
            for ($i=0;$i<count($arrAll);$i++) {
                $arr = $arrAll[$i];
                $id = $arr[0];
                $name = $arr[1];
                $icon = $arr[3];
                $currentPage = $pageAffiche . '&id=' . $groupeId;
                $generatedPage = 'groupe&id=' . $id;
                $classType = $currentPage == $generatedPage ? 'selected' : '';
           echo "<li class=$classType>
                <a href='index.php?page=$generatedPage'>
                    <span class='fa $icon'></span>$name
                </a>
           </li>";
            }
            //echo $pageAffiche . '&id=' . $groupeId;
        ?>

		<li class="<?php echo ( $pageAffiche == 'message' ? 'selected' : '' ); ?>">
			<a href="index.php?page=message">
				<span class="fa fa-envelope"></span>Message
			</a>
		</li>

        <li class="<?php echo ( $pageAffiche == 'admin' ? 'selected' : '' ); ?>">
            <a href="index.php?page=admin">
                <span class="fa fa-cogs"></span>Administration
            </a>
        </li>
		
	</ul>
	<br class="clear" />
</div>
		