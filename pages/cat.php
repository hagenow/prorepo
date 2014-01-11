<?php

require_once 'config.inc.php';
require_once 'functions.inc.php';

$conid = db_connect();

// Wurde das Formular abgeschickt?
if (isset( $_POST['submitbutton'] ))
{
    // if(!isset($_POST['filetype'])) die("Der Dateityp wurde nicht angegeben!");

    echo "<pre>" .print_r( $_POST, true ). "</pre>";

    /** write function that returns user-id or read user-id from session */

    if (isset( $_POST['catname'] ))
    {
        $catname = cleancatname($conid);
        createcat($conid, $catname, 0);
    }
    else
    {
        echo "Es wurde kein Kategoriename festgelegt!";
    }
}

?>
<?php

if(isset($_GET['catName']))
{
    require 'cat_find.php';
}
else
{
    require 'cat_def.php';
}

?>
