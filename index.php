<?php

ob_start();
if(!isset($_SESSION)){
        session_start();
}
session_regenerate_id();

//$_SESSION['angemeldet'] = false;
$conid                  = '';
$input                  = array();
$login                  = false;
$error                  = '';


require_once 'config.inc.php';
require_once 'functions.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <!-- TODO: add favicon
    <title>Fixed Top Navbar Example for Bootstrap</title>
    -->

    <!-- Bootstrap core CSS -->
    <link href="template/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="template/css/navbar-fixed-top.css" rel="stylesheet">

    <!-- search css -->
    <link href="template/css/search.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <?php require_once 'includes/menu.inc.php'; ?>

<div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-9">
                <h3>Main Content</h3>
                <?php require_once 'includes/inhalt.inc.php'; ?>
            </div>
            <div class="col-xs-6 col-md-3">
                <h3>Usermenu</h3>
            </div>
</div>



<?php require 'includes/javascripts.inc.php'; ?>

  </body>
</html>

