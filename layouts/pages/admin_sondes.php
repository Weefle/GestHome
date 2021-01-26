<?php
// Préparation de la requête
$prep = $pdo->prepare( 'SELECT * FROM groupe ORDER BY ordre ASC' );
// Exécution de la requête
$prep->execute();
// Récupération des résultats dans un tableau associatif
$arrAll = $prep->fetchAll();
require('layouts/pages/admin.php');
?>
<div id="pageMessage">

    <div class="contentTitre">
        Administration des sondes
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
                    echo "<tr >
                        <td > $groupe_name</td >
						<td class='textCenter fontSize20 fa $groupe_icon' ></td >
						<td > $sonde_count</td >
						<td class='groupe' data-groupe-id=$groupe_id ><input name='Modif_Group' value='Modifier' type='button'></td>
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

        $('#pageMessage').on( 'click', 'input[name=Modif_Group]', function(e){
            e.preventDefault();
            var parent = $(this).parents('.groupe');

            // Récupération de l'id de la sonde
            var groupeId = parent.data('groupe-id');

            Swal.fire({
                title: 'Modifier nom groupe',
                html: `<input type="text" id="value" class="swal2-input" placeholder="Nom groupe">`,
                confirmButtonText: 'Modifier',
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
            /*var liste;
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
                            title: 'Select a group',
                            input: 'select',
                            inputOptions: {
                                liste
                            },
                            inputPlaceholder: 'Groupe',
                            showCancelButton: true
                        });
                        //alert('Login');
                    } else {
                        alert("Il n'y a pas d'éléments!");
                    }
                }
            });*/

            Swal.fire({
                title: 'Login Form',
                html: `<input type="text" id="login" class="swal2-input" placeholder="Username">
  <input type="password" id="password" class="swal2-input" placeholder="Password">`,
                confirmButtonText: 'Sign in',
                focusConfirm: false,
                backdrop: `
    rgba(0,0,123,0.4)
    url("img/nyan-cat.gif")
    left top
    no-repeat
  `,
                preConfirm: () => {
                    const login = Swal.getPopup().querySelector('#login').value
                    const password = Swal.getPopup().querySelector('#password').value
                    if (!login || !password) {
                        Swal.showValidationMessage(`Please enter login and password`)
                    }
                    return { login: login, password: password }
                }
            }).then((result) => {
                if(result.value) {
                    const login = result.value.login;
                    const password = result.value.password;
                    $.ajax({
                        url: 'ajax/login_user.php',
                        type: 'POST',
                        data: {
                            login: login,
                            password: password
                        },
                        dataType: 'json',
                        success: function (response) {
                            // Gestion de la réponse
                            resultId = parseInt(response.result);
                            if (resultId > 0) {
                                alert('Login');
                            } else {
                                alert('Login incorrect');
                            }
                        }
                    });
                }
            });

        });
    });
</script>