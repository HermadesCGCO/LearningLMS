        </div> <!-- .mdk-header-layout.js-mdk-header-layout -->

	<div class="bg-white border-top-2 mt-auto">
	  <div class="container page__container page-section
		      d-flex flex-column">
	    <p class="text-70 brand mb-24pt">
	      <img class="brand-icon" src="/public/images/logos/1.jpg" width="30">
	      Delta Learning
	    </p>

	    <p class="measure-lead-max text-50 small mr-8pt">
	      Delta Learning es una plataforma completamente gratuita de
	      aprendizaje. Aquí podrás aprender hacking, programación y
	      muchas más cosas relacionadas con la informática.
	    </p>

	    <p class="mb-8pt d-flex">
	      <a href="#" class="text-70 text-underline mr-8pt small">
		Término de uso
	      </a>
	      <a href="#" class="text-70 text-underline mr-8pt small">
		Políticas de privacidad
	      </a>
	    </p>

	    <p class="text-50 small mt-n1 mb-0">
	      Copyright 2021 &copy; Todos los derechos reservados.
	    </p>
	  </div>
	</div>

	<script src="/public/vendor/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="/public/vendor/popper.min.js"></script>
	<script src="/public/vendor/bootstrap.min.js"></script>
	<script src="/public/vendor/perfect-scrollbar.min.js"></script>
	<script src="/public/vendor/dom-factory.js"></script>
	<script src="/public/vendor/material-design-kit.js"></script>
	<script src="/public/js/app.js"></script>
	<script src="/public/js/preloader.js"></script>

	<?php if (isset($includeDashboard)) { ?>
	  <script src="/public/js/settings.js"></script>
	  <script src="/public/vendor/moment.min.js"></script>
	  <script src="/public/vendor/moment-range.js"></script>
	  <script src="/public/vendor/Chart.min.js"></script>
	  <script src="/public/js/chartjs-rounded-bar.js"></script>
	  <script src="/public/js/chartjs.js"></script>
	  <script src="/public/js/page.instructor-dashboard.js"></script>
	  <script src="/public/vendor/list.min.js"></script>
	  <script src="/public/js/list.js"></script>
	<?php } ?>

	<?php if (!isset($noDrawer)) {
	    include "includes/drawer.php";
	} ?>


	</body>

</html>
