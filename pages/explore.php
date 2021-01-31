<?php

$pageTitle = "Explorar";

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";
include $_SERVER["DOCUMENT_ROOT"] . "/functions/drawCourseCard.php";

$course = new Course($conn);

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="page-section border-bottom-2">
    <div class="container page__container">

      <div class="d-flex flex-column flex-sm-row align-items-sm-center
		  mb-24pt" style="white-space: nowrap">
	<small class="flex text-muted text-headings text-uppercase mr-3 mb-2 mb-sm-0">
	  Mostrando 4 cursos por cada sección
	</small>
      </div>

      <div class="page-separator">
	<div class="page-separator__text">
	  Lo más popular
	</div>
      </div>

      <div class="row card-group-row">

	<?php

	$popular = $course->getPopularCourses(4);

	for ($i = 0; $i < sizeof($popular); $i++) {
	    $course->linkCourse($popular[$i]);
	    $info = $course->getCourse();
	    $youlearn = $course->getYouLearn(2);

	    echo drawCourseCard($info, $youlearn);
	}

	?>

      </div>

      <div class="page-separator">
	<div class="page-separator__text">
	  Cursos Destacados
	</div>
      </div>

      <div class="row card-group-row">
	<?php

	$featured = $course->getFeaturedCourses(4);

	for ($i = 0; $i < sizeof($featured); $i++) {
	    $course->linkCourse($featured[$i]);
	    $info = $course->getCourse();
	    $youlearn = $course->getYouLearn(4);

	    echo drawCourseCard($info, $youlearn);
	}

	?>
      </div>

      <?php

      $categories = $course->getCategories();

      for ($i = 0; $i < sizeof($categories); $i++) {

      ?>

	<div class="page-separator">
	  <div class="page-separator__text">
	    <?php echo $categories[$i]; ?>
	  </div>
	</div>

	<div class="row card-group-row">

	  <?php

	  $courses = $course->getCoursesFromCategory($categories[$i], 4);

	  for ($j = 0; $j < sizeof($courses); $j++) {
	      $course->linkCourse($courses[$j]);
	      $info = $course->getCourse();
	      $youlearn = $course->getYouLearn(4);

	      echo drawCourseCard($info, $youlearn);
	  ?>

	  <?php } ?>

	</div>

      <?php } ?>

    </div>
  </div>

</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
