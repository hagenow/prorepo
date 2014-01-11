<?php
    /** Lade die Konfigurationen und die Funktionen
    * */
    require_once 'config.inc.php';
    require_once 'functions.inc.php';
?>
<?php
    /** ausgelagerter HTML und CSS Code
     * */
    require_once 'includes/header.inc.php';
    require_once 'includes/css.inc.php';
?>
  <body>

    <div class="container">
<?php
    /** *  
     include 'includes/menu.php'; 
    /*/
?>


<?php 
    require_once 'includes/inhalt.inc.php'; 
?>
</div>
    

<?php
  require_once 'includes/javascripts.inc.php';
?>
  </body>
</html>

