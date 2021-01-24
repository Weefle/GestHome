<?php 

// Extraction de l'id
	$groupeId = 1;
	if ( isset( $_GET['id'] ) && $_GET['id'] > 0 ){
		$groupeId = $_GET['id'];
	}

// Préparation de la requête
$prep = $pdo->prepare( 'SELECT * FROM sonde WHERE groupe_id ="' . $groupeId . '"ORDER BY id ASC' );
// Exécution de la requête
$prep->execute();
// Récupération des résultats dans un tableau associatif
$arrAll = $prep->fetchAll();

// Préparation de la requête
$prep = $pdo->prepare( 'SELECT label FROM groupe WHERE id ="' . $groupeId . '"' );
// Exécution de la requête
$prep->execute();
// Récupération des résultats dans un tableau associatif
$arrAll2 = $prep->fetchAll();
$grp_name = $arrAll2[0];
//var_dump($grp_name);
?>

<div id="pageGroupe">

	<div class="contentTitre">
		<?php echo $grp_name[0]; ?>
	</div>

    <?php
    //var_dump($arrAll);
    for ($i=0;$i<count($arrAll);$i++) {
        $arr = $arrAll[$i];
        $name = $arr['label'];
        $id = $arr[0];
        $type_sonde_id = $arr['type_sonde_id'];
        //var_dump($type_sonde_id);
        $value = $arr[5];
        //var_dump($type);
        //$icon = $arr[3];
        //$currentPage = $pageAffiche . '&id=' . $groupeId;
        $generatedPage = 'groupe&id=' . $i;
        //$classType = $currentPage == $generatedPage ? 'selected' : '';
        switch ($type_sonde_id){

            case TYPE_SONDE_TEMPERATURE: $image = 'img/thermometre.png';
            $affichage = "<div class='blocBtn'>
					<input name='btnLampe' value='Allumer' type='button'>
				</div>";
            break;
            case TYPE_SONDE_PORTE: $image = 'img/porte_ouverte.png';
                $affichage = "<div class='blocBtn'>
                    <input name='btnPorte' value='Fermer' type='button'>
					<!--<input name='btnPorte' value='Changer' type='image' src='img/unlock.png'>-->
				</div>";
            break;
            case TYPE_SONDE_FENETRE: $image = 'img/volet_ouvert.png';
            break;
            case TYPE_SONDE_ECLAIRAGE: $image = 'img/lampe_eteinte.png';
            break;
            case TYPE_SONDE_LUMINOSITE: $image = 'img/luminosite.png';
            break;
            case TYPE_SONDE_CHAUFFAGE: $image = 'img/thermometre.png';
                break;

        }
        echo "<div class='contentContent'>
		<div class='sonde typeSonde' data-sonde-id=$id data-type-sonde-id=$type_sonde_id>
			<div class='titre'>$name ($value)</div>
			<div class='content'>
				<div class='imageSeule'>
					<img src=$image>
				</div>
				$affichage
			</div>
		</div>
	</div>";
    }

    //echo $pageAffiche . '&id=' . $groupeId;
    ?>

</div>

<script>
	$(document).ready( function(){
		
		$('#pageGroupe').on( 'click', 'input', function( e ){
			// Stop la propagation
				e.preventDefault();

			// Récupération du parent
				var parent = $(this).parents('.sonde');

			// Récupération de l'id de la sonde
				var sondeId = parent.data('sonde-id');
				var value = $(this).val();
                var typeSondeId = parent.data('type-sonde-id');

            if(typeSondeId!=null) {
                switch(typeSondeId){
                    case 1:
                        break;
                    case 3:
                        $.ajax({
                        url: 'ajax/update_porte.php',
                        type: 'POST',
                        data: {
                            sonde_id: sondeId,
                            //type_sonde_id: typeSondeId
                        },
                        dataType: 'json',
                        success: function (response) {
                            // Gestion de la réponse
                            resultId = parseInt(response.result);
                            if (resultId > 0) {
                                if (response.newValeur == 1) {
                                    parent.find('.imageSeule img').attr('src', 'img/porte_ferme.png');
                                    parent.find('input[name="btnPorte"]').val('Ouvrir');
                                } else {
                                    parent.find('.imageSeule img').attr('src', 'img/porte_ouverte.png');
                                    parent.find('input[name="btnPorte"]').val('Fermer');
                                }
                            } else {
                                alert('Erreur lors de la mise à jour de la porte');
                            }
                        }
                    });
                        break;
                    case 4:
                        break;
                    case 5: // Envoi des données au serveur
                        $.ajax({
                            url: 'ajax/update_eclairage.php',
                            type: 'POST',
                            data: {
                                sonde_id: sondeId,
                                //type_sonde_id: typeSondeId
                            },
                            dataType: 'json',
                            success: function (response) {
                                // Gestion de la réponse
                                resultId = parseInt(response.result);
                                if (resultId > 0) {
                                    if (response.newValeur == 1) {
                                        parent.find('.imageSeule img').attr('src', 'img/lampe_allumee.png');
                                        parent.find('input[name="btnLampe"]').val('Eteindre');
                                    } else {
                                        parent.find('.imageSeule img').attr('src', 'img/lampe_eteinte.png');
                                        parent.find('input[name="btnLampe"]').val('Allumer');
                                    }
                                } else {
                                    alert('Erreur lors de la mise à jour de la lampe');
                                }
                            }
                        });
                        break;
                    case 6:
                        break;
                    case 7:
                        break;
                }

            }
		});

	});

</script>
