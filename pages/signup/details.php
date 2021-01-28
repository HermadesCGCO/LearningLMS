<?php

$pageTitle = "Haz tu cuenta tuya";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

if (!isset($_SESSION["name"])) {
    header("Location: /signup");
}

if ($user->hasFinished()) {
    header("Location: /");
}

$info = $user->getUserInfo();

if (!$info) {
    die("Ha habido un error obteniendo la información de tu usuario");
}

if (isset($_POST["basic"])) {
    if ($user->updateInfo($_POST)) {
	$_SESSION["name"] = $_POST["name"];
	header("Refresh: 0");
	exit();
    }
}

if (isset($_POST["learning"])) {
    if ($user->setLearning($_POST)) {
	header("Location: /");
	exit();
    }
}

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">
  <?php include "elements/progress.php"; ?>

  <div class="pt-32pt">
    <div class="container page__container d-flex flex-column flex-md-row align-items-center
		text-center text-sm-left">
      <div class="flex d-flex flex-column flex-sm-row align-items-center">

	<div class="mb-24pt mb-sm-0 mr-sm-24pt">
	  <h2>Detalles de tu cuenta</h2>
	</div>

      </div>
    </div>
  </div>

  <div class="container page__container page-section">
    <div class="page-separator">
      <div class="page-separator__text">Información básica</div>
    </div>

    <div class="col-md-6 p-0">

      <form method="POST">
	<div class="form-group">
	  <label class="form-label">Nombres</label>
	  <input type="text" class="form-control" placeholder="Nombres"
		 name="name" value="<?php echo $info["name"]; ?>" required>
	</div>

	<div class="form-group">
	  <label class="form-label">Apellidos</label>
	  <input type="text" class="form-control" placeholder="Apellidos"
		 name="lastname" value="<?php echo $info["lastname"]; ?>"
		 required>
	</div>

	<div class="form-group">
	  <label class="form-label">Correo electrónico</label>
	  <input type="mail" class="form-control" placeholder="Email"
		 name="email" value="<?php echo $info["email"]; ?>" required>
	</div>

	<button class="btn btn-primary" type="submit" name="basic">
	  Actualizar
	</button>
      </form>

    </div>
  </div>

  <div class="container page__container page-section">
    <div class="page-separator">
      <div class="page-separator__text">¿Qué deseas aprender?</div>
    </div>
    <div class="col-md-6 p-0">
      <form method="POST">

	<div class="form-check">
	  <input class="form-check-input" type="checkbox" id="web"
		 name="web">
	  <label class="form-check-label" for="web">Desarrollo web</label>
	</div>

	<div class="form-check">
	  <input class="form-check-input" type="checkbox" id="hacking"
		 name="hacking">
	  <label class="form-check-label" for="hacking">Hacking</label>
	</div>

	<div class="form-check">
	  <input class="form-check-input" type="checkbox" id="movil"
		 name="movil">
	  <label class="form-check-label" for="movil">
	    Desarollo de aplicaciones móviles
	  </label>
	</div>

	<div class="form-check">
	  <input class="form-check-input" type="checkbox" id="Programacion"
		 name="programming">
	  <label class="form-check-label" for="Programacion">
	    Programación
	  </label>
	</div>

	<div class="form-check mb-4">
	  <input class="form-check-input" type="checkbox" id="linux"
		 name="linux">
	  <label class="form-check-label" for="linux">Linux</label>
	</div>

	<button class="btn btn-primary" type="submit" name="learning">Actualizar</button>

      </form>
    </div>
  </div>
</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
