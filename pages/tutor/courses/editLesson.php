<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Lesson.php";

if (empty($_GET["lesson"])) {
    header("Location: /tutor/courses");
}

$lesson = new Lesson($conn);
$lesson->linkLesson($_GET["lesson"]);

if (!$lesson->lessonExists()) {
    header("Location: /tutor/courses");
}

$courseId = $lesson->getCourse();

if (isset($_POST["save"])) {
    if ($lesson->updateLesson($_POST)) {
	header("Location: /courses/edit/" . $courseId);
	exit();
    }
}

if (isset($_POST["delete"])) {
    if ($lesson->deleteLesson()) {
	header("Location: /courses/edit/" . $courseId);
	exit();
    }
}

$info = $lesson->getInfo();

$pageTitle = $info["name"];

$includeTinyMCE = 1;

include "../elements/comprobation.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="pt-32pt">
    <div class="container page__container d-flex flex-column flex-md-row
		align-items-center text-sm-left">
      <div class="flex d-flex flex-column flex-sm-row
		  align-items-center">

	<div class="mb-24pt mb-sm-0 mr-sm-24pt">
	  <h2 class="mb-0">Editar lección</h2>
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
		Editar lección
	      </div>
	    </div>

	    <label class="form-label">Nombre</label>

	    <div class="form-group mb-24pt">
	      <input type="text" class="form-control form-control-lg"
		     placeholder="Nombre de la lección" name="name"
		     value="<?php echo $info["name"]; ?>" required>
	    </div>

	    <label class="form-label">Contenido</label>

	    <div class="form-group mb-24pt">
	      <textarea id="content" name="content">
		<?php echo $info["content"]; ?>
	      </textarea>
	    </div>

	  </div>

	  <div class="col-md-4">

	    <div class="card">
	      <div class="card-header text-center">
		<input type="submit" name="save" class="btn btn-accent"
			     value="Actualizar">
	      </div>

	      <div class="list-group list-group-flush">
		<div class="list-group-item">
		  <input type="submit" name="delete" class="text-danger btn"
			 value="Eliminar lección">
		</div>
	      </div>
	    </div>

	    <div class="page-separator">
	      <div class="page-separator__text">
		Video
	      </div>
	    </div>

	    <div class="card">
	      <iframe id="injectable" src="<?php echo $info["video"]; ?>"></iframe>

	      <div class="card-body">
		<label class="form-label">URL</label>

		<input type="text" class="form-control"
		       placeholder="Ingresa la URL del video"
		       name="video" id="videoInput"
		       value="<?php echo $info["video"]; ?>">

		<small class="form-text text-muted">
		  Ten en cuenta que la URL que ingreses será mostrada en forma
		  de frame.<br>
		  Si tu video está en YouTube, solo debes cambiar "/watch/" por
		  "/embed/".
		</smal>
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
