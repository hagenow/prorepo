<?php
    if(empty($_POST['foobar']))
    {
        if(registeruser())
         {
            echo "Thank you, the registration was successful!<br/>Please check your mails and confirm your mailaddress!";
        }
        else
        {
            echo "There was an error, try again and check your data before sending!";
        }

    }
    else
    {
        echo "Spam!?";
    }
?>
