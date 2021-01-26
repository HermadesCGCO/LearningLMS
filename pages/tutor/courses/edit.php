<?php

// TODO: Si la ID del curso ingresado no existe, crear uno nuevo

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: /tutor/courses");
}

$id = $_GET["id"];

$course = new Course($conn);
$course->linkCourse($id);

$info = $course->getCourse();

$pageTitle = $info["name"] . " - Editar";

$includeTinyMCE = 1;

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="pt-32pt">
    <div class="container page__container d-flex flex-column flex-md-row
		align-items-center text-center text-sm-left">
      <div class="flex d-flex flex-column flex-sm-row
		  align-items-center">

	<div class="mb-24pt mb-sm-0 mr-sm-24pt">
	  <h2 class="mb-0">Editar curso</h2>
	</div>

      </div>
    </div>
  </div>

  <div class="page-section border-bottom-2">
    <div class="container page__container">

      <form method="POST">
	<div class="row">
	  <div class="col-md-8">

	    <div class="page-separator">
	      <div class="page-separator__text">
		Información Básica
	      </div>
	    </div>

	    <label class="form-label">Nombre</label>

	    <div class="form-group mb-24pt">
	      <input type="text" class="form-control form-control-lg"
		     placeholder="Nombre del curso" name="name"
		     value="<?php echo $info["name"]; ?>">
	    </div>

	    <div class="form-group mb-32pt">
	      <label class="form-label">Descripción</label>
	      <textarea id="description" name="desc">
		<?php echo nl2br($info["description"]); ?>
	      </textarea>
	    </div>

	  </div>

	  <div class="col-md-4">

	    <div class="card">
	      <div class="card-header text-center">
		<input type="submit" name="save" class="btn btn-accent"
			     value="Guardar cambios">
	      </div>

	      <div class="list-group list-group-flush">
		<div class="list-group-item">
		  <input type="submit" name="delete" class="text-danger btn"
			 value="Eliminar curso">
		</div>
	      </div>
	    </div>

	  </div>
	</div>
	
      </form>
    </div>
  </div>

</div>

<script>
 tinymce.init({
     selector: "#description",
     plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak"
 })
</script>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
