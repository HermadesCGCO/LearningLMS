<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

$pageTitle = "Panel de Control";

if (!isset($user) || (isset($user) && !$user->isUserTutor())) {
    header("Location: /");
}

include $_SERVER["DOCUMENT_ROOT"] . "/classes/Tutor.php";

$tutor = new Tutor($conn, $_SESSION["name"]);

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

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

		    <a href="javascript:void(0)" class="sort"
		       data-sort="js-list-values-date">
		      Fecha
		    </a>
		  </th>

		</tr>
	      </thead>
	      <tbody class="list">

		<?php

		$courses = $tutor->getMyCourses(4);

		for ($i = 0; $i < sizeof($courses); $i++) {

		?>

		  <tr>
		    <td>
		      <div class="d-flex flex-nowrap align-items-center">

			<a href="/tutor/edit/"
			   class="avatar avatar-4by3 overlay overlay--primary
				 mr-12pt">
			  <img src="<?php echo $courses[$i]["thumb"]; ?>"
			       class="avatar-img rounded">
			  <span class="overlay__content"></span>
			</a>

			<div class="flex">

			  <a class="card-title js-lists-values-course"
			     href="/tutor/edit">
			    <?php echo $courses[$i]["name"]; ?>
			  </a>

			  <small class="text-muted mr-1">
			    <a class="js-lists-values-students"><?php echo $courses[$i]["students"] ?></a>
			  </small>

			</div>

		      </div>
		    </td>
		    <td class="text-right">
		      <small class="text-muted text-uppercase
				    js-lists-values-date">
			<?php echo $courses[$i]["date"]; ?>
		      </small>
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
	  <div class="page-separator__text">Comentario destacado</div>
	</div>

	<div class="card">
	  <div class="card-body">
	    TODO: Esto
	  </div>
	</div>

      </div>

    </div>
  </div>

</div>

<?php

$includeDashboard = 1;
include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
