<?php

$pageTitle = "Iniciar sesión";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

if (isset($_SESSION["name"])) {
    header("Location: /");
}

if (isset($_POST["submit"])) {
    include $_SERVER["DOCUMENT_ROOT"] . "/classes/User.php";

    $user = new User($conn);

    $mail = $_POST["mail"];
    $pass = $_POST["pass"];

    $login = $user->logIn($mail, $pass);

    if (!is_array($login)) {
	$_SESSION["name"] = $login;
	header("Location: /");
	exit();
    } else {
	print_r($login);
    }
}

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="pt-32pt pt-sm-64pt pb-32pt">
    <div class="container page__container">
      <form method="POST" class="col-md-5 p-0 mx-auto">

	<div class="form-group">
	  <label class="form-label" for="email">Email:</label>
	  <input id="email" type="email" class="form-control" name="mail"
		 placeholder="Tu dirección de correo electrónico..." required>
	</div>

	<div class="form-group">
	  <label class="form-label" for="pass">Contraseña:</label>
	  <input id="pass" type="password" class="form-control" name="pass"
		 placeholder="Ingresa tu contraseña" required>
	  <p class="text-right">
	    <a href="#" class="small">¿Olvidaste tu contraseña?</a>
	  </p>
	</div>

	<div class="text-center">
	  <button class="btn btn-primary" type="submit" name="submit">
	    Inicar sesión
	  </button>
	</div>

      </form>
    </div>
  </div>

</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
