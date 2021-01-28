			
		<div id="footer">
			GestHome - le <?php echo date('d M Y'); ?>
            <a class="footer_img" href="https://www.github.com/Weefle/GestHome">
                <img src='img/github.png' />
            </a>
		</div>

        <script>
            $(document).ready( function(){

                $('#header').on('click', '.header_img', function(e) {

                    e.preventDefault();

                    var login = $(this).data('login');


                    //alert(login);

                    if (login === false){

                        Swal.fire({
                            title: 'Login Form',
                            html: `<input type="text" id="login" class="swal2-input" placeholder="Username">
<input type="password" id="password" class="swal2-input" placeholder="Password">`,
                            confirmButtonText: 'Sign in',
                            focusConfirm: false,
                            backdrop: `rgba(0,0,123,0.4) url("img/nyan-cat.gif") left top no-repeat`,
                            preConfirm: () => {
                                const login = Swal.getPopup().querySelector('#login').value
                                const password = Swal.getPopup().querySelector('#password').value
                                if (!login || !password) {
                                    Swal.showValidationMessage(`Please enter login and password`)
                                }
                                return {login: login, password: password}
                            }
                        }).then((result) => {
                            if (result.value) {
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
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Parfait',
                                                text: 'Utilisateur connecté!',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location = "index.php";
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
                }else if (login === true)
                {

                    $.ajax({
                        url: 'ajax/logout_user.php',
                        type: 'POST',
                        data: {
                        },
                        dataType: 'json',
                        success: function (response) {
                            // Gestion de la réponse
                            resultId = parseInt(response.result);
                            if (resultId > 0) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Parfait',
                                    text: 'Utilisateur déconnecté!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location = "index.php";
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
        </script>

	</body>
</html>