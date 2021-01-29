<?php
// Préparation de la requête
$prep = $pdo->prepare( 'SELECT * FROM groupe ORDER BY ordre ASC' );
// Exécution de la requête
$prep->execute();
// Récupération des résultats dans un tableau associatif
$arrAll = $prep->fetchAll();

require('layouts/pages/admin.php');
if (isset($_SESSION['login']) && isset($_SESSION['password']) && isset($_SESSION['type'])) {

if ($_SESSION['type'] == 1) {

?>
<div id="pageMessage">

    <div class="contentTitre">
        Administration des groupes
    </div>

    <div class="contentContent">
        <input name="Add_Group" value="Ajouter un groupe" type="button">
        <div class="dataTableContainer">
            <table id="messageTable" class="stripe row-border dataTable">

                <thead>
                <tr>
                    <th>Groupe</th>
                    <th>Icone</th>
                    <th>Nombre de sondes</th>
                    <th>Modifier groupe</th>
                    <th>Supprimer groupe</th>
                </tr>
                </thead>

                <tbody>
                <?php
                for ($i=0;$i<count($arrAll);$i++) {
                    $arr = $arrAll[$i];
                    $groupe_id = $arr['id'];
                    $groupe_name = $arr['label'];
                    $groupe_icon = $arr['icone'];
                    $prep = $pdo->prepare( 'SELECT COUNT(*) FROM sonde WHERE groupe_id ="' . $groupe_id . '"' );
                    $prep->execute();
                    $arrRes = $prep->fetchAll();
                    //var_dump($arrRes);
                    $arr2 = $arrRes[0];
                    $sonde_count = $arr2[0];
                    echo "<tr class='groupe' data-groupe-id=$groupe_id data-groupe-name='$groupe_name' >
                        <td >$groupe_name</td >
						<td class='textCenter fontSize20 fa $groupe_icon' ></td >
						<td >$sonde_count</td >
						<td ><input name='Modif_Group' value='Modifier' type='button'></td>
						<td ><input name='Suppr_Group' value='Supprimer' type='button'></td>
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

        $('#pageMessage').on( 'click', 'input[name=Suppr_Group]', function(e) {
            e.preventDefault();
            var parent = $(this).parents('.groupe');

            // Récupération de l'id de la sonde
            var groupeId = parent.data('groupe-id');
            var groupeName = parent.data('groupe-name');

            Swal.fire({
                title: 'Etes vous sûr de supprimer ' + groupeName + '?',
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax/suppr_group.php',
                        type: 'POST',
                        data: {
                            id: groupeId
                        },
                        dataType: 'json',
                        success: function (response) {
                            // Gestion de la réponse
                            resultId = parseInt(response.result);
                            if (resultId > 0) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Parfait',
                                    text: 'Groupe supprimé!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location = "index.php?page=admin_groupes";
                                    }
                                });

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Echec',
                                    text: 'Erreur de requête!',
                                });
                            }
                        }
                    });
                }
            });
        });

        $('#pageMessage').on( 'click', 'input[name=Modif_Group]', function(e){
            e.preventDefault();
            var parent = $(this).parents('.groupe');

            // Récupération de l'id de la sonde
            var groupeId = parent.data('groupe-id');
            var groupeName = parent.data('groupe-name');
            //alert(groupeName);

            Swal.fire({
                title: 'Modifier groupe',
                html: `<input type="text" id="value" class="swal2-input" placeholder="${groupeName}">`,
                confirmButtonText: 'Valider',
                focusConfirm: false,
                preConfirm: () => {
                    const value = Swal.getPopup().querySelector('#value').value
                    if (!value) {
                        Swal.showValidationMessage(`Please enter a value!`)
                    }
                    return { value: value}
                }
            }).then((result) => {
                if(result.value) {
                    const value = result.value.value;
                    $.ajax({
                        url: 'ajax/modif_group.php',
                        type: 'POST',
                        data: {
                            value: value,
                            id: groupeId
                        },
                        dataType: 'json',
                        success: function (response) {
                            // Gestion de la réponse
                            resultId = parseInt(response.result);
                            if (resultId > 0) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Parfait',
                                    text: 'Nom du groupe modifié!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location = "index.php?page=admin_groupes";
                                    }
                                });

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Echec',
                                    text: 'Erreur de requête!',
                                });
                            }
                        }
                    });
                }
            });
        });

        $('#messageTable').DataTable( {
            paging: false,
            scrollY: 400
        } );

    $('#pageMessage').on('click', 'input[name=Add_Group]', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Ajouter groupe',
            html: `<input type="text" id="value" class="swal2-input" placeholder="Nom groupe">
<input type="text" id="icon" class="swal2-input" placeholder="Nom icone (ex: fa-calendar)">`,
            confirmButtonText: 'Valider',
            focusConfirm: false,
            preConfirm: () => {
                const value = Swal.getPopup().querySelector('#value').value
                const icon = Swal.getPopup().querySelector('#icon').value
                if (!value || !icon) {
                    Swal.showValidationMessage(`Please enter a value!`)
                }
                return { value: value, icon: icon}
            }
        }).then((result) => {
            if(result.value) {
                const value = result.value.value;
                const icon = result.value.icon;
                $.ajax({
                    url: 'ajax/add_group.php',
                    type: 'POST',
                    data: {
                        value: value,
                        icon: icon
                    },
                    dataType: 'json',
                    success: function (response) {
                        // Gestion de la réponse
                        resultId = parseInt(response.result);
                        if (resultId > 0) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Parfait',
                                text: 'Groupe ajouté!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = "index.php?page=admin_groupes";
                                }
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Echec',
                                text: 'Erreur de requête!',
                            });
                        }
                    }
                });
            }
        });

    });
    });
</script>
<?php
}}
    ?>