<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Tutor.php";

if (!isset($_GET["name"]) || empty($_GET["name"])) {
    // TODO: Llevar a la pagina de explorar tutores
    header("Location: /");
    exit();
}

if (!$user->isUserTutor($_GET["name"])) {
    // TODO: LLevar a la pagina de explorar tutores
    header("Location: /");
    exit();
}

$info = $user->getUserInfo($_GET["name"]);

$tutor = new Tutor($conn, $_GET["name"]);
$tutorInfo = $tutor->getInfo();

$course = new Course($conn);

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="page-section bg-primary">
    <div class="container page__container d-flex flex-column flex-md-row
		align-items-center text-center text-md-left">
      <img src="/public/images/illustration/illustration_teacher_white.svg"
	   width="104" class="mr-md-32pt mb-32pt mb-md-0">

      <div class="flex mb-32pt mb-md-0">
	<h2 class="text-white mb-0">
	  <?php echo $info["name"] . " " . $info["lastname"]; ?>
	</h2>
	<p class="lead text-white-50 d-flex align-items-center">Tutor</p>
      </div>

    </div>
  </div>

  <div class="page-section bg-alt border-bottom-2">
    <div class="container page__container">
      <div class="row">
	<div class="col-md-6">

	  <h4>Sobre mí</h4>
	  <p>
	    <?php echo htmlspecialchars_decode($tutorInfo["description"]); ?>
	  </p>

	</div>

	<div class="col-md-6">

	  <h4>Contáctame</h4>

	  <p>
	    TODO: Poner redes sociales, email y demas maneras de contacto con el
	    tutor.
	  </p>

	</div>
      </div>
    </div>
  </div>

  <div class="container page__container page-section">

    <div class="page-headline text-center">
      <h2>Expande tu conocimiento</h2>
      <p class="lead text-70 col-lg-8 mx-auto">
	Cursos por <?php echo $_GET["name"]; ?>
      </p>
    </div>

    <div class="row card-group-row mb-8pt">

      <?php

      $courses = $tutor->getMyCourses();

      for ($i = 0; $i < sizeof($courses); $i++) {
	  $course->linkCourse($courses[$i]);
	  $courseInfo = $course->getCourse();
      ?>

	<div class="col-sm-6 card-group-row__col">
	  <div class="card card-sm card-group-row__card">
	    <div class="card-body d-flex align-items-center">
	      <a href="/course/<?php echo $courseInfo["id"]; ?>"
		 class="avatar avatar-4by3 overlay overlay--primary mr-12pt">
		<img src="<?php echo $courseInfo["thumb"]; ?>" class="
			  avatar-img rounded">
		<span class="overlay__content"></span>
	      </a>

	      <div class="flex">
		<a class="card-title mb-4pt"
		   href="/course/<?php echo $courseInfo["id"]; ?>">
		  <?php echo $courseInfo["name"]; ?>
		</a>

		<div class="d-flex align-items-center">
		  <div class="rating mr-8pt">
		    TODO: Rating
		  </div>
		</div>
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
