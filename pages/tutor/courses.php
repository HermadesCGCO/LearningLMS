<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

$pageTitle = "Gestor de cursos";

include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";

$course = new Course($conn);

include "elements/comprobation.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="pt-32pt">
    <div class="container page__container d-flex flex-column flex-md-row
		align-items-center text-center text-sm-left">
      <div class="flex d-flex flex-column flex-sm-row align-items-center
		  mb-24pt mb-md-0">

	<div class="mb-24pt mb-sm-0 mr-sm-24pt">
	  <h2 class="mb-0">Cursos</h2>
	</div>

      </div>

      <div class="row" role="tablist">
	<div class="col-auto">
	  <a href="/courses/create" class="btn btn-outline-secondary">
	    Crear curso
	  </a>
	</div>
      </div>
    </div>
  </div>

  <div class="container page__container page-section">

    <div class="page-separator">
      <div class="page-separator__text">
	Mis cursos
      </div>
    </div>

    <div class="row">

      <?php

      include $_SERVER["DOCUMENT_ROOT"] . "/functions/drawCourseCard.php";

      $courses = $tutor->getMyCourses();

      for ($i = 0; $i < sizeof($courses); $i++) {
	  $course->linkCourse($courses[$i]);
	  $info = $course->getCourse($courses[$i]);
	  $youlearn = $course->getYouLearn(4);

	  echo drawCourseCard($info, $youlearn, "Editar", "edit");
      }
      ?>

    </div>

  </div>

</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
