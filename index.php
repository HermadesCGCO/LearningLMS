<?php

$pageTitle = "Inicio";
$includeHero = 1;
include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="border-bottom-2 py-16pt navbar-light bg-white border-bottom-2">
    <div class="container page__container">
      <div class="row align-items-center">

	<div class="d-flex col-md align-items-center border-bottom
		    border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
	  <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex
		      align-items-center justify-content-center mr-16pt">
	    <i class="material-icons text-white">subscriptions</i>
	  </div>
	  <div class="flex">
	    <div class="card-title mb-4pt">Cursos</div>
	    <p class="card-subtitle text-70">
	      Explora muchísimos cursos y demás formas de contenido totalmente
	      gratis
	    </p>
	  </div>
	</div>

	<div class="d-flex col-md align-items-center border-bottom
		    border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
	  <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex
		      align-items-center justify-content-center mr-16pt">
	    <i class="material-icons text-white">money</i>
	  </div>
	  <div class="flex">
	    <div class="card-title mb-4pt">Completamente gratis</div>
	    <p class="card-subtitle text-70">
	      Todo nuestro contenido es gratis, sin pagos escondidos o
	      políticas de privacidad abusivas.
	    </p>
	  </div>
	</div>

	<div class="d-flex col-md align-items-center border-bottom
		    border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
	  <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex
		      align-items-center justify-content-center mr-16pt">
	    <i class="material-icons text-white">update</i>
	  </div>
	  <div class="flex">
	    <div class="card-title mb-4pt">Acceso ilimitado</div>
	    <p class="card-subtitle text-70">
	      Accede a nuestro contenido por siempre sin suscripciones ni
	      pagos.
	    </p>
	  </div>
	</div>


      </div>
    </div>
  </div>

  <!-- Entradas de Blog -->
  <div class="page-section border-bottom-2">
    <div class="container page__container">

      <div class="page-separator">
	<div class="page-separator__text">
	  Entradas de Blog
	</div>
      </div>

    </div>
  </div>
  <!-- End Entradas de Blog -->

  <!-- Learning Paths -->
  <div class="page-section border-bottom-2">
    <div class="container page__container">

      <div class="page-separator">
	<div class="page-separator__text">
	  Paths
	</div>
      </div>

    </div>
  </div>
  <!-- End Learning Paths -->

  <!-- Featured Courses -->
  <div class="page-section border-bottom-2">
    <div class="container page__container">

      <div class="page-separator">
	<div class="page-separator__text">
	  Cursos destacados
	</div>
      </div>

      <div class="row card-group-row">

	<?php

	include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";
	include $_SERVER["DOCUMENT_ROOT"] . "/functions/drawCourseCard.php";

	$course = new Course($conn);
	$featured = $course->getFeaturedCourses(4);

	for ($i = 0; $i < sizeof($featured); $i++) {
	    $course->linkCourse($featured[$i]);
	    $info = $course->getCourse();
	    $youlearn = $course->getYouLearn(4);

	    echo drawCourseCard($info, $youlearn);
	}
	?>

      </div>

    </div>
  </div>
  <!-- End Featured Courses -->

</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
