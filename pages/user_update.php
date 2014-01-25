<?php 
    require 'includes/authcheck.inc.php';

    if(updateuserdata())
    {
        echo "Your user was successfully updated!";
    }
    else
    {
        echo "There was an error, please go back and start again!";
    }
?>
