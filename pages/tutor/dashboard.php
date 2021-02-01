<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

$pageTitle = "Panel de Control";

include "elements/comprobation.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="pt-32pt">
    <div class="container page__container d-flex flex-column flex-md-row
		align-items-center text-center text-sm-left">
      <div class="flex d-flex flex-column flex-sm-row align-items-center
		  mb-24pt mb-md-0">

	<div class="mb-24pt mb-sm-0 mr-sm-24pt">
	  <h2 class="mb-0">Panel de control</h2>
	</div>

      </div>
    </div>
  </div>

  <div class="container page__container page-section">

    <div class="page-separator">
      <div class="page-separator__text">Información de tutor</div>
    </div>

    <div class="row mb-8pt">
      <div class="col-lg-6">

	<div class="page-separator">
	  <div class="page-separator__text">Cursos recientes</div>
	</div>

	<div class="card">
	  <div data-toggle="lists" data-lists-values='[
			    "js-lists-values-course",
			    "js-lists-values-students",
			    "js-lists-values-date"
			    ]'
	       data-lists-sort-by="js-lists-values-date"
	       data-lists-sort-desc="true"
	       class="table-responsive">

	    <table class="table table-flush table-nowrap">
	      <thead>
		<tr>

		  <th colspan="2">
		    <a href="javascript:void(0)" class="sort"
		       data-sort="js-lists-values-course">
		      Curso
		    </a>

		    <a href="javascript:void(0)" class="sort"
		       data-sort="js-lists-values-students">
		      Estudiantes
		    </a>

		  </th>

		</tr>
	      </thead>
	      <tbody class="list">

		<?php

		include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";

		$course = new Course($conn);

		$courses = $tutor->getMyCourses(4);

		for ($i = 0; $i < sizeof($courses); $i++) {
		    $info = $course->getCourse($courses[$i]);
		?>

		  <tr>
		    <td>
		      <div class="d-flex flex-nowrap align-items-center">

			<a href="/courses/edit/<?php echo $info["id"]; ?>"
			   class="avatar avatar-4by3 overlay overlay--primary
				 mr-12pt">
			  <img src="<?php echo $info["thumb"]; ?>"
			       class="avatar-img rounded">
			  <span class="overlay__content"></span>
			</a>

			<div class="flex">

			  <a class="card-title js-lists-values-course"
			     href="/courses/edit/<?php echo $info["id"]; ?>">
			    <?php echo $info["name"]; ?>
			  </a>

			  <small class="text-muted mr-1">
			    <a class="js-lists-values-students">
			      <?php echo $info["students"] ?>
			      estudiantes
			    </a>
			  </small>

			</div>

		      </div>
		    </td>
		    <td class="text-right">
		    </td>
		  </tr>

		<?php } ?>

	      </tbody>
	    </table>

	  </div>
	</div>

      </div>

      <div class="col-lg-6">

	<div class="page-separator">
	  <div class="page-separator__text">Calificaciones</div>
	</div>

	<div class="card">
	  <div class="card-body">

	    <?php

	    $myCourses = $tutor->getMyCourses();
	    shuffle($myCourses);

	    $toGet = $myCourses[0];

	    $course = new Course($conn);
	    $course->linkCourse($toGet);
	    $courseInfo = $course->getCourse();

	    $review = $course->getRandomReview();

	    if (!empty($review)) {
	    ?>

	      <div class="media">
		<div class="media-left mr-12pt">
		  <a class="avatar avatar-sm" href="#">
		    <span class="avatar-title rounded">
		      <?php echo substr($review["student"], 0, 2); ?>
		    </span>
		  </a>
		</div>

		<div class="media-body d-flex flex-column">
		  <div class="d-flex align-items-center">
		    <a class="card-title" href="#">
		      <?php echo $review["student"]; ?>
		    </a>
		  </div>

		  <span class="text-muted">
		    en
		    <a class="text-50" href="/course/<?=$toGet;?>"
		       style="text-decoration: underline">
		      <?=$courseInfo["name"]?>
		    </a>
		  </span>

		  <p class="mt-1 mb-0 text-70">
		    <?=$review["content"];?>

		    <div class="rating">
		      <?php for ($i = 0; $i < $review["stars"]; $i++) { ?>
			<span class="rating__item">
			  <span class="material-icons">
			    star
			  </span>
			</span>
		      <?php } ?>
		    </div>

		  </p>

		  <button class="btn btn-primary col-md-4" onclick="featureReview(<?=$review["id"];?>)">
		    Destacar
		  </button>

		</div>
	      </div>

	    <?php } else {
		echo "Tu curso <b>" . $courseInfo["name"] . "</b> aun no tiene calificaciones";
	    }?>

	  </div>
	</div>

	<small class="text-muted">
	  Al recargar la página aparecerá una calificación aleatoria
	  de un curso tuyo aleatorio,
	  en este panel puedes ponerla como destacada.
	</small>

      </div>

    </div>
  </div>

</div>

<script>

 function featureReview(reviewId) {
     $.get("/api/private/courses/featureReview.php", { id: reviewId }).done((data) => {
	 if (data === "1") {
	     window.location.reload()
	 }
     })
 }

</script>

<?php

$includeDashboard = 1;
include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
