<?php

// TODO: Antes de aumentar el valor de completedLessons checar si esta leccion
// no ha sido completada

// TODO: Boton "Marcar como completada" al final de la leccion, si es necesario
// agregarle un valor a "completedLessons" y dirigir al usuario a la siguiente
// leccion.
// Si al marcar una leccion como completada y es la ultima leccion de la seccion
// pasar con la primera leccion de la siguiente seccion

// TODO: Si el video esta "empty" mostrar solamente el "content" de la leccion.

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

include $_SERVER["DOCUMENT_ROOT"] . "/classes/Lesson.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Tutor.php";

if (!isset($_SESSION["name"])) {
    header("Location: /login");
    exit();
}

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: /explore");
    exit();
}

$lesson = new Lesson($conn);
$lesson->linkLesson($_GET["id"]);

if (!$lesson->lessonExists()) {
    header("Location: /explore");
    exit();
}

$courseId = $lesson->getCourse();

$course = new Course($conn);
$course->linkCourse($courseId);
$courseInfo = $course->getCourse();

$tutor = new Tutor($conn, $courseInfo["tutor"]);
$tutorInfo = $tutor->getInfo();

$info = $lesson->getInfo();

$progress = $user->getCourseProgress($courseInfo["id"]);

if ($_GET["id"] > $progress["lesson"]) {
    header("Location: /course/" . $courseInfo["id"]);
    exit();
}

if ($progress["lesson"] == $_GET["id"]) {
    // This variable is used to determine wether we should increment or not the
    // value of completedLessons
    $addLesson = true;
}

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="navbar navbar-light border-0 navbar-expand">
    <div class="container page__container">

      <div class="media flex-nowrap">
	<div class="media-left mr-16pt">
	  <a href="/course/<?php echo $courseInfo["id"]; ?>">
	    <img src="<?php echo $courseInfo["thumb"]; ?>" class="rounded"
		 width="40" height="40">
	  </a>
	</div>

	<div class="media-body">
	  <a href="/course/<?php echo $courseInfo["id"]; ?>" class="card-title
		   text-body mb-0">
	    <?php echo $courseInfo["name"]; ?>
	  </a>

	  <p class="lh-1 d-flex align-items-center mb-0">
	    <span class="text-50 small font-weight-bold mr-8pt">
	      <?php echo $courseInfo["tutor"]; ?>
	    </span>
	    <span class="text-50 small">
	      <?php echo $tutorInfo["shortDesc"]; ?>
	    </span>
	  </p>
	</div>
      </div>

    </div>
  </div>

  <div class="bg-primary pb-lg-64pt py-32pt">
    <div class="container page__container">

      <nav class="course-nav">
	<?php

	$section = $lesson->getSection();
	$lessons = $course->getLessonsFromSection($section);

	for ($i = 0; $i < sizeof($lessons); $i++) {
	?>

	  <a data-toggle="tooltip" data-placement="bottom"
	     data-title="<?php echo $lessons[$i]["name"]; ?>"
	     <?php

	     if ($progress["lesson"] >= $lessons[$i]["id"]) {
		 echo 'href="/lesson/'. $lessons[$i]["id"] .'"';
	     }

	     ?>
	  >
	    <span class="material-icons <?php if ($_GET["id"] == $lessons[$i]["id"]) { echo "text-primary"; } ?>">
	      <?php

	      if ($progress["lesson"] > $lessons[$i]["id"]) {
		  echo "check";
	      } else if ($progress["lesson"] == $lessons[$i]["id"]) {
		  echo "account_circle";
	      } else if ($progress["lesson"] < $lessons[$i]["id"]) {
		  echo "lock";
	      }

	      ?>
	    </span>
	  </a>

	<?php } ?>
      </nav>

      <?php if (!empty($info["video"])) { ?>
	<div class="js-player embed-responsive embed-responsive-16by9 mb-32pt">
	  <div class="player embed-responsive-item">
	    <div class="player__content">

	      <iframe class="embed-responsive-item"
		      src="<?php echo $info["video"]; ?>"></iframe>

	    </div>
	  </div>
	</div>
      <?php } ?>

      <div class="d-flex flex-wrap align-items-end mb-16pt">
	<h1 class="text-white flex m-0"><?php echo $info["name"]; ?></h1>
      </div>

      <div class="hero__lead measure-hero-lead text-white-50 mb-24pt">
	<?php echo $info["content"]; ?>
      </div>

      <?php if ($progress["lesson"] <= $_GET["id"]) { ?>
      <div class="d-flex flex-column flex-sm-row align-items-center justify-content-start">
	<a onclick="complete()"
	   class="btn btn-outline-white mb-16pt mb-sm-0 mr-sm-16pt">
	  Lección completada
	</a>
      </div>
      <?php } ?>

    </div>
  </div>

</div>

<script>
 function complete() {
     $.get("/api/private/progress/goToNextSectionAndUpdateProgress.php", {
	 id: <?php echo $_GET["id"]; ?>
     }).done((data) => {
	 if (data != "-1") {
	     window.location.href = "/lesson/" + data
	 } else {
	     alert("Ha habido un error, por favor intentalo mas tarde")
	 }
     })
 }
</script>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
