<?php
// Préparation de la requête
$prep = $pdo->prepare( 'SELECT * FROM message ORDER BY id ASC' );
// Exécution de la requête
$prep->execute();
// Récupération des résultats dans un tableau associatif
$arrAll = $prep->fetchAll();

    if (isset($_SESSION['login']) && isset($_SESSION['password']) && isset($_SESSION['type'])) {

        if ($_SESSION['type'] == 1) {

?>
<div id="pageMessage">

	<div class="contentTitre">
		Message
	</div>
	
	<div class="contentContent">
		<div class="dataTableContainer">
			<table id="messageTable" class="stripe row-border dataTable">
			
				<thead>
					<tr>
						<th>Type</th>
						<th>Sonde</th>
						<th>Groupe - Type</th>
						<th>Message</th>
						<th>Date</th>
					</tr>
				</thead>
							
				<tbody>
                <?php
                 for ($i=0;$i<count($arrAll);$i++) {
                     $arr = $arrAll[$i];
                     $sonde_id = $arr[1];
                     $prep = $pdo->prepare( 'SELECT * FROM sonde WHERE id ="' . $sonde_id . '"' );
                     $prep->execute();
                     $arrRes = $prep->fetchAll();
                     //var_dump($arrRes);
                     $arr2 = $arrRes[0];
                     $label = $arr2[2];
                     $groupe_id = $arr2[4];
                     $message = $arr[2];
                     $date = $arr[4];
                     $prep = $pdo->prepare( 'SELECT * FROM groupe WHERE id ="' . $groupe_id . '"' );
                     $prep->execute();
                     $arrRes2 = $prep->fetchAll();
                     //var_dump($arrRes);
                     $arr3 = $arrRes2[0];
                     $groupe = $arr3[1];
                     $icon = $arr3[3];
                     echo "<tr >
						<td class='textCenter fontSize20 fa $icon' ></td >
						<td > $label</td >
						<td > $groupe</td >
						<td > $message</td >
						<td > $date</td >
					</tr >";
					}

                ?>
				</tbody>
							
			</table>
		</div>
	</div>
	
</div>

<script>
	$(document).ready( function(){
        $('#messageTable').DataTable( {
            paging: false,
            scrollY: 400
        } );
	});
</script>

<?php
}}
    ?>