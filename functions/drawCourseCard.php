<?php

function drawCourseCard($info, $youlearn, $textVisit="Visitar", $url="default") {
    // Esta funcion es usada para dibujar el card de un curso.

    $youlearnHTML = "";

    if ($url == "default") {
	$url = "/course/" . $info["id"];
    } else if ($url == "edit") {
	$url = "/courses/edit/" . $info["id"];
    }

    for ($i = 0; $i < sizeof($youlearn); $i++) {
	$youlearnHTML .= '
<div class="d-flex align-items-center">

  <span class="material-icons icon-16pt text-50
    mr-8pt">check</span>

  <p class="flex text-50 lh-l mb-0">
    <small>'. $youlearn[$i]["content"] .'</small>
  </p>

</div>
    ';
    }

    $ratingHTML = "";

    for ($i = 0; $i < floor($info["rating"]); $i++) {
	$ratingHTML .= '
<span class="rating__item"><span class="material-icons">star</span></span>
	';

	if (floor($info["rating"]) < 5 && $i == floor($info["rating"])-1) {
	    $restant = abs(floor($info["rating"]) - 5);

	    for ($j = 0; $j < $restant; $j++) {
		$ratingHTML .= '
<span class="rating__item"><span class="material-icons">star_border</span></span>
		';
	    }
	}
    }

    if ($info["category"] == "Hacking") {
	$icon = "/public/images/icons/hacking.jpg";
    } else if ($info["category"] == "Programación") {
	$icon = "/public/images/icons/programming.jpg";
    } else if ($info["category"] == "Desarrollo web") {
	$icon = "/public/images/icons/web.jpg";
    } else if ($info["category"] == "Linux") {
	$icon = "/public/images/icons/linux.jpg";
    } else if ($info["category"] == "Desarrollo aplicaciones móviles") {
	$icon = "/public/images/icons/mobile.jpg";
    }


    return '
<div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

  <div class="card card-sm card--elevated p-relative o-hidden overlay
       overlay--primary-dodger-blue js-overlay mdk-reveal js-mdk-reveal
       card-group-row__card"
    data-partial-height="44"
    data-toggle="popover" data-trigger="click">

    <a href="'. $url . '" class="js-image" data-position="">
      <img src="' . $info["thumb"] . '" height="140" width="140">

      <span class="overlay__content align-items-start justify-content-start">
        <span class="overlay__action card-body d-flex align-items-center">
          <i class="material-icons mr-4pt">play_circle_outline</i>
          <span class="card-title text-white">'. $textVisit .'</span>
        </span>
      </span>
    </a>

    <div class="mdk-reveal__content">
      <div class="card-body">

        <div class="d-flex">
          <div class="flex">
            <a class="card-title" href="#">'. $info["name"] .'</a>
            <small class="text-50 font-weight-bold mb-4pt">
              ' . $info["tutor"] . '
            </small>
          </div>
        </div>

        <div class="d-flex">
          <div class="rating flex">
            '. $ratingHTML .'
          </div>

          <small class="text-50">'. $info["duration"] .' horas</small>
        </div>

      </div>
    </div>

  </div>

  <div class="popoverContainer d-none">

    <div class="media">
      <div class="media-left mr-12pt">
        <img src="'. $icon .'" class="rounded">
      </div>

      <div class="media-body">
        <h1 class="card-title mb-0">'. $info["name"] .'</h1>
        <p class="lh-l mb-0">
          <span class="text-50 small">por</span>
          <span class="text-50 small font-weight-bold">
            '. $info["tutor"] .'
          </span>
        </p>
      </div>
    </div>

    <p class="my-16pt text-70">
      '. $info["shortDesc"] .'
    </p>

    <div class="mb-16pt">
      '. $youlearnHTML .'
    </div>

    <div class="row align-items-center">
      <div class="col-auto">
        <div class="d-flex align-items-center mb-4pt">
          <span class="material-icons icon-16pt text-50 mr-4pt">
            access_time
          </span>

          <p class="flex text-50 lh-l mb-0">
            <small>'. $info["duration"] .' horas</small>
          </p>
        </div>

        <div class="d-flex align-items-center mb-4pt">
          <span class="material-icons icon-16pt text-50 mr-4pt">
            play_circle_outline
          </span>
          <p class="flex text-50 lh-l mb-0">
            <small>'. $info["lessons"] .' lecciones</small>
          </p>
        </div>

        <div class="d-flex align-items-center mb-4pt">
          <span class="material-icons icon-16pt text-50 mr-4pt">
            assessment
          </span>
          <p class="flex text-50 lh-l mb-0">
            <small>'. $info["difficulty"] .'</small>
          </p>
        </div>
      </div>

      <div class="col text-right">
        <a href="'. $url . '" class="btn btn-primary">
          '. $textVisit .'
        </a>
      </div>
    </div>

  </div>

</div>
    ';
}

?>
