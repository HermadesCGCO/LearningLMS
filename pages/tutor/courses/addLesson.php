<?php

// TODO: Checar si la lección ya existe, si es asi, actualizar los datos en
// lugar de crear una nueva lección.
// Si la seccion ya existe, en lugar de /addLesson/(section) la URL seria
// /lesson/(lessonID)

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";

if (empty($_GET["section"])) {
    header("Location: /tutor/courses");
}

$course = new Course($conn);
$courseId = $course->getIdFromSection($_GET["section"]);
$course->linkCourse($courseId);

if (!$course->courseExists()) {
    // Puede que esto no sea necesario, pero just in case
    header("Location: /tutor/courses");
}

if (isset($_POST["save"])) {
    $result = $course->addLesson($_POST, $_GET["section"]);

    if ($result) {
	header("Location: /courses/edit/" . $courseId);
    }
}

if (isset($_POST["delete"])) {
}

$pageTitle = "Crear lección";

$includeTinyMCE = 1;

include "../elements/comprobation.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="pt-32pt">
    <div class="container page__container d-flex flex-column flex-md-row
		align-items-center text-center text-sm-left">
      <div class="flex d-flex flex-column flex-sm-row
		  align-items-center">

	<div class="mb-24pt mb-sm-0 mr-sm-24pt">
	  <h2 class="mb-0">Crear lección</h2>
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
		Información Báasica
	      </div>
	    </div>

	    <label class="form-label">Nombre</label>

	    <div class="form-group mb-24pt">
	      <input type="text" class="form-control form-control-lg"
		     placeholder="Nombre de la lección" name="name"
		     required>
	    </div>

	    <label class="form-label">Contenido</label>

	    <div class="form-group mb-24pt">
	      <textarea id="content" name="content"></textarea>
	      <small class="form-text text-muted">
		Aunque las lecciones por defecto son video, puedes incluir texto
		en ellas, si no especificas un video el contenido de la
		lección será lo que introduzcas en este campo.
	      </small>
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
			 value="Eliminar lección">
		</div>
	      </div>
	    </div>

	    <div class="page-separator">
	      <div class="page-separator__text">Video</div>
	    </div>

	    <div class="card">
	      <iframe id="injectable"></iframe>

	      <div class="card-body">
		<label class="form-label">URL</label>

		<input type="text" class="form-control"
		       placeholder="Ingresa la URL del video"
		       name="video" id="videoInput">

		<small class="form-text text-muted">
		  Ten en cuenta que la URL que ingreses será mostrada en forma
		  de frame.<br>
		  Si tu video está en YouTube, solo debes cambiar "/watch/" por
		  "/embed/".
		</small>
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
     selector: "#content",
     plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak"
 })

 window.onload = () => {
     var injectable = document.querySelector("#injectable")
     var videoInput = document.querySelector("#videoInput")

     videoInput.onchange = () => {
	 injectable.src = videoInput.value
     }
 }
</script>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
