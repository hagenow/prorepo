<?php
    if(registeruser() && !empty($_POST['foobar']))
    {
        echo "There was an error, please go back and register new!";
    }
    else
    {
        echo "Thank you, the registration was successful!<br/>Please check your mails and confirm your mailaddress!";
    }
?>
