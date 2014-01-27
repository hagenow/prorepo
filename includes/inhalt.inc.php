<?php
if(isset($_GET['show']))
{
    switch($_GET['show'])
    {
        case "login": require 'pages/user_login.php'; break;
        case "usersettings": require 'pages/user_settings.php'; break;
        case "updateuser": require 'pages/user_update.php'; break;
        case "register": require 'pages/user_register.php'; break;
        case "mymodels": require 'pages/user_models.php'; break;
        case "mylogs": require 'pages/user_logs.php'; break;
        case "mygroups": require 'pages/user_groups.php'; break;
        case "logout": require 'pages/user_logout.php'; break;
        case "notloggedin": require 'pages/user_notloggedin.php'; break;

        case "contact": require 'pages/contact.php'; break;
        case "download": require 'pages/download.php'; break;

        case "cat": require 'pages/cat_list.php'; break;
        case "catnew": require 'pages/cat_new.php'; break;

        case "modlist": require 'pages/model_list.php'; break;
        case "modview": require 'pages/model_view.php'; break;
        case "modnew": require 'pages/model_new.php'; break;
        case "modedit": require 'pages/model_edit.php'; break;
        case "modupload": require 'pages/model_upload.php'; break;
        case "modbatch": require 'pages/under_construction.php'; break;

        case "loglist": require 'pages/log_list.php'; break;
        case "logview": require 'pages/log_view.php'; break;
        case "lognew": require 'pages/log_new.php'; break;
        case "logedit": require 'pages/log_edit.php'; break;
        case "logupload": require 'pages/log_upload.php'; break;
        case "logbatch": require 'pages/under_construction.php'; break;
        
        case "groupnew": require 'pages/group_new.php'; break;
        case "groupedit": require 'pages/group_edit.php'; break;
        case "groupview": require 'pages/group_view.php'; break;
        case "groupcurrent": require 'pages/group_current.php'; break;

        case "uc": require 'pages/under_construction.php'; break;

        default: require 'pages/start.php'; break;
    }
}
else
{
    require 'pages/start.php';
}

?>
