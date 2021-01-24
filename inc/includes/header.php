<div id="header" class="mdk-header mdk-header--bg-dark bg-dark js-mdk-header
	 mb-0" data-effects="parallax-background waterfall" data-fixed
     data-condenses>

  <?php if (isset($includeHero) && $includeHero == 1) : ?>
  <div class="mdk-header__bg">
    <div class="mdk-header__bg-front"
	 style="background-image: url(/public/images/students.jpg)"></div>
  </div>
  <?php endif; ?>

  <div class="mdk-header__content justify-content-center">
    <div class="navbar navbar-expand navbar-dark-pickled-bluewood
		bg-transparent will-fade-background"
	 id="default-navbar" data-primary>

      <button class="navbar-toggler w-auto mr-16pt d-block rounded-0"
	      type="button" data-toggle="sidebar">
	<span class="material-icons">short_text</span>
      </button>

      <a href="/" class="navbar-brand mr-16pt">
	<span class="avatar avatar-sm navbar-brand-icon mr-0 mr-lg-8pt">
	  <span class="avatar-title rounded bg-primary">
	    <img src="/public/images/illustration/illustration_student_white.svg"
		 class="img-fluid">
	  </span>
	</span>

	<span class="d-none d-lg-block">
	  <?php echo $_CONFIG["SITE_TITLE"]; ?>
	</span>
      </a>

      <!-- Top Menu -->
      <ul class="nav navbar-nav d-none d-sm-flex flex justify-content-start ml-8pt">

	<li class="nav-item">
	  <a href="/" class="nav-link">Inicio</a>
	</li>

	<li class="nav-item dropdown">
	  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"
	     data-caret="true">Explorar</a>

	  <div class="dropdown-menu">
	    <a href="#" class="dropdown-item">Cursos</a>
	    <a href="#" class="dropdown-item">Paths</a>
	    <a href="#" class="dropdown-item">Shows</a>
	    <a href="#" class="dropdown-item">Tutores</a>
	  </div>
	</li>

	<li class="nav-item">
	  <a href="#" class="nav-link">Blog</a>
	</li>

	<li class="nav-item dropdown">
	  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"
	     data-caret="true">Información</a>

	  <div class="dropdown-menu">
	    <a href="#" class="dropdown-item">Sobre Delta</a>
	    <a href="#" class="dropdown-item">Sobre Learning</a>
	  </div>
	</li>

	<?php if (isset($_SESSION["name"])) { ?>
	  <li class="nav-item dropdown" data-toggle="tooltip"
	      data-title="Comunidad" data-placement="bottom"
	      data-boundary="window">
	    <a href="#" class="nav-link dropdown-toggle"
	       data-toggle="dropdown" data-caret="false">
	      <i class="material-icons">people_outline</i>
	    </a>

	    <div class="dropdown-menu">
	      <a href="#" class="dropdown-item">Explorar usuarios</a>
	      <a href="#" class="dropdown-item">Explorar tutores</a>
	      <a href="#" class="dropdown-item">Centro de mensajes</a>
	      <a href="#" class="dropdown-item">Perfil</a>
	    </div>
	  </li>
	<?php } ?>

      </ul>
      <!-- End Top Menu -->

      <ul class="nav navbar-nav ml-auto mr-0">
	<li class="nav-item">
	  <a href="#" class="nav-link" data-toggle="tooltip"
	     data-title="Iniciar sesión" data-placement="bottom"
	     data-boundary="window">
	    <i class="material-icons">lock_open</i>
	  </a>
	</li>

	<li class="nav-item">
	  <a href="/signup" class="btn btn-outline-white">Únetenos</a>
	</li>
      </ul>

    </div>

    <?php if (isset($includeHero) && $includeHero == 1) : ?>
      <div class="hero container page__container text-center text-md-left py-112pt">
	<h1 class="text-white text-shadow">Aprende completamente gratis</h1>
	<p class="lead measure-hero lead mx-auto mx-md-0 text-white text-shadow mb-48pt">
	  Aprende programación, hacking y muchísimas cosas más de manera
	  completamente gratuita.
	</p>

	<a href="#" class="btn btn-lg btn-white btn--raised mb-16pt">
	  Explorar
	</a>

	<p class="mb-0">
	  <a href="#" class="text-white text-shadow">
	    <strong>¿Eres un tutor?</strong>
	  </a>
	</p>
      </div>
    <?php endif; ?>
  </div>

</div>
