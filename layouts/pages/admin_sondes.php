<?php
// Préparation de la requête
$prep = $pdo->prepare( 'SELECT * FROM sonde ORDER BY id ASC' );
// Exécution de la requête
$prep->execute();
// Récupération des résultats dans un tableau associatif
$arrAll = $prep->fetchAll();

//var_dump(hash('sha512', 'test'));

require('layouts/pages/admin.php');
if (isset($_SESSION['login']) && isset($_SESSION['password']) && isset($_SESSION['type'])) {

if ($_SESSION['type'] == 1) {
?>
<div id="pageMessage">

    <div class="contentTitre">
        Administration des sondes
    </div>

    <div class="contentContent">
        <input name="Add_Sonde" value="Ajouter une sonde" type="button">
        <div class="dataTableContainer">
            <table id="messageTable" class="stripe row-border dataTable">

                <thead>
                <tr>
                    <th>Sonde</th>
                    <th>Code</th>
                    <th>Type de sonde</th>
                    <th>Groupe</th>
                    <th>Valeur</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
                </thead>

                <tbody>
                <?php
                for ($i=0;$i<count($arrAll);$i++) {
                    $arr = $arrAll[$i];
                    $groupe_id = $arr['groupe_id'];
                    $sonde_id = $arr['id'];
                    $sonde_name = $arr['label'];
                    $sonde_code = $arr['code'];
                    $value = $arr['valeur'];
                    $sonde_type_id = $arr['type_sonde_id'];
                    $prep = $pdo->prepare( 'SELECT label FROM groupe WHERE id ="' . $groupe_id . '"' );
                    $prep->execute();
                    $arrRes = $prep->fetch();
                    //var_dump($arrRes);
                    $groupe = $arrRes[0];
                    $prep = $pdo->prepare( 'SELECT label FROM type_sonde WHERE id ="' . $sonde_type_id . '"' );
                    $prep->execute();
                    $arrr = $prep->fetch();
                    //var_dump($arrr);
                    $type = $arrr[0];
                    echo "<tr class='sonde' data-sonde-id=$sonde_id data-sonde-name='$sonde_name' >
                        <td >$sonde_name</td >
						<td >$sonde_code</td >
						<td >$type</td >
						<td >$groupe</td >
						<td >$value</td >
						<td  ><input name='Modif_Sonde' value='Modifier' type='button'></td>
						<td  ><input name='Suppr_Sonde' value='Supprimer' type='button'></td>
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

        $('#pageMessage').on( 'click', 'input[name=Suppr_Sonde]', function(e) {
            e.preventDefault();
            var parent = $(this).parents('.sonde');

            // Récupération de l'id de la sonde
            var sondeId = parent.data('sonde-id');
            var sondeName = parent.data('sonde-name');

            Swal.fire({
                title: 'Etes vous sûr de supprimer ' + sondeName + '?',
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax/suppr_sonde.php',
                        type: 'POST',
                        data: {
                            id: sondeId
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
                                        window.location = "index.php?page=admin_sondes";
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

        $('#pageMessage').on( 'click', 'input[name=Modif_Sonde]', function(e){
            e.preventDefault();
            var parent = $(this).parents('.sonde');

            // Récupération de l'id de la sonde
            var sondeId = parent.data('sonde-id');
            var sondeName = parent.data('sonde-name');
            //alert(groupeName);

            Swal.fire({
                title: 'Modifier sonde',
                html: `<input type="text" id="value" class="swal2-input" placeholder="${sondeName}">`,
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
                        url: 'ajax/modif_sonde.php',
                        type: 'POST',
                        data: {
                            value: value,
                            id: sondeId
                        },
                        dataType: 'json',
                        success: function (response) {
                            // Gestion de la réponse
                            resultId = parseInt(response.result);
                            if (resultId > 0) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Parfait',
                                    text: 'Nom de la sonde modifié!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location = "index.php?page=admin_sondes";
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

        $('#pageMessage').on('click', 'input[name=Add_Sonde]', function(e){
            e.preventDefault();

            var liste;
            $.ajax({
                url: 'ajax/get_groups.php',
                type: 'POST',
                data: {},
                dataType: 'json',
                success: function (response) {
                    // Gestion de la réponse
                    resultId = parseInt(response.result);
                    if (resultId > 0) {
                        liste = response.list;
                        //alert(list);
                        Swal.fire({
                            title: 'Selectionnez un groupe',
                            input: 'select',
                            inputOptions: {
                                liste
                            },
                            inputPlaceholder: 'Groupe',
                            showCancelButton: true,
                            inputValidator: (value) => {
                                return new Promise((resolve) => {
                                    var groupe = value;
                                    var liste;
                                    $.ajax({
                                        url: 'ajax/get_types.php',
                                        type: 'POST',
                                        data: {},
                                        dataType: 'json',
                                        success: function (response) {
                                            // Gestion de la réponse
                                            resultId = parseInt(response.result);
                                            if (resultId > 0) {
                                                liste = response.list;
                                                //alert(list);
                                                Swal.fire({
                                                    title: 'Selectionnez une sonde',
                                                    input: 'select',
                                                    inputOptions: {
                                                        liste
                                                    },
                                                    inputPlaceholder: 'Type de sonde',
                                                    showCancelButton: true,
                                                    inputValidator: (value) => {
                                                        return new Promise((resolve) => {
                                                            var sonde_type = value;
                                                            Swal.fire({
                                                                title: 'Ajouter sonde',
                                                                html: `<input type="text" id="value" class="swal2-input" placeholder="Nom sonde">`,
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
                                                                        url: 'ajax/add_sonde.php',
                                                                        type: 'POST',
                                                                        data: {
                                                                            value: value,
                                                                            group: groupe,
                                                                            type: sonde_type

                                                                        },
                                                                        dataType: 'json',
                                                                        success: function (response) {
                                                                            // Gestion de la réponse
                                                                            resultId = parseInt(response.result);
                                                                            if (resultId > 0) {
                                                                                Swal.fire({
                                                                                    icon: 'success',
                                                                                    title: 'Parfait',
                                                                                    text: 'Sonde ajouté!',
                                                                                }).then((result) => {
                                                                                    if (result.isConfirmed) {
                                                                                        window.location = "index.php?page=admin_sondes";
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

                                                        })
                                                    }
                                                });
                                                //alert('Login');
                                            } else {
                                                alert("Il n'y a pas d'éléments!");
                                            }
                                        }
                                    });
                                })
                            }
                        });
                        //alert('Login');
                    } else {
                        alert("Il n'y a pas d'éléments!");
                    }
                }
            });


        });
    });
</script>

<?php
}}
?>