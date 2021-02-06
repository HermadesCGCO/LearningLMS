<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

if (!isset($_GET["name"]) || empty($_GET["name"])) {
    header("Location: /");
    exit();
}

if (!isset($_SESSION["name"])) {
    include $_SERVER["DOCUMENT_ROOT"] . "/classes/User.php";

    $user = new User($conn);
}

$user->linkUser($_GET["name"]);

$info = $user->getUserInfo();
$customInfo = $user->getCustomInfo();

if (isset($_SESSION["name"])) {
    $user->linkUser($_SESSION["name"]);
}

$pageTitle = $info["name"];

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>

<div class="mdk-header-layout__content page-content">

  <div class="page-section bg-primary border-bottom-white">
    <div class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-md-left">
      <img src="/public/images/illustration/illustration_student_white.svg"
	   class="mr-md-32pt mb-32pt mb-md-0" />

      <div class="flex mb-32pt mb-md-0">
	<h2 class="text-white mb-0"><?=$info["name"] . " " . $info["lastname"]?></h2>
	<p class="lead text-white-50 d-flex align-items-center">
	  Estudiante
	</p>
      </div>
    </div>
  </div>

  <div class="page-section bg-alt border-bottom-2">
    <div class="container page__container">
      <div class="row">
	<div class="col-md-6">
	  <h4>Sobre mí</h4>
	  <p class="text-70">
	    <?php

	    if (!empty($customInfo[0]["about"])) {
		echo htmlspecialchars_decode($customInfo[0]["about"]);
	    } else {
		echo "Este estudiante aún no nos habla sobre él...<br><br>";
	    }

	    ?>
	    Mis intereses son:
	  </p>
	  <ul class="text-70">
	    <?php

	    $interests = explode(",", $info["wantlearn"]);

	    for ($i = 0; $i < sizeof($interests)-1; $i++) {
		echo "<li>". $interests[$i] ."</li>";
	    }
	    ?>
	  </ul>
	</div>

	<div class="col-md-6">
	  <h4>Contacto</h4>
	  <p class="text-70">
	    <?php

	    if (!empty($customInfo["connect"])) {
		echo htmlspecialchars_decode($customInfo["connect"]);
	    } else {
		echo "Este estudiante aún no nos habla sobre él...<br></br>";
	    }

	    ?>
	  </p>

	  <div class="d-flex align-items-center">
	    <?php

	    if (!empty($customInfo["facebook"])) {
		echo '
<a href="'.$customInfo["facebook"].'" class="text-accent fab fa-facebook-square
font-size-24pt mr-8pt"></a>
		';
	    }

	    if (!empty($customInfo["twitter"])) {
		echo '
<a href="'.$customInfo["twitter"].'" class="text-accent fab fa-twitter-square
font-size-24pt mr-8pt"></a>
		';
	    }

	    if (!empty($customInfo["youtube"])) {
		echo '
<a href="'.$customInfo["youtube"].'" class="text-accent fab fa-youtube-square
font-size-24pt mr-8pt"></a>
		';

	    }

	    if (!empty($customInfo["mastodon"])) {
		echo '
<a href="'.$customInfo["mastidon"].'" class="text-accent fab fa-mastodon-square
font-size-24pt mr-8pt"></a>
		';

	    }

	    if (!empty($customInfo["peertube"])) {
		echo '
<a href="'.$customInfo["peertube"].'" class="text-accent fab fa-youtube
font-size-24pt mr-8pt"></a>
		';

	    }

	    if (!empty($customInfo["facebook"])) {
		echo '
<a href="'.$customInfo["facebook"].'" class="text-accent fab fa-facebook-square
font-size-24pt mr-8pt"></a>
		';

	    }

	    if (!empty($customInfo["facebook"])) {
		echo '
<a href="'.$customInfo["facebook"].'" class="text-accent fab fa-facebook-square
font-size-24pt mr-8pt"></a>
		';

	    }

	    ?>
	  </div>
	</div>
      </div>
    </div>
  </div>

  <!-- TODO: Show student's courses and progress? -->
</div>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
