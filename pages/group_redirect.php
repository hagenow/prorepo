<?php

require_once 'config.inc.php';
require_once 'functions.inc.php';

$id = '';

if(isset($_GET['guid']))
{
    $id = getgroupid($_GET['guid']);
    header("Location: ".$_SERVER['PHP_SELF']."?show=groupview&groupID=".$id."");
}
else
{
    echo "No ID given!";
}
?>
