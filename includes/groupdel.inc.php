<?php 

require 'authcheck.inc.php'; 
require_once '../config.inc.php';
require_once '../functions.inc.php';

if(isset($_POST['groupID']))
{
    $groupid = cleaninput($_POST['groupID']);

    $values = viewmodel($groupid);

    if(isset($_POST['groupID']) && ($values['creator'] == $_SESSION['user'] || isadmin()) && $values['state'] == "1")
       removegroup($groupid);
}

if(isset($_POST['referrer']))
{
    if(isset($_POST['modelID']))
        deletefromgroup("model",$_POST['modelID']);
    if(isset($_POST['logID']))
        deletefromgroup("log",$_POST['logID']);
}
if(isset($_POST['modelID']))
{
    $arr = $_SESSION['grpmodels'];
    $key = $_POST['modelID'];
    unset($arr[$key]);
    $_SESSION['grpmodels'] = $arr;
    echo "removed!";
}
if(isset($_POST['logID']))
{
    $arr = $_SESSION['grplogs'];
    $key = $_POST['logID'];
    unset($arr[$key]);
    $_SESSION['grplogs'] = $arr;
    echo "removed!";
}
?>
