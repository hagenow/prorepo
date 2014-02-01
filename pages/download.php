<?php

require_once 'config.inc.php';
require_once 'functions.inc.php';

$id = '';

if(isset($_GET['id']))
{
    $id = $_GET['id'];
    getfile($id);
}
else
{
    echo "No ID given!";
}
?>
