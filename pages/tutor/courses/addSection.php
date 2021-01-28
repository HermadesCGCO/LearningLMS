<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: /tutor/courses");
}

$id = $_GET["id"];

$course = new Course($conn);
$course->linkCourse($id);

if (!$course->courseExists()) {
    header("Location: /tutor/courses");
}

if (isset($_POST["create"])) {
    $result = $course->createSection(htmlspecialchars($_POST["name"]));

    if (!is_array($result)) {
	header("Location: /courses/edit/" . $_GET["id"]);
	exit();
    } else {
	print_r($result);
	exit();
    }
}

$pageTitle = "Crear sección";

include "../elements/comprobation.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="pt-32pt">
    <div class="container page__container d-flex flex-column flex-md-row
		align-items-center text-center text-sm-left">
      <div class="flex d-flex flex-column flex-sm-row
		  align-items-center">

	<div class="mb-24pt mb-sm-0 mr-sm-24pt">
	  <h2 class="mb-0">Crear sección</h2>
	</div>

      </div>
    </div>
  </div>

  <div class="page-section border-bottom-2">
    <div class="container page__container">

      <form method="POST">

	<div class="row">
	  <div class="col-md-10">

	    <div class="page-separator">
	      <div class="page-separator__text">
		Información de la sección
	      </div>
	    </div>

	    <label class="form-label">Nombre</label>

	    <div class="form-group mb-24pt">
	      <input type="text" class="form-control form-control-lg"
		     placeholder="Nombre de la sección" name="name" required>
	    </div>

	    <input type="submit" name="create" class="btn btn-accent"
		   value="Crear">

	  </div>
	</div>

      </form>

    </div>
  </div>

</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
