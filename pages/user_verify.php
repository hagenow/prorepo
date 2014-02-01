<?php
if(verify($_GET['id']))
{
    echo "Your mailaddress is validated!";
}
else
{
    echo "Something went wrong or the URL was incorrect.<br>";
    echo "FOr further assistance contact the administrator!";
}
?>
