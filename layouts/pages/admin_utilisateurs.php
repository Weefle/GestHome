<?php
// Préparation de la requête
$prep = $pdo->prepare( 'SELECT * FROM utilisateur ORDER BY id ASC' );
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
        Administration des utilisateurs
    </div>

    <div class="contentContent" >
        <input name="Add_User" value="Ajouter un utilisateur" type="button">
        <div class="dataTableContainer">
            <table id="messageTable" class="stripe row-border dataTable">

                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Type</th>
                    <th>Login</th>
                    <th>Date</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
                </thead>

                <tbody>
                <?php
                for ($i=0;$i<count($arrAll);$i++) {
                    $arr = $arrAll[$i];
                    $utilisateur_id = $arr['id'];
                    $utilisateur_login = $arr['login'];
                    $utilisateur_name = $arr['nom'];
                    $utilisateur_surname = $arr['prenom'];
                    $utilisateur_password = $arr['password'];
                    $utilisateur_date = $arr['date_derniere_connexion'];
                    $type_utilisateur_id = $arr['type_utilisateur_id'];
                    $prep = $pdo->prepare( 'SELECT label FROM type_utilisateur WHERE id ="' . $type_utilisateur_id . '"' );
                    $prep->execute();
                    $arrRes = $prep->fetch();
                    $type_utilisateur = $arrRes[0];

                    echo "<tr class='utilisateur' data-utilisateur-id=$utilisateur_id data-utilisateur-login='$utilisateur_login' data-utilisateur-password='$utilisateur_password' >
                        <td >$utilisateur_name</td >
						<td >$utilisateur_surname</td >
						<td >$type_utilisateur</td >
						<td >$utilisateur_login</td >
						<td >$utilisateur_date</td >
						<td  ><input name='Modif_User' value='Modifier' type='button'></td>
						<td  ><input name='Suppr_User' value='Supprimer' type='button'></td>
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

        $('#pageMessage').on( 'click', 'input[name=Suppr_User]', function(e) {
            e.preventDefault();
            var parent = $(this).parents('.utilisateur');

            // Récupération de l'id de la sonde
            var utilisateurId = parent.data('utilisateur-id');
            var utilisateurLogin = parent.data('utilisateur-login');

                Swal.fire({
                    title: 'Etes vous sûr de supprimer ' + utilisateurLogin + '?',
                    text: "Vous ne pourrez pas revenir en arrière!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'ajax/suppr_user.php',
                            type: 'POST',
                            data: {
                                id: utilisateurId
                            },
                            dataType: 'json',
                            success: function (response) {
                                // Gestion de la réponse
                                resultId = parseInt(response.result);
                                if (resultId > 0) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Parfait',
                                        text: 'Utilisateur supprimé!',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location = "index.php?page=admin_utilisateurs";
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

        $('#pageMessage').on( 'click', 'input[name=Modif_User]', function(e){
            e.preventDefault();
            var parent = $(this).parents('.utilisateur');

            // Récupération de l'id de la sonde
            var utilisateurId = parent.data('utilisateur-id');
            var utilisateurLogin = parent.data('utilisateur-login');
            var utilisateurPassword = parent.data('utilisateur-password');

            Swal.fire({
                title: 'Modifier utilisateur',
                html: `<input type="text" id="login" value="${utilisateurLogin}" class="swal2-input" placeholder="Saisir nouvel identifiant">
                        <input type="password" id="password" class="swal2-input" placeholder="Saisir nouveau mot de passe">`,
                confirmButtonText: 'Valider',
                focusConfirm: false,
                preConfirm: () => {
                    const login = Swal.getPopup().querySelector('#login').value
                    const password = Swal.getPopup().querySelector('#password').value
                    if (!login || !password) {
                        Swal.showValidationMessage(`Please enter a value!`)
                    }
                    return { login: login, password: password}
                }
            }).then((result) => {
                if(result.value) {
                    const login = result.value.login;
                    const password = result.value.password;
                    $.ajax({
                        url: 'ajax/modif_user.php',
                        type: 'POST',
                        data: {
                            login: login,
                            password: password,
                            id: utilisateurId
                        },
                        dataType: 'json',
                        success: function (response) {
                            // Gestion de la réponse
                            resultId = parseInt(response.result);
                            if (resultId > 0) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Parfait',
                                    text: 'Nom de l\'utilisateur modifié!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location = "index.php?page=admin_utilisateurs";
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

        $('#pageMessage').on('click', 'input[name=Add_User]', function(e){
            e.preventDefault();

                var liste;
                $.ajax({
                    url: 'ajax/get_user_types.php',
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
                                title: 'Selectionnez un type d\'utilisateur',
                                input: 'select',
                                inputOptions: {
                                    liste
                                },
                                inputPlaceholder: 'Type d\'utilisateur',
                                showCancelButton: true,
                                inputValidator: (value) => {
                                    return new Promise((resolve) => {

                                        var type = value;

                                        Swal.fire({
                                            title: 'Ajouter utilisateur',
                                            html: `<input type="text" id="login" class="swal2-input" placeholder="Login">
                                                <input type="password" id="password" class="swal2-input" placeholder="Password">
                                                <input type="text" id="nom" class="swal2-input" placeholder="Name">
                                                <input type="text" id="prenom" class="swal2-input" placeholder="LastName">`,
                                            confirmButtonText: 'Valider',
                                            focusConfirm: false,
                                            preConfirm: () => {
                                                const login = Swal.getPopup().querySelector('#login').value
                                                const password = Swal.getPopup().querySelector('#password').value
                                                const nom = Swal.getPopup().querySelector('#nom').value
                                                const prenom = Swal.getPopup().querySelector('#prenom').value

                                                if (!login || !password || !nom || !prenom) {
                                                    Swal.showValidationMessage(`Please enter a value!`)
                                                }
                                                return {login: login, password: password, nom: nom, prenom: prenom}
                                            }
                                        }).then((result) => {
                                            if (result.value) {
                                                const login = result.value.login;
                                                const password = result.value.password;
                                                const nom = result.value.nom;
                                                const prenom = result.value.prenom;
                                                $.ajax({
                                                    url: 'ajax/add_user.php',
                                                    type: 'POST',
                                                    data: {
                                                        login: login,
                                                        password: password,
                                                        nom: nom,
                                                        prenom: prenom,
                                                        type: type
                                                    },
                                                    dataType: 'json',
                                                    success: function (response) {
                                                        // Gestion de la réponse
                                                        resultId = parseInt(response.result);
                                                        if (resultId > 0) {
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Parfait',
                                                                text: 'Utilisateur ajouté!',
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    window.location = "index.php?page=admin_utilisateurs";
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



        });
    });
</script>

<?php
    }}
        ?>
