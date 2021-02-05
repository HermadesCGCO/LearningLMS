<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Tutor.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: /");
    exit();
}

$course = new Course($conn);
$course->linkCourse($_GET["id"]);

if (!$course->courseExists()) {
    header("Location: /");
    exit();
}

$info = $course->getCourse();

$tutor = new Tutor($conn, $info["tutor"]);
$tutorInfo = $tutor->getInfo();

if (isset($user) && $user->isUserEnroledInCourse($_GET["id"])) {
    $enroled = 1;

    $progress = $user->getCourseProgress($_GET["id"]);
} else {
    $enroled = 0;
}

if (isset($_POST["postReview"]) && isset($user)) {
    if ($user->hasReviewedCourse($_GET["id"])) {
	if ($user->updateReview($_GET["id"], $_POST["stars"], $_POST["reviewContent"], $course, $notifications)) {
	    header("Refresh: 0");
	    exit();
	}
    } else {
	if ($user->reviewCourse($_GET["id"], $_POST["stars"], $_POST["reviewContent"], $course, $notifications)) {
	    header("Refresh: 0");
	    exit();
	}
    }
}

$includeTinyMCE = 1;

$pageTitle = $info["name"];

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="mdk-box bg-primary js-mdk-box mb-0"
       data-effects="blend-background">
    <div class="mdk-box__content">

      <div class="hero py-64pt text-center text-sm-left">
	<div class="container page__container">
	  <h1 class="text-white"><?php echo $info["name"]; ?></h1>
	  <p class="lead text-white-50 measure-hero-lead">
	    <?php echo $info["shortDesc"]; ?>
	  </p>
	</div>
      </div>

    </div>
  </div>

  <div class="navbar navbar-expand-sm navbar-light bg-white border-bottom-2
	      navbar-list p-0 m-0 align-items-center">
    <div class="container page__container">
      <ul class="nav navbar-nav flex align-items-sm-center">

	<li class="nav-item navbar-list__item">
	  <div class="media align-items-center">

	    <span class="media-left mr-16pt">
	      <span class="avatar avatar-sm mr-8pt2">
		<span class="avatar-title rounded-circle bg-primary">
		  <!-- TODO: Tutor profile picture -->
		  <i class="material-icons">account_box</i>
		</span>
	      </span>
	    </span>

	    <div class="media-body">
	      <a class="card-title m-0" href="/tutor/<?php echo $info["tutor"]; ?>">
		<?php echo $info["tutor"]; ?>
	      </a>
	      <p class="text-50 lh-l mb-0">Tutor</p>
	    </div>

	  </div>
	</li>

	<li class="nav-item navbar-list__item">
	  <i class="material-icons text-muted icon--left">schedule</i>
	  <?php echo $info["duration"]; ?> horas
	</li>

	<li class="nav-item navbar-list__item">
	  <i class="material-icons text-muted icon--left">assessment</i>
	  <?php echo $info["difficulty"]; ?>
	</li>

	<li class="nav-item ml-sm-auto text-sm-center flex-column
		   navbar-list__item">
	  <div class="rating rating-24">
	  </div>
	  <p class="lh-l mb-0">
	    <small class="text-muted"><?php

				      if (strlen($info["rating"]) > 3) {
					  echo substr($info["rating"], 0, 3);
				      } else {
					  echo $info["rating"];
				      }

				      ?>/5 estrellas</small>
	  </p>
	</li>

      </ul>
    </div>
  </div>

  <div class="page-section border-bottom-2">
    <div class="container page__container">

      <div class="page-separator">
	<div class="page-separator__text">Contenidos</div>
      </div>

      <div class="row mb-0">

	<div class="col-lg-8">

	  <div class="accordion js-accordion accordion--boxed list-group-flush"
	       id="parent">

	    <?php

	    $sections = $course->getSections();

	    for ($i = 0; $i < sizeof($sections); $i++) {
	    ?>

	      <div class="accordion__item <?php
					  if (isset($progress) && $progress["section"] == $sections[$i]["id"]) {
					      echo "open";
					  }
					  ?>">
		<a href="#" class="accordion__toggle"
		   data-toggle="collapse"
		   data-target="#course-toc-<?php echo $i; ?>"
		   data-parent="#parent">
		  <span class="flex"><?php echo $sections[$i]["name"]; ?></span>
		  <span class="accordion__toggle-icon material-icons">
		    keyboard_arrow_down
		  </span>
		</a>

		<div class="accordion__menu collapse <?php
						     if (isset($progress) && $progress["section"] == $sections[$i]["id"]) {
							 echo "show";
						     }
						     ?>"
		     id="course-toc-<?php echo $i; ?>">

		  <?php

		  $lessons = $course->getLessonsFromSection(
		      $sections[$i]["id"]
		  );

		  for ($j = 0; $j < sizeof($lessons); $j++) {
		  ?>

		    <div class="accordion__menu-link">
		      <span class="icon-holder icon-holder--small
				   <?php

				   if (isset($progress) && $progress["lesson"] == $lessons[$j]["id"]) {
				       echo "icon-holder--primary";
				   } else if (isset($progress) && $progress["lesson"] > $lessons[$j]["id"]) {
				       echo "icon-holder--default";
				   } else {
				       echo "icon-holder--dark";
				   }

				   ?>
				   rounded-circle d-inline-flex icon--left">

			<i class="material-icons icon-16pt">
			  <?php

			  if (isset($progress) && $progress["lesson"] == $lessons[$j]["id"]) {
			      echo "play_circle_outline";
			  } else if (isset($progress) && $progress["lesson"] > $lessons[$j]["id"]) {
			      echo "check";
			  } else  {
			      echo "lock";
			  }

			  ?>
			</i>
		      </span>
		      <a class="flex"
				<?php
				if (isset($progress) && $progress["lesson"] >= $lessons[$j]["id"]) {
				    echo 'href="/lesson/'. $lessons[$j]["id"] .'"';
				}
				?>
		      >
			<?php echo $lessons[$j]["name"]; ?>
		      </a>
		    </div>

		  <?php } ?>

		</div>
	      </div>

	    <?php } ?>

	  </div>

	</div>

	<?php if ($enroled == 0) { ?>
	  <div class="col-md-4">

	    <div class="card">
	      <div class="card-body py-16pt text-center">
		<span class="icon-holder icon-holder--outline-secondary
			     rounded-circle d-inline-flex mb-8pt">
		  <i class="material-icons">play_circle_outline</i>
		</span>
		<h4 class="card-title"><strong>Toma este curso</strong></h4>
		<p class="card-subtitle text-70 mb-24pt">
		  Obtén acceso a sus lecciones, sus quizzes y recursos
		  descargables.
		</p>
		<?php

		if (isset($_SESSION["name"])) {
		?>
		  <a href="/takeCourse/<?php echo $_GET["id"]; ?>" class="btn btn-accent mb-8pt">
		    Tomar curso
		  </a>
		<?php } else { ?>
		  <p class="mb-0">Para tomar este curso necesitas una cuenta,
		    pero no te preocupes, es gratis, no tendrás que pagar
		    nada.</p>
		  <p class="mb-0">Crea tu cuenta <a href="/signup">aquí</a></p>
		<?php } ?>
	      </div>
	    </div>

	  </div>
	<?php } ?>
      </div>

    </div>
  </div>

  <div class="page-section bg-alt border-bottom-2">

    <div class="container page__content">
      <div class="row">

	<div class="col-md-7">
	  <div class="page-separator">
	    <div class="page-separator__text">Sobre este curso</div>
	  </div>
	  <?php
	  echo htmlspecialchars_decode($info["description"]);
	  ?>
	</div>

	<div class="col-md-5">
	  <div class="page-separator">
	    <div class="page-separator__text bg-alt">¿Qué aprenderás?</div>
	  </div>

	  <ul class="list-unstyled">

	    <?php

	    $youlearn = $course->getYouLearn();

	    for ($i = 0; $i < sizeof($youlearn); $i++) {
	    ?>

	      <li class="d-flex align-items-center">
		<span class="material-icons text-50 mr-8pt">check</span>
		<span class="text">
		  <?php echo $youlearn[$i]["content"]; ?>
		</span>
	      </li>

	    <?php } ?>

	  </ul>
	</div>

      </div>
    </div>

  </div>

  <div class="page-section bg-alt border-bottom-2">
    <div class="container">
      <div class="row">

	<div class="col-md-7 mb-24pt mb-md-0">
	  <h4>Acerca del tutor</h4>
	  <?php

	  if (!empty($tutorInfo["description"])) {
	      echo htmlspecialchars_decode($tutorInfo["description"]);
	  } else {
	      echo "<p>Este tutor es perezoso y aún no escribe una presentación
para nostros</p>";
	  }

	  ?>
	</div>

	<div class="col-md-5 pt-sm-32pt pt-md-0 d-flex flex-column align-items-center
		    justify-content-start">
	  <div class="text-center">

	    <!-- TODO: Profile picture -->
	    <p class="avatar avatar-lg mr-8pt2">
	      <span class="avatar-title rounded-circle bg-primary">
		<i class="material-icons" style="font-size: 40px;">account_box</i>
	      </span>
	    </p>

	    <h4 class="m-0"><?php echo $info["tutor"]; ?></h4>

	    <p class="lb-1">
	      <small class="text-muted">
		<?php echo $tutorInfo["shortDesc"]; ?>
	      </small>
	    </p>

	    <div class="d-flex flex-column flex-sm-row align-items-center
			justify-content-start">

	      <a href="/tutor/<?php echo $info["tutor"]; ?>" class="btn btn-outline-primary mb-16pt
		       mb-sm-0 mr-sm-16pt">
		Seguir
	      </a>

	      <a href="/tutor/<?php echo $info["tutor"]; ?>" class="btn btn-outline-secondary">
		Ver perfil
	      </a>

	    </div>

	  </div>
	</div>

      </div>
    </div>
  </div>

  <div class="page-section border-bottom-2">
    <div class="container">
      <div class="page-headline text-center">
	<h2>Feedback</h2>
	<p class="lead text-70 measure-lead mx-auto">Lee lo que otros estudiantes piensan de este curso</p>
      </div>

      <div class="position-relative carousel-card p-0 mx-auto">
	<div class="position-relative carousel-card p-0 mx-auto">
	  <div class="row d-block js-mdk-carousel" id="carousel-feedback">

	    <a class="carousel-control-next js-mdk-carousel-control mt-n24pt"
	       href="#carousel-feedback" role="button" data-slide="next">
	      <span class="carousel-control-icon material-icons"
		    aria-hidden="true">keyboard_arrow_right</span>
	      <span class="sr-only">Siguiente</span>
	    </a>

	    <div class="mdk-carousel__content">

	      <?php

	      $feedback = $course->getFeaturedReviews(10);

	      for ($i = 0; $i < sizeof($feedback); $i++) {
	      ?>

		<div class="col-12 col-md-6">

		  <div class="card card-feedback card-body">
		    <blockquote class="blockquote mb-0">
		      <p class="text-70 small mb-0"><?php echo $feedback[$i]["content"]; ?></p>
		    </blockquote>

		    <div class="media ml-12pt">
		      <div class="media-left mr-12pt">
			<a class="avatar avatar-sm" href="/student/<?=$feedback[$i]["student"]?>">
			  <span class="avatar-title rounded-circle"><?php echo substr($feedback[$i]["student"], 0, 2); ?></span>
			</a>
		      </div>

		      <div class="media-body media-middle">
			<a class="card-title" href="/student/<?=$feedback[$i]["student"]?>">
			  <?php echo $feedback[$i]["student"]; ?>
			</a>

			<div class="rating mt-4pt">
			  <?php for ($j = 0; $j < $feedback[$i]["stars"]; $j++) {  ?>
			    <span class="rating__item"><span class="material-icons">star</span></span>
			  <?php } ?>
			</div>
		      </div>
		    </div>
		  </div>

		</div>

	      <?php } ?>

	    </div>

	  </div>
	</div>
      </div>
    </div>
  </div>

  <div class="page-section bg-alt border-bottom-2">

    <div class="container page__container">
      <div class="page-separator">
	<div class="page-separator__text">
	  Calificaciones
	</div>
      </div>

      <div class="row mb-32pt">
	<div class="col-md-3 mb-32pt mb-md-0">
	  <div class="display-1"><?php

				      if (strlen($info["rating"]) > 3) {
					  echo substr($info["rating"], 0, 3);
				      } else {
					  echo $info["rating"];
				      }

				      ?></div>
	  <div class="rating rating-24">
	    <?php

	    for ($i = 0; $i < floor($info["rating"]); $i++) {
	    ?>
	      <span class="rating__item">
		<span class="material-icons">
		  star
		</span>
	      </span>

	      <?php

	      if (floor($info["rating"]) < 5 && $i == (floor($info["rating"]) - 1)) {
		  $necesary = abs(floor($info["rating"]) - 5);

		  for ($j = 0; $j < $necesary; $j++) {
		      echo '
<span class="rating__item">
  <span class="material-icons">star_border</span>
</span>
		      ';
		  }
	      }

	      ?>
	    <?php } ?>
	  </div>
	  <p class="text-muted mb-0"><?php echo $info["ratings"]; ?> calificaciones</p>
	</div>

	<div class="col-md-9">

	  <?php

	  $ratings = $course->getRatings(10);

	  for ($i = 0; $i < sizeof($ratings); $i++) {
	  ?>

	    <div class="row mb-16pt">
	      <div class="col-md-3 mb-16pt">
		<div class="d-flex">
		  <a class="avatar avatar-sm mr-12pt" href="/student/<?=$ratings[$i]["student"]?>">
		    <span class="avatar-title rounded-circle">
		      <?php echo substr($ratings[$i]["student"], 0, 2); ?>
		    </span>
		  </a>
		  <div class="flex">
		    <p class="small text-muted m-0">
		      <?php echo $ratings[$i]["stars"]; ?> estrellas
		    </p>
		    <a href="<?=$ratings[$i]["student"]?>" class="card-title">
		      <?php echo $ratings[$i]["student"]; ?>
		    </a>
		  </div>
		</div>
	      </div>

	      <div class="col-md-9">
		<div class="rating mb-8pt">
		  <?php

		  for ($j = 0; $j < $ratings[$i]["stars"]; $j++) {
		  ?>
		    <span class="rating__item">
		      <span class="material-icons">
			star
		      </span>
		    </span>

		    <?php
		    if ($ratings[$i]["stars"] < 5 && $j == ($ratings[$i]["stars"] - 1)) {
			$necesary = abs($ratings[$i]["stars"] - 5);

			for ($k = 0; $k < $necesary; $k++) {
			    echo '
<span class="rating__item">
  <span class="material-icons">star_border</span>
</span>
			    ';
			}
		    }
		    ?>

		  <?php } ?>
		</div>
		<p class="text-70 mb-0">
		  <?php echo $ratings[$i]["content"]; ?>
		</p>
	      </div>
	    </div>

	  <?php } ?>

	</div>
      </div>
    </div>

  </div>

  <?php
  if (isset($_SESSION["name"])) {

      if ($user->hasReviewedCourse($_GET["id"])) {
	  $review = $user->getCourseReview($_GET["id"]);
      }

  ?>
  <div class="page-section border-bottom-2">
    <div class="container">
      <div class="page-headline text-center">
	<h2>Deja tu calificación</h2>
	<p class="lead text-70 measure-lead mx-auto">
	  Cuéntale al tutor que piensas de este curso
	</p>
      </div>

      <form method="POST">
	<div class="row">
	  <div class="col-md-10">
	    <div class="form-label mb-4pt">Estrellas</div>

	    <div class="form-group">
	      <select class="form-control" name="stars">
		<?php
		for ($i = 0; $i < 5; $i++) {
		?>

		  <option value="<?=$i+1?>"
			  <?php if (isset($review) && (($review["stars"] - 1)) == $i) { echo "selected"; } ?>>
		    <?=$i+1?>
		  </option>

		<?php } ?>
	      </select>
	    </div>

	    <div class="form-label mb-8pt">¿Qué piensas de este curso?</div>

	    <div class="form-group">
	      <textarea id="reviewContent" name="reviewContent">
		<?php

		if (isset($review)) {
		    echo $review["content"];
		}

		?>
	      </textarea>
	    </div>

	    <button class="btn btn-accent" name="postReview">Publicar</button>
	  </div>
	</div>
      </form>
    </div>
  </div>

  <script>
   tinymce.init({
       selector: "#reviewContent",
       plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak"
   })
  </script>
  <?php } ?>

</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
