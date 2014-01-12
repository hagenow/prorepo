<?php 
    require 'includes/authcheck.inc.php';
?>
<?php
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
echo session_id();
?>
