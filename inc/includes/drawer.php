<div class="mdk-drawer js-mdk-drawer" id="default-drawer">
  <div class="mdk-drawer__content">

    <div class="sidebar sidebar-dark-pickled-bluewood sidebar-left"
	 data-perfect-scrollbar>

      <a href="/"
	 class="sidebar-brand">
	<span class="avatar avatar-xl sidebar-brand-icon h-auto">
	  <span class="avatar-title rounded bg-primary">
	    <!-- TODO: Logo -->
	    <img src="" class="img-fluid">
	  </span>
	</span>

	<span><?php echo $_CONFIG["SITE_TITLE"]; ?></span>
      </a>

      <?php if (isset($user) && $user->isUserTutor()) { ?>
	<div class="sidebar-heading">
	  Panel de control
	</div>

	<ul class="sidebar-menu">

	  <li class="sidebar-menu-item">
	    <a class="sidebar-menu-button"
	       href="/tutor/dashboard">
	      <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">
		school
	      </span>
	      <span class="sidebar-menu-text">
		Panel de control
	      </span>
	    </a>
	  </li>

	  <li class="sidebar-menu-item">
	    <a class="sidebar-menu-button"
	       href="/tutor/courses">
	      <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">
		import_contacts
	      </span>
	      <span class="sidebar-menu-text">
		Gestionar cursos
	      </span>
	    </a>
	  </li>

	</ul>

	<div class="sidebar-heading">Estudiante</div>
      <?php } ?>

      <ul class="sidebar-menu">

	<li class="sidebar-menu-item">
	  <a class="sidebar-menu-button" href="/">
	    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">
	      home
	    </span>
	    <span class="sidebar-menu-text">
	      Inicio
	    </span>
	  </a>
	</li>

	<li class="sidebar-menu-item">
	  <a class="sidebar-menu-button" href="/explore">
	    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">
	      local_library
	    </span>
	    <span class="sidebar-menu-text">
	      Explorar Cursos
	    </span>
	  </a>
	</li>

	<li class="sidebar-menu-item">
	  <a class="sidebar-menu-button" href="#">
	    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">
	      style
	    </span>
	    <span class="sidebar-menu-text">
	      Explorar Paths
	    </span>
	  </a>
	</li>

	<li class="sidebar-menu-item">
	  <a class="sidebar-menu-button" href="#">
	    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">
	      account_box
	    </span>
	    <span class="sidebar-menu-text">Panel De Control</span>
	  </a>
	</li>

	<li class="sidebar-menu-item">
	  <a class="sidebar-menu-button" href="#">
	    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">
	      search
	    </span>
	    <span class="sidebar-menu-text">Mis Cursos</span>
	  </a>
	</li>

	<li class="sidebar-menu-item">
	  <a class="sidebar-menu-button" href="#">
	    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">
	      timeline
	    </span>
	    <span class="sidebar-menu-text">Mis Paths</span>
	  </a>
	</li>

      </ul>

    </div>

  </div>
</div>
