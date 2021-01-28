<!DOCTYPE html>
<html lang="<?php echo $_CONFIG['SITE_LANGUAGE']; ?>" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
	  content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php if (isset($pageTitle)) { echo $pageTitle . " | "; }
	   echo $_CONFIG["SITE_TITLE"]; ?></title>

    <link href="https://fonts.googleapis.com/css?family=Lato:400,700%7CRoboto:400,500%7CExo+2:600&display=swap" rel="stylesheet">
    <link href="/public/vendor/spinkit.css" rel="stylesheet">
    <link href="/public/vendor/perfect-scrollbar.css" rel="stylesheet">
    <link href="/public/css/material-icons.css" rel="stylesheet">
    <link href="/public/css/fontawesome.css" rel="stylesheet">
    <link href="/public/css/preloader.css" rel="stylesheet">
    <link href="/public/css/app.css" rel="stylesheet">

    <?php if (isset($includeTinyMCE)) { ?>
      <script src="https://cdn.tiny.cloud/1/ed9i52pzqjggn51x1agkk35jmanr5v75no00uc54fhosxajd/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <?php } ?>
  </head>

  <body class="layout-sticky-subnav layout-default">

    <div class="preloader">
      <div class="sk-chase">
	<div class="sk-chase-dot"></div>
	<div class="sk-chase-dot"></div>
	<div class="sk-chase-dot"></div>
	<div class="sk-chase-dot"></div>
	<div class="sk-chase-dot"></div>
	<div class="sk-chase-dot"></div>
      </div>
    </div>

    <div class="mdk-header-layout js-mdk-header-layout">

      <?php if (!isset($noHeader)) { include "includes/header.php"; } ?>
