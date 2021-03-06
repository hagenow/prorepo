<?php
if(isset($_GET['show']))
{
    switch($_GET['show'])
    {
        case "login": require 'pages/user_login.php'; break;
        case "usersettings": require 'pages/user_settings.php'; break;
        case "usershow": require 'pages/user_show.php'; break;
        case "userpwreset": require 'pages/user_pwreset.php'; break;
        case "updateuser": require 'pages/user_update.php'; break;
        case "register": require 'pages/user_register.php'; break;
        case "verify": require 'pages/user_verify.php'; break;
        case "mymodels": require 'pages/user_models.php'; break;
        case "mylogs": require 'pages/user_logs.php'; break;
        case "mygroups": require 'pages/user_groups.php'; break;
        case "logout": require 'pages/user_logout.php'; break;
        case "notloggedin": require 'pages/user_notloggedin.php'; break;

        case "admincontact": require 'pages/admin_contact.php'; break;
        case "adminuser": require 'pages/admin_user.php'; break;
        case "contact": require 'content/contact.inc.php'; break;
        case "download": require 'pages/download.php'; break;
        case "editcontent": require 'pages/editcontent.php'; break;

        case "search": require 'pages/search.php'; break;
        case "batch1": require 'pages/batch_new1.php'; break;
        case "batch2": require 'pages/batch_new2.php'; break;

        case "cat": require 'pages/cat_list.php'; break;
        case "catnew": require 'pages/cat_new.php'; break;
        case "catedit": require 'pages/cat_edit.php'; break;

        case "modlist": require 'pages/model_list.php'; break;
        case "modview": require 'pages/model_view.php'; break;
        case "modnew": require 'pages/model_new.php'; break;
        case "moddelete": require 'pages/model_delete.php'; break;
        case "modedit": require 'pages/model_edit.php'; break;
        case "modupload": require 'pages/model_upload.php'; break;
        case "modbatch": require 'pages/under_construction.php'; break;

        case "loglist": require 'pages/log_list.php'; break;
        case "logview": require 'pages/log_view.php'; break;
        case "lognew": require 'pages/log_new.php'; break;
        case "logdelete": require 'pages/log_delete.php'; break;
        case "logedit": require 'pages/log_edit.php'; break;
        case "logupload": require 'pages/log_upload.php'; break;
        case "logbatch": require 'pages/under_construction.php'; break;
        
        case "groupnew": require 'pages/group_new.php'; break;
        case "groupedit": require 'pages/group_edit.php'; break;
        case "groupdelete": require 'pages/group_delete.php'; break;
        case "groupview": require 'pages/group_view.php'; break;
        case "groupcurrent": require 'pages/group_current.php'; break;
        case "group": require 'pages/group_redirect.php'; break;

        case "uc": require 'pages/under_construction.php'; break;

        case "404": require 'pages/error_404.php'; break;
        case "noauth": require 'pages/not_authorized.php'; break;

        default: require 'pages/start.php'; break;
    }
}
else
    require 'pages/start.php';
?>
