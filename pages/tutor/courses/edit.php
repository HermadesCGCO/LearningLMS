<?php

// TODO:
// Eliminar/editar secciones

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: /tutor/courses");
}

$id = $_GET["id"];

$course = new Course($conn);
$course->linkCourse($id);

$info = $course->getCourse();

if ($course->courseExists()) {
    $type = "update";
} else {
    $type = "create";
}

$youlearn = $course->getYoulearn();

if (isset($_POST["save"])) {
    if ($type == "update") {
	$result = $course->updateCourse($_POST);

	if (!is_array($result)) {
	    header("Refresh: 0");
	    exit();
	} else {
	    print_r($result);
	    exit();
	}
    } else if ($type == "create") {
	$result = $course->createCourse($_POST, $_SESSION["name"]);

	if (!is_array($result)) {
	    header("Location: /courses/edit/" . $result);
	    exit();
	} else {
	    print_r($result);
	    exit();
	}
    }
}

if (isset($_POST["delete"])) {
    if ($course->courseExists()) {
	if ($course->deleteCourse()) {
	    header("Location: /tutor/courses");
	}
    }
}

$name = (!empty($info["name"])) ? $info["name"] : "Crear curso";

$pageTitle = $name . " - Editar";

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
		     value="<?php echo $info["name"]; ?>" required>
	    </div>

	    <label class="form-label">Descripción corta</label>

	    <div class="form-group mb-24pt">
	      <input type="text" class="form-control"
		     placeholder="Descripción corta" name="shortDesc"
		     value="<?php echo $info["shortDesc"]; ?>"
		     name="shortDesc" required>
	      <small class="form-text text-muted">Ingresa una descripción corta
		de tu curso que incite a los estudiantes a tomarlo.</small>
	    </div>

	    <div class="form-group mb-32pt">
	      <label class="form-label">Descripción</label>
	      <textarea id="description" name="description">
		<?php echo $info["description"]; ?>
	      </textarea>
	    </div>

	    <div class="page-separator">
	      <div class="page-separator__text">
		¿Qué aprenderá el estudiante?
	      </div>
	    </div>

	    <div class="row" id="injectYoulearn">
	      <!-- TODO: Al crear un nuevo youlearn, el API si se creo de
		   manera exitosa, devuelve el ID del youlearn creado,
		   luego, se anade el valor devuelto al ID del elemento. -->
	      <?php

	      for ($i = 0; $i < sizeof($youlearn); $i++) {

	      ?>
		<div id="deletable-<?php echo $youlearn[$i]["id"]; ?>" class="row col-md-12">
		  <div class="col-md-10 form-group">
		    <input type="text" class="form-control"
			   id="text-<?php echo $youlearn[$i]["id"]; ?>"
			   value="<?php echo $youlearn[$i]["content"]; ?>">
		  </div>
		  <div class="col-md-2 form-group">
		    <input type="button" class="btn btn-accent" value="Eliminar"
			   id="delete-<?php echo $youlearn[$i]["id"]; ?>">
		  </div>
		</div>

		<script>
		 var text<?php echo $youlearn[$i]["id"]; ?> =
		     document.getElementById("text-<?php echo $youlearn[$i]["id"]; ?>")
		 var trash<?php echo $youlearn[$i]["id"]; ?> =
		     document.getElementById("delete-<?php echo $youlearn[$i]["id"]; ?>")
		 var deletable<?php echo $youlearn[$i]["id"]; ?> =
		     document.getElementById("deletable-<?php echo $youlearn[$i]["id"]; ?>");

		 text<?php echo $youlearn[$i]["id"]; ?>.onchange = () => {
		     updateYouLearn("<?php echo $youlearn[$i]["id"]; ?>",
				    text<?php echo $youlearn[$i]["id"]; ?>.value
		     )
		 }

		 trash<?php echo $youlearn[$i]["id"]; ?>.onclick = () => {
		     deleteYouLearn(
			 <?php echo $youlearn[$i]["id"]; ?>,
			 deletable<?php echo $youlearn[$i]["id"]; ?>
		     )
		 }
		</script>
	      <?php } ?>

	    </div>

	    <input type="button" class="btn btn-primary mb-3" value="Nuevo"
		   id="newYoulearn">

	    <script>
	     // TODO: Optimizar esto

	     var newYoulearn = document.getElementById("newYoulearn")
	     var inject = document.getElementById("injectYoulearn")

	     newYoulearn.onclick = () => {
		 if (document.getElementById("createdYoulearn") === null) {
		     // TODO: Inject youlearn

		     var elm = document.createElement("div")
		     elm.id = "createdYoulearn"
		     elm.className = "row col-md-12"
		     elm.innerHTML = `
<div class="col-md-10 form-group">
	     <input type="text" class="form-control"
		    onchange="updateNewYoulearn()"
		    id="newYoulearnText">
</div>
<div class="col-md-2 form-group">
	     <input type="button" class="btn btn-primary"
		    value="Cancelar" onclick="cancelNewYouLearn()"
		    id="cancelNewYoulearn">
</div>
		     `

		     inject.appendChild(elm)
		 }
	     }

	     function updateNewYoulearn() {
		 var newTextContent = document.getElementById("newYoulearnText")

		 $.get("/api/private/courses/createYoulearn.php", {
		     content: newTextContent.value,
		     course: <?php echo $_GET["id"]; ?>
		 }).done((data) => {
		     if (data != "Error") {
			 var created = document.getElementById("createdYoulearn")
			 created.id = "deletable-" + data

			 var cancelButton = document.getElementById("cancelNewYoulearn")
			 cancelButton.id = "delete-" + data
			 cancelButton.className = "btn btn-accent"
			 cancelButton.value = "Eliminar"

			 var text = document.getElementById("newYoulearnText")
			 text.id = "text-" + data
			 text.onchange = () => {
			     updateYouLearn(data, text.value)
			 }

			 cancelButton.onclick = () => {
			     deleteYouLearn(
				 data,
				 created
			     )
			 }
		     }
		 })
	     }

	     function cancelNewYouLearn() {
		 var created = document.getElementById("createdYoulearn")
		 created.parentNode.removeChild(created)
	     }
	    </script>

	    <div class="page-separator">
	      <div class="page-separator__text">Secciones</div>
	    </div>

	    <div class="accordion js-accordion accordion--boxed
			mb-24pt" id="parent">
	      <?php

	      $sections = $course->getSections();

	      for ($i = 0; $i < sizeof($sections); $i++) {

	      ?>

		<div class="accordion__item">
		  <a href="#" class="accordion__toggle collapsed"
		     data-toggle="collapse"
		     data-target="#course-toc-<?php echo $i; ?>"
		     data-parent="#parent">
		    <span class="flex"><?php echo $sections[$i]["name"]; ?></span>
		    <span class="accordion__toggle-icon material-icons">
		      keyboard_arrow_down
		    </span>
		  </a>

		  <div class="accordion__menu collapse"
		       id="course-toc-<?php echo $i; ?>">
		    <div class="accordion__menu-link d-flex justify-content-end">
		      <a class="text-primary" href="/course/<?php echo "addLesson/" . $sections[$i]["id"]; ?>">
			Crear lección
		      </a>
		    </div>

		    <div id="sortable-<?php echo $i;?>">
		      <?php

		      // TODO: Actualizar lección
		      $lessons = $course->getLessonsFromSection($sections[$i]["id"]);

		      for ($j = 0; $j < sizeof($lessons); $j++) {

		      ?>

			<div class="accordion__menu-link"
			     id="<?php echo $lessons[$j]["id"]; ?>">
			  <i class="material-icons text-70 icon-16pt icon--left">
			    drag_handle
			  </i>
			  <a class="flex" href="/course/editLesson/<?php echo $lessons[$j]["id"]; ?>">
			    <?php echo $lessons[$j]["name"]; ?>
			  </a>
			</div>

		      <?php } ?>
		    </div>

		  </div>
		</div>

	      <?php } ?>
	    </div>

	    <a href="/section/add/<?php echo $_GET["id"]; ?>"
	       class="btn btn-outline-secondary mb-24pt mb-sm-0">
	      Agregar sección
	    </a>
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

	    <div class="page-separator">
	      <div class="page-separator__text">Miniatura</div>
	    </div>

	    <div class="card">
	      <img src="<?php echo $info["thumb"]; ?>" id="injectThumb"
		     class="img-fluid rounded-top">
	      <div class="card-body">
		<label class="form-label">URL</label>
		<input type="text" class="form-control"
		       value="<?php echo $info["thumb"]; ?>"
		       placeholder="Ingresa la URL de una imágen"
		       name="thumb"
		       id="thumbInput" required>
	      </div>
	    </div>

	    <div class="page-separator">
	      <div class="page-separator__text">Opciones</div>
	    </div>

	    <div class="card">
	      <div class="card-body">
		<div class="form-group">
		  <label class="form-label">Categoría</label>
		  <select name="category" class="form-control custom-select" required>
		    <?php

		    $categories = $course->getCategories();

		    for ($i = 0; $i < sizeof($categories); $i++) {
	
		    ?>
		      <option value="<?php echo $categories[$i] ?>"
			      <?php if ($categories[$i] == $info["category"]) {
				  echo "selected";
			      } ?>>
			<?php echo $categories[$i]; ?>
		      </option>
		    <?php } ?>
		  </select>
		</div>

		<div class="form-group">
		  <label class="form-label">Dificultad</label>
		  <select name="difficulty" class="form-control custom-select" required>
		    <?php

		    $difficulties = $course->getDifficulties();

		    for ($i = 0; $i < sizeof($difficulties); $i++) {

		    ?>

		      <option value="<?php echo $difficulties[$i]; ?>"
			      <?php
			      if ($difficulties[$i] == $info["difficulty"]) {
				  echo "selected";
			      }
			      ?>
		      >
			<?php echo $difficulties[$i]; ?>
		      </option>

		    <?php } ?>
		  </select>
		</div>

		<div class="form-group mb-0">
		  <label class="form-label">Duración</label>
		  <div class="row">
		    <div class="col-md-6">
		      <div class="input-group form-inline">
			<input type="number" class="form-control"
			       name="duration"
			       value="<?php echo $info["duration"]; ?>"
			       required>
		      </div>
		    </div>
		  </div>
		  <small class="form-text text-muted">
		    Ingresa la duración del curso (en horas).
		  </small>
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

 window.onload = () => {
     var injectThumb = document.querySelector("#injectThumb")
     var thumbInput = document.querySelector("#thumbInput")

     thumbInput.onchange = () => {
	 injectThumb.src = thumbInput.value
     }

     <?php for ($i = 0; $i < sizeof($sections); $i++) { ?>
     $("#sortable-<?php echo $i; ?>").sortable({
	 update: () => {
	     update<?php echo $i; ?>()
	 }
     })

     function update<?php echo $i; ?>() {
	 var arr = $("#sortable-<?php echo $i; ?>").sortable("toArray");

	 for (var i = 0; i < arr.length; i++) {
	     var lessonId = arr[i]
	     var order = i+1

	     $.get("/api/private/lessons/updateOrder.php", { lesson: lessonId, order: order }).done((data) => {
		 //alert(data)
	     })
	 }
     }
     <?php } ?>
 }

 function updateYouLearn(id, content) {
     $.get("/api/private/courses/updateYoulearn.php", {
	 id: id,
	 content: content
     })
 }

 function deleteYouLearn(id, element) {
     $.get("/api/private/courses/deleteYoulearn.php", {
	 id: id
     }).done((data) => {
	 if (data == "1") {
	     element.innerHTML = ""
	 }
     })
 }
</script>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
