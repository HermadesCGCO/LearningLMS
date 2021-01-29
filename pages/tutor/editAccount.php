<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Tutor.php";

$tutor = new Tutor($conn, $_SESSION["name"]);
$info = $tutor->getInfo();

if (isset($_POST["submit"])) {
    if ($tutor->hasInfo()) {
	$tutor->updateInfo($_POST);
	header("Refresh: 0");
	exit();
    } else {
	$tutor->createInfo($_POST);
	header("Refresh: 0");
	exit();
    }
}


$pageTitle = "Actualiza tu información como tutor";

$includeTinyMCE = 1;
$noIncludeTutor = 1;
include "elements/comprobation.php";

?>

<div class="mdk-header-layout__content page-container">

  <div class="pt-32pt">
    <div class="container page__container d-flex flex-column flex-md-row
		align-items-center text-center text-sm-left">
      <div class="flex d-flex flex-column flex-sm-row align-items-center">

	<div class="mb-24pt mb-sm-0 mr-sm-24pt">
	  <h2 class="mb-0">Actualizar información de tutor</h2>
	</div>

      </div>
    </div>
  </div>

  <div class="container page__container page-section">
    <div class="page-separator">
      <div class="page-separator__text">Información de tutor</div>
    </div>

    <div class="col-md-8 p-0">
      <form method="POST">

	<div class="form-group">
	  <label class="form-label">Descripción corta</label>
	  <input type="text" class="form-control"
		 value="<?php echo $info["shortDesc"]; ?>" name="shortDesc">
	  <small class="text-form text-muted">
	    Cuéntanos de forma breve qué sabes hacer.
	  </small>
	</div>

	<div class="form-group">
	  <label class="form-label">Sobre ti</label>
	  <textarea id="description" name="description">
	    <?php echo $info["description"]; ?>
	  </textarea>
	  <small class="text-form text-muted">
	    Cuéntanos lo que sabes hacer, lo que estás aprendiendo, cuanta
	    experiencia tienes y qué te motiva a enseñarle a otros.
	  </small>
	</div>

	<input type="submit" name="submit" class="btn btn-primary"
		     value="Actualizar">

      </form>
    </div>
  </div>

</div>

<script>

 tinymce.init({
     selector: "#description",
     plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak"
 })

</script>

<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/footer.php";

?>
