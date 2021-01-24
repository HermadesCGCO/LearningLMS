<div class="py-32pt navbar-submenu">
  <div class="container page__container">
    <div class="progression-bar progression-bar--active-accent">

      <a href="/signup" class="progression-bar__item
	       <?php
	       if ($_SERVER["REQUEST_URI"] == "/signup/details") {
		   echo "progression-bar__item--complete";
	       } else if ($_SERVER["REQUEST_URI"] == "/signup") {
		   echo "progression-bar__item--complete
		   progression-bar__item--active";
	       }
	       ?>
	       ">
	<span class="progression-bar__item-content">
	  <i class="material-icons progression-bar__item-icon">
	    <?php
	    if ($_SERVER["REQUEST_URI"] == "/signup/details") {
		echo "done";
	    }
	    ?>
	  </i>
	  <span class="progression-bar__item-text h5 mb-0 text-uppercase">
	    Registro
	  </span>
	</span>
      </a>

      <a href="/signup/details" class="progression-bar__item
	       <?php
	       if ($_SERVER["REQUEST_URI"] == "/signup/details") {
		   echo "progression-bar__item--complete
		   progression-bar__item--active";
	       }
	       ?>">
	<span class="progression-bar__item-content">
	  <i class="material-icons progression-bar__item-icon"></i>
	  <span class="progression-bar__item-text h5 mb-0 text-uppercase">
	    Detalles de la cuenta
	  </span>
	</span>
      </a>

    </div>
  </div>
</div>
