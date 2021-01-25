<?php
// Préparation de la requête
$prep = $pdo->prepare( 'SELECT * FROM groupe ORDER BY ordre ASC' );
// Exécution de la requête
$prep->execute();
// Récupération des résultats dans un tableau associatif
$arrAll = $prep->fetchAll();

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
						<td ><input name='Modif_Group' value='Modifier' type='button'></td>
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

    $('#pageMessage').on('click', 'input[name=Add_Group]', function(){

        Swal.fire({
            title: 'Login Form',
            html: `<input type="text" id="login" class="swal2-input" placeholder="Username">
  <input type="password" id="password" class="swal2-input" placeholder="Password">`,
            confirmButtonText: 'Sign in',
            focusConfirm: false,
            preConfirm: () => {
                const login = Swal.getPopup().querySelector('#login').value
                const password = Swal.getPopup().querySelector('#password').value
                if (!login || !password) {
                    Swal.showValidationMessage(`Please enter login and password`)
                }
                return { login: login, password: password }
            }
        }).then((result) => {
            Swal.fire(`
    Login: ${result.value.login}
    Password: ${result.value.password}
  `.trim())
        });

    });
    });
</script>