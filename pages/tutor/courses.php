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

      $courses = $tutor->getMyCourses();

      for ($i = 0; $i < sizeof($courses); $i++) {
	  $info = $course->getCourse($courses[$i]);
      ?>
	<div class="col-sm-6 col-md-4 col-xl-3">
	  <div class="card card-sm card--elevated p-relative o-hidden overlay
		      overlay--primary js-overlay mdk-reveal js-mdk-reveal"
	       data--partial-height="44"
	       data-toggle="popover" data-trigger="click">

	    <a href="/courses/edit/<?php echo $info["id"];?>" class="js-image" data-position="">
	      <img src="<?php echo $info["thumb"]; ?>" width="430" height="168">

	      <span class="overlay__content align-items-start
			   justify-content-start">
		<span class="overlay__action card-body d-flex
			     align-items-center">
		  <i class="material-icons mr-4pt">edit</i>
		  <span class="card-title text-white">
		    Editar
		  </span>
		</span>
	      </span>
	    </a>

	    <div class="mdk-reveal__content">
	      <div class="card-body">
		<div class="d-flex">
		  <div class="flex">
		    <a class="card-title mb-4pt"
		       href="/courses/edit/<?php echo $info["id"]; ?>">
		      <?php echo $info["name"]; ?>
		    </a>
		  </div>
		  <a href="/courses/edit/<?php echo $info["id"] ?>"
		     class="ml-4pt material-icons text-20
			   card-course__icon-favorite">edit</a>
		</div>
		<div class="d-flex">
		  <div class="rating flex">
		    <!-- TODO: Rating -->
		  </div>
		  <small class="text-50">
		    <?php echo $info["duration"]; ?> horas
		  </small>
		</div>
	      </div>
	    </div>

	  </div>

	  <div class="popoverContainer d-none">
	    <div class="media">
	      <div class="media-left mr-12pt">
		<!-- TODO: Reemplazar esta imagen por la imagen de la
		     categoria de la que este curso hace parte. -->
		<img src="<?php echo $info["thumb"]; ?>"
		     width="40" height="40" class="rounded">
	      </div>

	      <div class="media-body">
		<div class="card-title mb-0">
		  <?php echo $info["name"]; ?>
		</div>
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
		  <p class="flex text-50 lh-1 mb-0">
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
		  <p class="flex text-50 lh-1 mb-0">
		    <small><?php echo $info["duration"]; ?>
		      horas</small>
		  </p>
		</div>

		<div class="d-flex align-items-center mb-4pt">
		  <span class="material-icons icon-16pt text-50 mr-4pt">
		    play_circle_outline
		  </span>
		  <p class="flex text-50 lh-1 mb-0">
		    <small><?php echo $info["lessons"]; ?>
		      lecciones</small>
		  </p>
		</div>

		<div class="d-flex align-items-center">
		  <span class="material-icons icon-16pt text-50 mr-4pt">
		    assessment
		  </span>
		  <p class="flex text-50 lh-1 mb-0">
		    <small><?php echo $info["difficulty"]; ?></small>
		  </p>
		</div>

	      </div>

	      <div class="col text-right">
		<a href="/courses/edit/<?php echo $info["id"]; ?>"
		   class="btn btn-primary">
		  Editar curso
		</a>
	      </div>
	    </div>
	  </div>
	</div>

      <?php } ?>


    </div>

  </div>

</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
