<?php
if(isset($_GET['show']))
{
    switch($_GET['show'])
    {
        case "login": require 'pages/user_login.php'; break;
        case "usersettings": require 'pages/user_settings.php'; break;
        case "register": require 'pages/user_register.php'; break;
        case "logout": require 'pages/user_logout.php'; break;
        case "notloggedin": require 'pages/user_notloggedin.php'; break;

        case "cat2": require 'pages/cat.php'; break;
        case "cat": require 'pages/cat_list.php'; break;
        case "mod": require 'pages/model_list.php'; break;
        case "newmod": require 'pages/model_new.php'; break;
        case "log": require 'pages/log_list.php'; break;
        case "newlog": require 'pages/log_new.php'; break;
        
        default: require 'pages/start.php'; break;
    }
}
else
{
    require 'pages/start.php';
}

?>

