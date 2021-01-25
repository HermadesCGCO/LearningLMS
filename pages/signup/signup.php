<?php

$pageTitle = "Registro";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

if (isset($_SESSION["name"])) {
    header("Location: /signup/details");
}

if (isset($_POST["submit"])) {
    include $_SERVER["DOCUMENT_ROOT"] . "/classes/User.php";

    $user = new User($conn);
    if ($user->createUser($_POST)) {
	$_SESSION["name"] = $_POST["name"];

	header("Location: /signup/details");
	exit();
    } else {
	// TODO: echo $user->errors;
	print_r($user->errors);
    }
}

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">

  <?php include "elements/progress.php"; ?>

  <div class="page-section container page__container">
    <div class="col-lg-10 p-0 mx-auto">
      <div class="row">
	<div class="col-md-12 mb-24pt mb-md-0">
	  <form method="POST">

	    <div class="form-group">
	      <label class="form-label">Nombre de Usuario:</label>
	      <input id="name" type="text" name="name" class="form-control"
		     placeholder="Username" required>
	    </div>

	    <div class="form-group">
	      <label class="form-label">Apellidos:</label>
	      <input id="lastname" type="text" name="lastname"
		     class="form-control" placeholder="Apellidos" required>
	    </div>

	    <div class="form-group">
	      <label class="form-label">Correo electrónico:</label>
	      <input id="email" type="email" name="email" class="form-control"
		     placeholder="Correo electrónico" required>
	    </div>

	    <div class="form-group">
	      <label class="form-label">Contraseña:</label>
	      <input id="pass1" type="password" name="pass1"
		     class="form-control" placeholder="Contraseña" required>
	    </div>

	    <div class="form-group">
	      <label class="form-label">Repite tu contraseña:</label>
	      <input id="pass2" type="password" name="pass2"
		     class="form-control" placeholder="Repite tu contraseña"
		     required>
	    </div>

	    <?php if (isset($user) && sizeof($user->errors) > 0) { ?>
	      <div class="alert alert-danger">
		Se han encontrado los siguientes errores:<br>
		<?php for ($i = 0; $i < sizeof($user->errors); $i++) { ?>
		  <?php echo $user->errors[$i] . "<br>"; ?>
		<?php } ?>
	      </div>
	    <?php } ?>

	    <button class="btn btn-primary" type="submit" name="submit">Crear cuenta</button>

	  </form>
	</div>

      </div>
    </div>
  </div>

</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php"; ?>
