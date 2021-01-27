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

	$course = new Course($conn);
	$featured = $course->getFeaturedCourses(4);

	for ($i = 0; $i < sizeof($featured); $i++) {
	    $course->linkCourse($featured[$i]);
	    $info = $course->getCourse();
	?>
	  <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

	    <div class="card card-sm card--elevated p-relative o-hidden overlay
			overlay--primary-dodger-blue js-overlay
			card-group-row__card" data-toggle="popover"
		 data-trigger="click">

	      <a class="card-img-top js-image" href="#" data-position=""
		 data-height="140">
		<img src="<?php echo $info["thumb"]; ?>">

		<span class="overlay__content">
		  <span class="overlay__action d-flex flex-column text-center">
		    <i class="material-icons icon-32pt">play_circle_outline</i>
		    <span class="card-title text-white">Visitar</span>
		  </span>
		</span>
	      </a>

	      <div class="card-body flex">
		<div class="d-flex">
		  <div class="flex">
		    <a class="card-title" href="#">
		      <?php echo $info["name"]; ?>
		    </a>
		    <small class="text-50 font-weight-bold mb-4pt">
		      <?php echo $info["tutor"]; ?>
		    </small>
		  </div>
		</div>
		<div class="d-flex">
		  <div class="rating-flex">
		    <!-- TODO: Rating -->
		  </div>
		</div>
	      </div>

	      <div class="card-footer">

		<div class="row justify-content-between">
		  <div class="col-auto d-flex align-items-center">
		    <span class="material-icons icon-16pt text-50 mr-4pt">
		      access_time
		    </span>
		    <p class="flex text-50 lh-l mb-0">
		      <?php echo $info["duration"]; ?>
		      horas
		    </p>
		  </div>

		  <div class="col-auto d-flex align-items-center">
		    <span class="material-icons icon-16pt text-50 mr-4pt">
		      play_circle_outline
		    </span>
		    <p class="flex text-50 lh-l mb-0">
		      <?php echo $info["lessons"]; ?>
		      lecciones
		    </p>
		  </div>
		</div>

	      </div>

	    </div>

	    <div class="popoverContainer d-none">
	      <div class="media">
		<div class="media-left mr-12pt">
		  <img src="<?php

			    if ($info["category"] == "Hacking") {
				echo "/public/images/icons/hacking.jpg";
			    } else if ($info["category"] == "Programación") {
				echo "/public/images/icons/programming.jpg";
			    } else if ($info["category"] == "Desarrollo web") {
				echo "/public/images/icons/web.jpg";
			    } else if ($info["category"] == "Linux") {
				echo "/public/images/icons/linux.jpg";
			    } else if ($info["category"] == "Desarrollo aplicaciones móviles") {
				echo "/public/images/icons/mobile.jpg";
			    }

			    ?>"
		       width="40" height="40" class="rounded">

		</div>

		<div class="media-body">
		  <h1 class="card-title mb-0"><?php echo $info["name"]; ?></h1>
		  <p class="lh-l mb-0">
		    <span class="text-50 small">por</span>
		    <span class="text-50 small font-weight-bold">
		      <?php echo $info["tutor"]; ?>
		    </span>
		  </p>
		</div>
	      </div>

	      <p class="my-16pt text-70">
		<?php echo $info["shortDesc"]; ?>
	      </p>

	      <div class="mb-16pt">
		<?php

		$things = $course->getYouLearn(4);

		for ($j = 0; $j < sizeof($things); $j++) {

		?>

		  <div class="d-flex align-items-center">

		    <span class="material-icons icon-16pt text-50
				 mr-8pt">check</span>

		    <p class="flex text-50 lh-l mb-0">
		      <small><?php echo $things[$j]; ?></small>
		    </p>

		  </div>

		<?php } ?>
	      </div>

	      <div class="row align-items-center">
		<div class="col-auto">
		  <div class="d-flex align-items-center mb-4pt">
		    <span class="material-icons icon-16pt text-50 mr-4pt">
		      access_time
		    </span>
		    <p class="flex text-50 lh-l mb-0">
		      <small><?php echo $info["duration"]; ?> horas</small>
		    </p>
		  </div>

		  <div class="d-flex align-items-center mb-4pt">
		    <span class="material-icons icon-16pt text-50 mr-4pt">
		      play_circle_outline
		    </span>
		    <p class="flex text-50 lh-l mb-0">
		      <small><?php echo $info["lessons"]; ?> lecciones</small>
		    </p>
		  </div>

		  <div class="d-flex align-items-center mb-4pt">
		    <span class="material-icons icon-16pt text-50 mr-4pt">
		      assessment
		    </span>
		    <p class="flex text-50 lh-l mb-0">
		      <small><?php echo $info["difficulty"]; ?></small>
		    </p>
		  </div>

		</div>

		<div class="col text-right">
		  <a href="#" class="btn btn-primary">
		    Visitar
		  </a>
		</div>
	      </div>
	    </div>

	  </div>
	<?php } ?>

      </div>

    </div>
  </div>
  <!-- End Featured Courses -->

  <!-- Feedback -->
  <div class="page-section">
    <div class="container page__container">
      <div class="page-headline text-center">
	<h2>Feedback</h2>
	<p class="lead measure-lead mx-auto text-70">
	  Descubre lo que otros estudiantes y entidades piensan sobre nuestro
	  contenido, nuestra plataforma y nuestras herramientas.
	</p>
      </div>

      <h1>TODO: This</h1>
    </div>
  </div>
  <!-- End Feedback -->

</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
