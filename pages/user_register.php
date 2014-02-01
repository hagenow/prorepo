<?php
    if(empty($_POST['foobar']))
    {
        if(registeruser())
        {
            echo "Thank you, the registration was successful!<br/>Please check your mails and confirm your mailaddress!";
        }
        else
        {
            echo "There was an error, please go back and register new!";
        }

    }
    else
    {
        echo "Spam!?";
    }
?>
